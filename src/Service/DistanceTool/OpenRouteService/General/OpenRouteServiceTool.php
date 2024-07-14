<?php

namespace GeoTool\Service\DistanceTool\OpenRouteService\General;

use Exception;
use GeoTool\Dto\CoordinateTool\CoordinateDto;
use GeoTool\Dto\DistanceTool\DistanceDto;
use GeoTool\Service\DistanceTool\DistanceToolInterface;
use GeoTool\Service\OpenRouteService\DistanceOpenRouteService;
use GuzzleHttp\Exception\GuzzleException;

/**
 * This is self-hosted instance of api that calls the open street map and allows to calculate distance for FREE
 * Info: is MIT
 *
 * - {@link https://github.com/GIScience/openrouteservice}
 * - {@link https://openrouteservice.org/dev/#/api-docs}
 * - {@link https://giscience.github.io/openrouteservice/installation/Running-with-Docker.html}
 *
 * However the self hosted version has limited api endpoints
 * - check here {@link https://giscience.github.io/openrouteservice/installation/Installation-and-Usage.html#usage}
 */
class OpenRouteServiceTool implements DistanceToolInterface
{

    /**
     * @param DistanceOpenRouteService $distanceOpenRouteService
     */
    public function __construct(
        private readonly DistanceOpenRouteService $distanceOpenRouteService
    )
    {}

    /**
     * Get distance between 2 locations
     *
     * @param CoordinateDto[] $coordinateDtos
     *
     * @return DistanceDto|null
     * @throws GuzzleException
     * @throws Exception
     */
    public function getDistance(array $coordinateDtos): ?DistanceDto
    {
        return $this->distanceOpenRouteService->getDistance(...$coordinateDtos);
    }

}