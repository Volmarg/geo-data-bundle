<?php

namespace GeoTool\Service\LocationTool;


use GeoTool\Dto\GeoTool\LocationDataDto;

/**
 * This interface describes common logic for Geo Tools
 */
interface LocationToolInterface
{
    /**
     * Will return single location data for given city name
     *
     * @param string $cityName
     * @return LocationDataDto|null
     */
    public function getLocationData(string $cityName): ?LocationDataDto;
}