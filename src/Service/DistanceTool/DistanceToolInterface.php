<?php

namespace GeoTool\Service\DistanceTool;

use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Dto\DistanceTool\DistanceDto;

/**
 * Common definitions of logic for distance calculations
 * Todo: replace with the AbstractDistanceTool + set the proper class in {@see DistanceToolService}
 *
 */
interface DistanceToolInterface
{
    const DISTANCE_GEO_LOCATION_SIMILARITY_PERCENTAGE = 5;

    /**
     * Will provide distance between 2 places (in KM)
     *
     * @param CoordinateDto[] $coordinateDtos
     *
     * @return DistanceDto|null
     */
    public function getDistance(array $coordinateDtos): ?DistanceDto;
}