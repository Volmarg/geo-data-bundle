<?php

namespace GeoTool\Service\OpenRouteService;

use GeoTool\Service\Env\EnvReaderService;
use GeoTool\Service\Request\GuzzleService;
use Psr\Log\LoggerInterface;

/**
 * Works with local copy of the open route service
 *
 * - {@link https://github.com/GIScience/openrouteservice}
 * - {@link https://openrouteservice.org/dev/#/api-docs}
 *
 */
class OpenRouteService
{
    /**
     * Return the base url that will be used to create absolute paths
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return EnvReaderService::getOpenRouteServiceBaseUrl();
    }

    public function __construct(
        protected readonly GuzzleService   $guzzleService,
        protected readonly LoggerInterface $logger,
    ){}

}
