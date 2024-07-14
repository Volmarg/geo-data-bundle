<?php

namespace GeoTool\Service\DistanceTool;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use Psr\Log\LoggerInterface;
use TypeError;

class DistanceToolService
{
    /**
     * @var DistanceToolInterface[]
     */
    private array $distanceToolApis = [];

    /**
     * @var LoggerInterface $logger
     */
    private LoggerInterface $logger;

    /**
     * @param DistanceToolInterface[] $distanceToolApis
     */
    public function setDistanceToolApis(array $distanceToolApis): void
    {
        $this->distanceToolApis = $distanceToolApis;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Get distance between 2 locations (in km)
     *
     * @param CoordinateDto[] $coordinateDtos
     *
     *
     * @return float|null
     */
    public function getDistance(array $coordinateDtos): ?float
    {
        foreach ($coordinateDtos as $dto) {
            /**
             * Some tools (free map tool) have issues with coordinates which have bigger float value,
             * that's why rounding it up to 3 points
             */
            $dto->setLongitude(round($dto->getLongitude(), 3));
            $dto->setLatitude(round($dto->getLatitude(), 3));

            $numericIndexedDtos[] = $dto;
        }

        $firstCoordinateDto  = $numericIndexedDtos[0];
        $secondCoordinateDto = $numericIndexedDtos[1];

        foreach ($this->distanceToolApis as $distanceTool) {

            try {
                $distance = $distanceTool->getDistance($coordinateDtos);
                if (!empty($distance)) {
                    return $distance->getDistanceKm();
                }
            }catch(Exception | TypeError $exc){
                $this->logger->warning("Could not calculate distance between: {$firstCoordinateDto->getLocationName()} and {$secondCoordinateDto->getLocationName()}", [
                    "message" => $exc->getMessage(),
                    "trace"   => $exc->getTraceAsString(),
                ]);
                continue;
            }
        }

        return null;
    }

    /**
     * Basically it calculates some distance between two coordinates,
     * Keep in mind that this is the distance AKA: STRAIGHT LINE between 2 points, not driving distance
     *
     * Taken from {@link https://stackoverflow.com/questions/10053358/measuring-the-distance-between-two-coordinates-in-php}
     *
     * @param float $latitudeFrom  Latitude of start point in [deg decimal]
     * @param float $longitudeFrom Longitude of start point in [deg decimal]
     * @param float $latitudeTo    Latitude of target point in [deg decimal]
     * @param float $longitudeTo   Longitude of target point in [deg decimal]
     * @param float $earthRadius   Mean earth radius in [m]
     * @return float               Distance between points in [m] (same as earthRadius)
     */
    public static function calculateDistanceBetweenTwoPoints(
        float $latitudeFrom,
        float $longitudeFrom,
        float $latitudeTo,
        float $longitudeTo,
        float $earthRadius = 6371000
    )
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo   = deg2rad($latitudeTo);
        $lonTo   = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }

}