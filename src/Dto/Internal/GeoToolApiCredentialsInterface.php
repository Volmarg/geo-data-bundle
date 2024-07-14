<?php

namespace GeoTool\Dto\Internal;

/**
 * Defines common logic for Geo tool api credentials
 */
interface GeoToolApiCredentialsInterface
{
    /**
     * Will return url used for city location fetching
     *
     * @return string
     */
    public function getCityLocationDataEndpoint(): string;
}