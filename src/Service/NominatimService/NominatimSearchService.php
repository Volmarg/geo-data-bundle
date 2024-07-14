<?php

namespace GeoTool\Service\NominatimService;

use Exception;
use GeoTool\Dto\Nominatim\SearchResult;
use GeoTool\Enum\Nominatim\OsmTypeEnum;
use GeoTool\Service\Env\EnvReaderService;
use GeoTool\Service\Request\GuzzleServiceInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Handles the: {@link https://nominatim.org/release-docs/develop/api/Search/}
 */
class NominatimSearchService extends NominatimService
{
    private const END_POINT   = "search";
    private const PARAM_QUERY = "q";

    /**
     * Nominatim returns array of results, because there can be few locations that matches,
     * The importance is the internal Nominatim rank of how likely is that given result is what user looks for
     * - how likely is that given result matches provided query
     *
     * @param string $searchedQuery
     * @return SearchResult|null
     * @throws GuzzleException
     */
    public function getHighestImportanceResult(string $searchedQuery): ?SearchResult
    {
        try {
            $responseArray          = $this->getResponse($searchedQuery);
            $highestImportanceData  = null;
            $highestImportanceValue = null;

            foreach ($responseArray as $responseDataChunk) {
                $importance = $responseDataChunk['importance'] ?? null;
                if (empty($highestImportanceValue)) {
                    $highestImportanceValue = $importance;
                    $highestImportanceData  = $responseDataChunk;
                    continue;
                }

                if ($highestImportanceValue < $importance) {
                    $highestImportanceValue = $importance;
                    $highestImportanceData  = $responseDataChunk;
                }
            }

            if (empty($highestImportanceData)) {
                return null;
            }

            $searchResult = $this->buildSearchResult($highestImportanceData);

            return $searchResult;
        }catch(Exception | \TypeError $e){
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
     * @param string $searchedQuery
     * @return array|null
     *
     * @throws GuzzleException
     */
    private function getResponse(string $searchedQuery): ?array
    {
        try {

            $calledUrl = EnvReaderService::getNominatimBaseUrl() . self::END_POINT;
            $options   = [
                GuzzleServiceInterface::KEY_QUERY => $this->buildQueryParams($searchedQuery),
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
        }catch(Exception|\TypeError $e) {
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
     * @param string $query
     * @return array
     */
    private function buildQueryParams(string $query): array
    {
        return [
            self::PARAM_QUERY => $query,
        ];
    }

    /**
     * @param array $highestImportanceData
     *
     * @return SearchResult
     */
    private function buildSearchResult(array $highestImportanceData): SearchResult
    {
        $placeId    = $highestImportanceData['place_id'];
        $osmType    = $highestImportanceData['osm_type'];
        $osmId      = $highestImportanceData['osm_id'];
        $lattitude  = $highestImportanceData['lat'];
        $longitude  = $highestImportanceData['lon'];
        $category   = $highestImportanceData['category'];
        $type       = $highestImportanceData['type'];
        $importance = $highestImportanceData['importance'];

        $osmTypeEnum  = OsmTypeEnum::tryFrom($osmType);
        $osmTypeShort = $this->mapOsmTypeToShort($osmTypeEnum);

        $searchResult = new SearchResult(
            $placeId,
            $osmTypeEnum,
            $osmTypeShort,
            $osmId,
            $lattitude,
            $longitude,
            $category,
            $type,
            $importance,
        );

        return $searchResult;
    }

}
