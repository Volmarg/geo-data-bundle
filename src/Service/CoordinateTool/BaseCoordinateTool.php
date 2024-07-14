<?php

namespace GeoTool\Service\CoordinateTool;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Service\Request\GuzzleService;
use Psr\Log\LoggerInterface;
use TypeError;

/**
 * Base code for working with Coordinate tools,
 * Info: keep in mind that this is perfectly fine to have 2 different location names with the same coordinates
 *       this can happen when user searches for the same location but under different names, example:
 *      - Kolonia Niemcy,
 *      - Köln Deutschland,
 *
 */
abstract class BaseCoordinateTool
{

    /**
     * Will return information about how many coordinates can be passed per call
     *
     * @return int
     */
     public abstract function getMaxLocationsAmountPerCall(): int;

    /**
     * Will return geo-location data from api
     *
     * @param array $locationsNamesChunk
     * @return CoordinateDto[]
     */
    protected abstract function getGeoLocationFromApi(array $locationsNamesChunk): array;

    /**
     * @var LoggerInterface $logger
     */
    protected LoggerInterface $logger;

    /**
     * @var GuzzleService $guzzleService
     */
    protected GuzzleService $guzzleService;

    /**
     * @param LoggerInterface          $logger
     * @param GuzzleService            $guzzleService
     */
    public function __construct(
        LoggerInterface $logger,
        GuzzleService   $guzzleService
    )
    {
        $this->logger        = $logger;
        $this->guzzleService = $guzzleService;
    }

    /**
     * Will return geo-location data for location name
     *
     * @param array $locationsNames
     *
     * @return CoordinateDto[] | null
     */
    public function getCoordinatesForLocations(array $locationsNames): ?array
    {
        // that's because depending on api 1 or more locations can be returned from single call
        $locationNamesChunks  = array_chunk($locationsNames, $this->getMaxLocationsAmountPerCall());
        $locationsCoordinates = [];
        foreach($locationNamesChunks as $locationsNamesChunk){
            try{
                $locationsCoordinates = $this->getGeoLocationFromApi($locationsNames);

                if (count($locationsCoordinates) < count($locationsNamesChunk)) {
                    // check with locations names are missing and fetch only these via Api if they are not in DB yet
                    $foundLocationsNames = array_map(
                        fn(CoordinateDto $coordinateDto) => $coordinateDto->getLocationName(),
                        $locationsCoordinates,
                    );

                    $missingLocations     = array_diff($locationsNamesChunk, $foundLocationsNames);
                    $locationFromApi      = $this->getGeoLocationFromApi($missingLocations);
                    $locationsCoordinates = array_merge($locationsCoordinates, $locationFromApi);
                }
            }catch(Exception | TypeError $e){
                $this->logger->warning("Could not get the geo-location data", [
                    "exception" => [
                        "message" => $e->getMessage(),
                        "trace"   => $e->getTraceAsString(),
                    ]
                ]);
                return null;
            }

        }

        if( empty($locationsCoordinates) ){
            return null;
        }

        return $locationsCoordinates;
    }

}