<?php

namespace GeoTool\Service\LocationTool;

use GeoTool\Dto\GeoTool\LocationDataDto;

/**
 * Handles providing data from geo tools
 */
class LocationToolsService
{

    /**
     * @var LocationToolInterface[]
     */
    private array $locationToolApis;

    /**
     * @param LocationToolInterface[] $locationToolApis
     */
    public function setLocationToolApis(array $locationToolApis): void
    {
        $this->locationToolApis = $locationToolApis;
    }

    /**
     * Will communicate with all {@see LocationToolInterface} to find location data for given city name.
     * The first found result (no matter which API is it) will be used.
     *
     * All the API are used as "fallback" in case when no data is found in currently called one
     *
     * @param string $cityName
     *
     * @return LocationDataDto|null
     */
    public function getLocationData(string $cityName): ?LocationDataDto
    {
        $foundLocationData = null;
        foreach($this->locationToolApis as $service){
            $foundLocationData = $service->getLocationData($cityName);
            if( !empty($foundLocationData) ){
                return $foundLocationData;
            }
        }
        return $foundLocationData;
    }

}