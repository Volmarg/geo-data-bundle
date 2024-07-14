<?php

namespace GeoTool\Dto\Internal;

/**
 * Contains geo tool based api credentials
 */
class GeoToolApiCredentials extends ApiCredentials implements GeoToolApiCredentialsInterface
{
    /**
     * Defines how many entries found for given query should be returned
     * There is no need to return to many of entries as:
     * - some might be very similar,
     * - the one with biggest `confidence` are returned first,
     * - multiple entries are just to confirm that location data does not vary too much and can be fetched at all,
     */
    private const FETCHED_ENTRIES_LIMIT = 5;

    private const GET_CITY_LOCATION_DATA_ENDPOINT_URI = "/v1/forward";

    private const KEY_API_KEY   = "access_key";
    private const KEY_CITY_NAME = "query"; //could be probably used for any string but there is no need for this now
    private const KEY_LIMIT     = "limit";

    /**
     * {@inheritDoc}
     */
    public function getCityLocationDataEndpoint(): string
    {
        $queryString = http_build_query([
            self::KEY_API_KEY   => $this->getPassword(),
            self::KEY_LIMIT     => self::FETCHED_ENTRIES_LIMIT,
            self::KEY_CITY_NAME => "", // city name wil be applied in service
        ]);

        return $this->getDomain() . self::GET_CITY_LOCATION_DATA_ENDPOINT_URI . "?" . $queryString;
    }
}