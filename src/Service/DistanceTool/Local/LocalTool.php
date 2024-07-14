<?php

namespace GeoTool\Service\DistanceTool\Local;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Dto\DistanceTool\DistanceDto;
use GeoTool\Service\DistanceTool\DistanceToolInterface;
use GeoTool\Service\DistanceTool\DistanceToolService;
use Psr\Log\LoggerInterface;
use TypeError;

/**
 * Handles distance calculation locally, relies fully on mathematical calculation of distance between 2 geo
 * coordinate points.
 *
 * Is less accurate, cannot handle the "car routes", only makes straight line between 2 points.
 */
class LocalTool implements DistanceToolInterface
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(
        private LoggerInterface $logger,
    ){}

    /**
     * Get distance between 2 locations
     *
     * @param CoordinateDto[] $coordinateDtos
     *
     * @return DistanceDto|null
     * @throws Exception
     */
    public function getDistance(array $coordinateDtos): ?DistanceDto
    {
        try{
            $firstCoordinateDto  = $coordinateDtos[0];
            $secondCoordinateDto = $coordinateDtos[1];

            $distanceMeters = DistanceToolService::calculateDistanceBetweenTwoPoints(
                $firstCoordinateDto->getLatitude(),
                $firstCoordinateDto->getLongitude(),
                $secondCoordinateDto->getLatitude(),
                $secondCoordinateDto->getLongitude()
            );

            $distanceKm = round(($distanceMeters / 1000),2);

            $distanceDto = new DistanceDto();
            $distanceDto->setDistanceKm($distanceKm);
            $distanceDto->setStartPoint($firstCoordinateDto->getLocationName());
            $distanceDto->setEndPoint($secondCoordinateDto->getLocationName());
            $distanceDto->setStartPointCoordinateDto($firstCoordinateDto);
            $distanceDto->setEndPointCoordinateDto($secondCoordinateDto);;

        } catch (Exception|TypeError $e) {
            $this->logger->warning("Could not get the distance for coordinates", [
                "Tool" => "local",
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }

        return $distanceDto;
    }

}