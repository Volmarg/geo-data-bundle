<?php

namespace GeoTool\Controller;

use GeoTool\Service\DistanceTool\DistanceToolService;
use GeoTool\Service\LocationTool\LocationToolsService;
use GeoTool\Service\LocationTool\Nominatim\General\NominatimLocationTool;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    /**
     * @param DistanceToolService   $distanceToTool
     * @param LocationToolsService  $locationToolsService
     * @param NominatimLocationTool $nominatimLocationTool
     */
    public function __construct(
        LocationToolsService                      $locationToolsService,
        private readonly NominatimLocationTool    $nominatimLocationTool,
        private readonly DistanceToolService      $distanceToolService
    )
    {
    }

    /**
     * @return never
     */
    #[Route("/test/")]
    public function testCalls(): never
    {
        die("DIED");
    }

}