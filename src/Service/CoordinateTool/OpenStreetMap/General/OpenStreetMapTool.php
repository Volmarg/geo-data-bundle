<?php

namespace GeoTool\Service\CoordinateTool\OpenStreetMap\General;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Service\CoordinateTool\BaseCoordinateTool;
use GeoTool\Service\CoordinateTool\OpenStreetMap\OpenStreetMapInterface;
use GeoTool\Service\Request\GuzzleService;
use Psr\Log\LoggerInterface;
use TypeError;

/**
 * @link https://wiki.osmfoundation.org/wiki/FAQ#:~:text=the%20OpenStreetMap%20community.-,Is%20OpenStreetMap%20data%20free%3F,our%20distribution%20licence%2C%20the%20ODbL.
 * @link https://nominatim.org/release-docs/develop/api/Search/
 *
 * Info: seems like this API is not supporting fetching multiple location data
 * Info2: this is totally MIT (free to use)
 *
 * This calls the official based map instance
 */
class OpenStreetMapTool extends BaseCoordinateTool implements OpenStreetMapInterface
{
    private const PARAM_FORMAT        = "format";
    private const PARAM_LIMIT_RESULTS = "limit";
    private const PARAM_QUERY         = "q";

    private const KEY_LONGITUDE = "lon";
    private const KEY_LATITUDE  = "lat";

    /**
     * @param GuzzleService   $guzzleService
     * @param LoggerInterface $logger
     */
    public function __construct(
        GuzzleService   $guzzleService,
        LoggerInterface $logger
    )
    {
        parent::__construct($logger, $guzzleService);
    }

    /**
     * @param string $location
     * @return string
     */
    public function buildGetCoordinatesUrl(string $location): string
    {
        $paramsArray = [
          self::PARAM_FORMAT        => 'jsonv2',
          self::PARAM_LIMIT_RESULTS => 1,
          self::PARAM_QUERY         => $location,
        ];

        $paramsString = http_build_query($paramsArray);

        return "https://nominatim.openstreetmap.org/search?" . $paramsString;
    }

    /**
     * {@inheritDoc}
     * @return int
     */
    public function getMaxLocationsAmountPerCall(): int
    {
        return 1;
    }

    /**
     * {@inheritDoc}
     */
    public function getGeoLocationFromApi(array $locationsNames): array
    {
        try{
            $coordinates = [];
            foreach($locationsNames as $locationsName){
                $calledUrl       = $this->buildGetCoordinatesUrl($locationsName);
                $response        = $this->guzzleService->get($calledUrl);
                $responseContent = $response->getBody()->getContents();

                if( empty($responseContent) ){
                    throw new Exception("Response is empty. Called url: {$calledUrl}");
                }

                $responseDataArray = json_decode($responseContent, true);
                if( JSON_ERROR_NONE !== json_last_error() ){
                    throw new Exception("Response is not valid json: {$responseContent}. Got error: " . json_last_error_msg());
                }

                if( empty($responseDataArray) ){
                    $this->logger->warning("No location information was found on OpenStreetMap for location: {$locationsName}");
                    continue;
                }

                $locationData = $responseDataArray[0];
                if( !array_key_exists(self::KEY_LATITUDE, $locationData) ){
                    throw new Exception("OpenStreetMap response is missing latitude key in data array: {$responseContent}, searched: " . self::KEY_LATITUDE);
                }

                if( !array_key_exists(self::KEY_LONGITUDE, $locationData) ){
                    throw new Exception("OpenStreetMap response is missing longitude key in data array: {$responseContent}, searched: " . self::KEY_LONGITUDE);
                }

                $latitude  = $locationData[self::KEY_LATITUDE];
                $longitude = $locationData[self::KEY_LONGITUDE];

                $coordinateDto = new CoordinateDto();
                $coordinateDto->setLocationName($locationsName);
                $coordinateDto->setLongitude($longitude);
                $coordinateDto->setLatitude($latitude);

                $coordinates[] = $coordinateDto;
            }

            return $coordinates;
        }catch(Exception | TypeError $e){
            $this->logger->warning("Could not fetch coordinates", [
                "locationNames" => $locationsNames,
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            throw $e;
        }

    }
}