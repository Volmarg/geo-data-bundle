<?php

namespace GeoTool\Service\LocationTool\PositionStack\General;

use Exception;
use GeoTool\Dto\GeoTool\LocationDataDto;
use GeoTool\Dto\GeoTool\PositionStackLocationDataDto;
use GeoTool\Exception\GeoToolException;
use GeoTool\Service\ConfigLoader\ApiConfigLoaderService;
use GeoTool\Service\LocationTool\PositionStack\PositionStackInterface;
use GeoTool\Service\Request\GuzzleService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @link https://positionstack.com/
 * @link https://positionstack.com/quickstart
 * @link https://positionstack.com/documentation
 */
class PositionStackLocationTool implements PositionStackInterface
{
    const KEY_DATA = "data";

    private const MAX_ERROR_BEFORE_DOWN = 10;

    private bool $isDown      = false;
    private int  $errorsCount = 0;

    /**
     * @var ApiConfigLoaderService
     */
    private ApiConfigLoaderService $apiConfigLoaderService;

    /**
     * @var GuzzleService $guzzleService
     */
    private GuzzleService $guzzleService;

    /**
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    /**
     * @param ApiConfigLoaderService $apiConfigLoaderService
     * @param GuzzleService          $guzzleService
     * @param SerializerInterface    $serializer
     * @param LoggerInterface        $logger
     */
    public function __construct(
        ApiConfigLoaderService           $apiConfigLoaderService,
        GuzzleService                    $guzzleService,
        SerializerInterface              $serializer,
        private readonly LoggerInterface $logger
    )
    {
        $this->serializer             = $serializer;
        $this->guzzleService          = $guzzleService;
        $this->apiConfigLoaderService = $apiConfigLoaderService;
    }

    /**
     * {@inheritDoc}
     *
     * If there are few elements with the same highest confidence then only the first one will be returned
     *
     * @param string $cityName
     *
     * @return LocationDataDto|null
     * @throws GeoToolException
     * @throws GuzzleException
     */
    public function getLocationData(string $cityName): ?LocationDataDto
    {
        if ($this->isDown) {
            return null;
        }

        $positionStackDtos = $this->fetchLocationDataFromApi($cityName);

        $highestConfidence = null;
        $highConfidenceDto = null;
        foreach($positionStackDtos as $dto){
            if($dto->getConfidence() > $highestConfidence){
                $highestConfidence = $dto->getConfidence();
                $highConfidenceDto = $dto;
            }
        }

        if( empty($highConfidenceDto) ){
            return null;
        }

        $locationData = new LocationDataDto();
        $locationData->setLatitude($highConfidenceDto->getLatitude());
        $locationData->setLongitude($highConfidenceDto->getLongitude());
        $locationData->setRegion($highConfidenceDto->getRegion());
        $locationData->setRegionCode($highConfidenceDto->getRegionCode());
        $locationData->setLocationName($highConfidenceDto->getName());
        $locationData->setNativeLanguageCityName($highConfidenceDto->getAdministrativeArea());
        $locationData->setCountry($highConfidenceDto->getCountry());
        $locationData->setCountryCode($highConfidenceDto->getCountryCode());
        $locationData->setContinent($highConfidenceDto->getContinent());

        return $locationData;
    }

    /**
     * Return the array of data fetched from api
     *
     * @param string $cityName
     * @return PositionStackLocationDataDto[]
     * @throws GeoToolException
     * @throws GuzzleException
     */
    private function fetchLocationDataFromApi(string $cityName): array
    {
        $url = $this->apiConfigLoaderService->getPositionStackApiCredential()->getCityLocationDataEndpoint() . $cityName;

        try {
            $response = $this->guzzleService->get($url);
        } catch (Exception $e) {
            $this->errorsCount++;
            if ($this->errorsCount === self::MAX_ERROR_BEFORE_DOWN) {
                $this->isDown = true;
                $this->logger->critical("Position stack is down, tried to obtain data few times but failed", [
                    "lastExceptionMessage" => $e->getMessage(),
                ]);
            }

            return [];
        }

        $responseBody = $response->getBody()->getContents();
        if($response->getStatusCode() !== Response::HTTP_OK){
            $message = "Could not fetch data from api using: " . __CLASS__
                     . "Reason: {$response->getReasonPhrase()}. Response: {$responseBody}";
            throw new GeoToolException($message);
        }

        $responseDataArray = json_decode($responseBody, true);
        if( JSON_ERROR_NONE !== json_last_error() ){
            $message = "Could not parse response, this is not valid json"
                     . "Response: {$responseBody}. Json error: " . json_last_error_msg();
            throw new GeoToolException($message);
        }

        $locationsData = $responseDataArray[self::KEY_DATA] ?? null;
        if( is_null($locationsData) ){
            throw new GeoToolException("Could not read data from, key is missing in response: " . self::KEY_DATA);
        }

        $locationDataDtos = array_map(
            fn(array $dataArray) => $this->serializer->deserialize(json_encode($dataArray), PositionStackLocationDataDto::class, "json"),
            $locationsData
        );

        return $locationDataDtos;
    }
}