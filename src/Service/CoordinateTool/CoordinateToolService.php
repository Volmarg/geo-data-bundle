<?php

namespace GeoTool\Service\CoordinateTool;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use TypeError;

/**
 * Handles returning geolocation coordinates
 */
class CoordinateToolService
{

    /**
     * @var BaseCoordinateTool[]
     */
    private array $coordinationToolApis = [];

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @var SerializerInterface $serializer
     */
    private SerializerInterface $serializer;

    /**
     * @param BaseCoordinateTool[] $coordinationToolApis
     */
    public function setCoordinationToolApis(array $coordinationToolApis): void
    {
        $this->coordinationToolApis = $coordinationToolApis;
    }

    /**
     * @param LoggerInterface     $logger
     * @param SerializerInterface $serializer
     */
    public function __construct(LoggerInterface $logger, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->logger     = $logger;
    }

    /**
     * Will return coordinates for provided locations
     *
     * Todo: need to pass array of locations here, where the key can either be coordinateDto (that was found before),
     *       or null if non was found before, this will then allow to replace {@see BaseCoordinateTool::getCoordinateFromDbAndApi}
     *
     * @param string[] $locationsNames
     * @return ?Array<null, CoordinateDto>
     */
    public function getCoordinates(array $locationsNames): ?array
    {
        try{
            /**
             * Info: if coordinate is missing for any location then it will retry for both of them
             *       - in case of performance issues this could be optimized to fetch only the missing ones
             */
            foreach($this->coordinationToolApis as $coordinationToolApi){
                $coordinates = $coordinationToolApi->getCoordinatesForLocations($locationsNames);

                if( !empty($coordinates) ){
                    $countOfCoordinates    = count($coordinates);
                    $countOfLocationsNames = count($locationsNames);
                    if( $countOfCoordinates < $countOfLocationsNames ){

                        $serializedCoordinates = array_map(
                            fn(CoordinateDto $coordinateDto) => $this->serializer->serialize($coordinateDto, "json"),
                            $coordinates
                        );

                        $this->logger->warning("Could not get coordinates for all of provided locations names", [
                            "locationsNames" => $locationsNames,
                            "coordinates"    => $serializedCoordinates,
                        ]);
                        continue;
                    }

                    return $coordinates;
                }
            }

            throw new Exception("No coordinates were found for provided locations names");
        }catch(Exception | TypeError $e){
            $this->logger->warning("Could not get coordinates for location names", [
                "locationsNames" => $locationsNames,
                "exception"      => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }
    }
}