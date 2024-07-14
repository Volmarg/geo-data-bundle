<?php

namespace GeoTool\Service\NominatimService;

use GeoTool\Enum\Nominatim\OsmTypeEnum;
use GeoTool\Service\Request\GuzzleService;
use Psr\Log\LoggerInterface;

/**
 * This service is basically based on the OPEN STREET MAP
 * And it's this: {@see https://nominatim.openstreetmap.org/}
 *
 * Then only difference here is that this is being run on private server, so that's private instance
 */
class NominatimService
{
    /**
     * Return the base url that will be used to create absolute paths
     *
     * @return string
     */
    protected function getBaseUrl(): string
    {
        return $_ENV['NOMINATIM_SERVICE_TOOL_BASE_URL'];
    }

    public function __construct(
        protected readonly GuzzleService   $guzzleService,
        protected readonly LoggerInterface $logger,
    ){}

    /**
     * @param OsmTypeEnum $enum
     *
     * @return OsmTypeEnum
     */
    protected function mapOsmTypeToShort(OsmTypeEnum $enum): OsmTypeEnum
    {
        $shortEnum = match ($enum) {
            OsmTypeEnum::FULL_NODE     => OsmTypeEnum::SHORT_NODE,
            OsmTypeEnum::FULL_WAY      => OsmTypeEnum::SHORT_WAY,
            OsmTypeEnum::FULL_RELATION => OsmTypeEnum::SHORT_RELATION,
        };

        return $shortEnum;
    }

    /**
     * @param OsmTypeEnum $enum
     *
     * @return OsmTypeEnum
     */
    protected function mapOsmShortTypeToFull(OsmTypeEnum $enum): OsmTypeEnum
    {
        $shortEnum = match ($enum) {
            OsmTypeEnum::SHORT_NODE     => OsmTypeEnum::FULL_NODE,
            OsmTypeEnum::SHORT_WAY      => OsmTypeEnum::FULL_WAY,
            OsmTypeEnum::SHORT_RELATION => OsmTypeEnum::FULL_RELATION,
        };

        return $shortEnum;
    }

}