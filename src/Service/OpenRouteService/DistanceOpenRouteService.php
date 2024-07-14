<?php

namespace GeoTool\Service\OpenRouteService;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Dto\DistanceTool\DistanceDto;
use GeoTool\Service\Env\EnvReaderService;
use GeoTool\Service\NominatimService\NominatimSearchService;
use GeoTool\Service\Request\GuzzleService;
use GeoTool\Service\Request\GuzzleServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;
use TypeError;

/**
 * {@see OpenRouteService} but handles the distance end-point
 */
class DistanceOpenRouteService extends OpenRouteService
{
    private const END_POINT = "v2/directions/driving-car/json";

    public function __construct(
        GuzzleService   $guzzleService,
        LoggerInterface $logger,

        // it can be used as it relies on localhost instance, so no costs are generated
        private readonly NominatimSearchService $nominatimSearchService
    )
    {
        parent::__construct($guzzleService, $logger);
    }

    /**
     * Returns the distance between 2 coordinates
     *
     * @param CoordinateDto $firstCoordinate
     * @param CoordinateDto $secondCoordinate
     *
     * @return DistanceDto|null
     *
     * @throws GuzzleException
     *
     * @throws Exception
     */
    public function getDistance(CoordinateDto $firstCoordinate, CoordinateDto $secondCoordinate): ?DistanceDto
    {
        $this->checkAndFillCoordinates($firstCoordinate);
        $this->checkAndFillCoordinates($secondCoordinate);

        $responseData = $this->getResponse($firstCoordinate, $secondCoordinate);
        if (empty($responseData)) {
            return null;
        }

        $distance = $this->extractDistanceFromResponse($responseData);

        $distanceDto = new DistanceDto();
        $distanceDto->setDistanceKm($distance);

        return $distanceDto;
    }

    /**
     * Will call the endpoint and return response array, or null if something goes wrong
     *
     * @param CoordinateDto $firstCoordinate
     * @param CoordinateDto $secondCoordinate
     *
     * @return array|null
     *
     * @throws GuzzleException
     */
    private function getResponse(CoordinateDto $firstCoordinate, CoordinateDto $secondCoordinate): ?array
    {
        try {
            $calledUrl = $this->getBaseUrl() . self::END_POINT;
            $options   = [
                GuzzleServiceInterface::KEY_JSON_REQUEST_BODY => $this->buildRequestData($firstCoordinate, $secondCoordinate),
                'auth' => [
                    EnvReaderService::getOpenRouteServiceUsername(),
                    EnvReaderService::getOpenRouteServicePassword()
                ]
            ];

            $response        = $this->guzzleService->post($calledUrl, $options);
            $responseContent = $response->getBody()->getContents();

            if (empty($responseContent)) {
                throw new Exception("Response content is empty!");
            }

            $responseArray = json_decode($responseContent, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new Exception("Could not parse the json {$responseContent}, got error: " . json_last_error_msg());
            }

            return $responseArray;
        }catch(Exception|TypeError $e) {
            $this->logger->warning("Failed getting the response from endpoint: " . self::END_POINT, [
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }

    }

    /**
     * Returns the data sent to the endpoint
     *
     * @param CoordinateDto $firstCoordinate
     * @param CoordinateDto $secondCoordinate
     *
     * @return array
     */
    private function buildRequestData(CoordinateDto $firstCoordinate, CoordinateDto $secondCoordinate): array
    {
        return [
            "coordinates" => [
                [
                    $firstCoordinate->getLongitude(),
                    $firstCoordinate->getLatitude(),
                ],
                [
                    $secondCoordinate->getLongitude(),
                    $secondCoordinate->getLatitude(),
                ]
            ]
        ];
    }

    /**
     * Will extract the distance from api response
     *
     * @param array $responseData
     *
     * @return float|null
     *
     * @throws Exception
     */
    private function extractDistanceFromResponse(array $responseData): ?float
    {
        $responseContent = json_encode($responseData);
        if (!array_key_exists("routes", $responseData)) {
            throw new Exception("`routes` key does not exist in response: {$responseContent}");
        }

        $routesArray = $responseData["routes"][0];
        if (!array_key_exists("summary", $routesArray)) {
            throw new Exception("`summary` key does not exist in the routes tree in response: {$responseContent}");
        }

        $summaryArray = $routesArray["summary"];
        if (!array_key_exists("distance", $summaryArray)) {
            throw new Exception("`distance` key does not exist in the summary tree in response: {$responseContent}");
        }

        $distanceMeters = $summaryArray["distance"];
        $distanceKm     = ($distanceMeters / 1000);

        return round((float)$distanceKm, 2);
    }

    /**
     * If the provided {@see CoordinateDto} is missing geo coordinates then an attempt to fetch this will be made
     *
     * @param CoordinateDto $coordinateDto
     *
     * @throws GuzzleException
     * @throws Exception
     */
    private function checkAndFillCoordinates(CoordinateDto $coordinateDto): void
    {
        if(
                !empty($coordinateDto->getLongitude())
            &&  !empty($coordinateDto->getLatitude())
        ){
            return; // no need to prefill
        }

        if (empty($coordinateDto->getLocationName())) {
            throw new Exception("Coordinate dto structure is empty!");
        }

        $searchResult = $this->nominatimSearchService->getHighestImportanceResult($coordinateDto->getLocationName());

        $coordinateDto->setLatitude($searchResult->getLattitude());
        $coordinateDto->setLongitude($searchResult->getLongitude());
    }

}