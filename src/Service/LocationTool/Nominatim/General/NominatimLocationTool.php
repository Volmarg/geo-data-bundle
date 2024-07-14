<?php

namespace GeoTool\Service\LocationTool\Nominatim\General;

use Exception;
use GeoTool\Dto\GeoTool\LocationDataDto;
use GeoTool\Service\LocationTool\LocationToolInterface;
use GeoTool\Service\NominatimService\NominatimDetailService;
use GeoTool\Service\NominatimService\NominatimSearchService;
use GeoTool\Service\NominatimService\NominatimService;
use GuzzleHttp\Exception\GuzzleException;
use League\ISO3166\ISO3166;
use Psr\Log\LoggerInterface;
use TypeError;

/**
 * Locally ran instance of:
 *
 * {@link https://nominatim.org/release-docs/develop/api/Details/}
 *
 * {@see NominatimService}
 * {@see NominatimDetailService}
 * {@see NominatimSearchService}
 */
class NominatimLocationTool implements LocationToolInterface
{
    /**
     * @param LoggerInterface $logger
     * @param NominatimDetailService $nominatimDetailService
     * @param NominatimSearchService $nominatimSearchService
     */
    public function __construct(
        private readonly LoggerInterface        $logger,
        private readonly NominatimDetailService $nominatimDetailService,
        private readonly NominatimSearchService $nominatimSearchService,
    )
    {}

    /**
     * {@inheritDoc}
     *
     * If there are few elements with the same highest confidence then only the first one will be returned
     *
     * @param string $cityName
     *
     * @return LocationDataDto|null
     * @throws GuzzleException
     */
    public function getLocationData(string $locationName): ?LocationDataDto
    {
        try {
            $searchResult = $this->nominatimSearchService->getHighestImportanceResult($locationName);
            if (empty($searchResult)) {
                return null;
            }

            $detailsResult = $this->nominatimDetailService->getDetails($searchResult->getOsmId(), $searchResult->getOsmTypeShort());

            $locationData = new LocationDataDto();
            $locationData->setLatitude($searchResult->getLattitude());
            $locationData->setLongitude($searchResult->getLongitude());
            $locationData->setLocationName($locationName);
            $locationData->setNativeLanguageCityName($detailsResult->getLocalName());
            $locationData->setCountryCode($detailsResult->getCountryCode());

            $countryName = null;
            if (!empty($detailsResult->getCountryCode())) {
                $data = (new ISO3166)->alpha2($detailsResult->getCountryCode());
                if (!empty($data)) {
                    $countryName = $data['name'] ?? null;
                }
            }

            $locationData->setCountry($countryName);
            $locationData->setContinent("");
            $locationData->setRegion(null);
            $locationData->setRegionCode(null);

            return $locationData;
        } catch (Exception|TypeError $e) {
            $this->logger->warning("Could not get location data", [
                "class" => self::class,
                'func'  => __FUNCTION__,
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }
    }

}