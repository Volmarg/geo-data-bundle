<?php

namespace GeoTool\Service\NominatimService;

use Exception;
use GeoTool\Dto\Nominatim\DetailsResult;
use GeoTool\Enum\Nominatim\OsmTypeEnum;
use GeoTool\Service\Env\EnvReaderService;
use GeoTool\Service\Request\GuzzleServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use TypeError;

/**
 * Handles the: {@link https://nominatim.org/release-docs/develop/api/Details/}
 */
class NominatimDetailService extends NominatimService
{
    private const END_POINT      = "details";
    private const PARAM_OSM_ID   = "osmid";
    private const PARAM_OSM_TYPE = "osmtype";

    /**
     * Returns more information about given osm id (probably some internal OSM identifier to track places down),
     *
     * @param int $osmId
     * @param OsmTypeEnum $osmTypeShort
     *
     * @return DetailsResult|null
     *
     * @throws GuzzleException
     */
    public function getDetails(int $osmId, OsmTypeEnum $osmTypeShort): ?DetailsResult
    {
        try {
            $responseArray = $this->getResponse($osmId, $osmTypeShort);
            $detailsResult = $this->buildSearchResult($responseArray);

            return $detailsResult;
        }catch(Exception | TypeError $e){
            $this->logger->warning("Failed getting search result: " . self::END_POINT, [
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }
    }

    /**
     * Will call the endpoint and return response array, or null if something goes wrong
     *
     * @param int $osmId
     * @param OsmTypeEnum $osmTypeShort
     * @return array|null
     *
     * @throws GuzzleException
     */
    private function getResponse(int $osmId, OsmTypeEnum $osmTypeShort): ?array
    {
        try {

            $calledUrl = EnvReaderService::getNominatimBaseUrl() . self::END_POINT;
            $options   = [
                GuzzleServiceInterface::KEY_QUERY => $this->buildQueryParams($osmId, $osmTypeShort),
                'auth' => [
                    EnvReaderService::getNominatimUsername(),
                    EnvReaderService::getNominatimPassword()
                ]
            ];

            $response        = $this->guzzleService->get($calledUrl, $options);
            $responseContent = $response->getBody()->getContents();

            if (empty($responseContent)) {
                throw new Exception("Response content is empty!");
            }

            $responseArray = json_decode($responseContent, true);
            if (JSON_ERROR_NONE !== json_last_error()) {
                throw new Exception("Could not parse the json {$responseContent}, got error: " . json_last_error_msg());
            }

            return $responseArray;
        } catch (Exception|TypeError $e) {
            $this->logger->warning("Failed getting the response from endpoint: " . self::END_POINT, [
                "exception" => [
                    "message" => $e->getMessage(),
                    "trace"   => $e->getTraceAsString(),
                ]
            ]);

            return null;
        }

    }

    /**
     * Returns the data sent to the endpoint
     *
     * @param int $osmId
     * @param OsmTypeEnum $osmTypeShort
     *
     * @return array
     */
    private function buildQueryParams(int $osmId, OsmTypeEnum $osmTypeShort): array
    {
        return [
            self::PARAM_OSM_ID   => $osmId,
            self::PARAM_OSM_TYPE => $osmTypeShort->value,
        ];
    }

    /**
     * @param array $resultData
     *
     * @return DetailsResult
     */
    private function buildSearchResult(array $resultData): DetailsResult
    {
        $osmType     = $resultData['osm_type'];
        $osmId       = $resultData['osm_id'];
        $category    = $resultData['category'];
        $type        = $resultData['type'];
        $localName   = $resultData['localname'];
        $countryCode = $resultData['country_code'];

        $osmTypeShort = OsmTypeEnum::tryFrom($osmType);
        $osmTypeFull  = $this->mapOsmShortTypeToFull($osmTypeShort);

        $detailsResult = new DetailsResult(
            $osmTypeFull,
            $osmTypeShort,
            $osmId,
            $category,
            $type,
            $localName,
            $countryCode
        );

        return $detailsResult;
    }

}
