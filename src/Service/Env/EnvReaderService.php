<?php

namespace GeoTool\Service\Env;

/**
 * .env reading related service
 */
class EnvReaderService
{
    private const VAR_NOMINATIM_AUTH_USERNAME         = "NOMINATIM_AUTH_USERNAME";
    private const VAR_NOMINATIM_AUTH_PASSWORD         = "NOMINATIM_AUTH_PASSWORD";
    private const VAR_NOMINATIM_SERVICE_TOOL_BASE_URL = "NOMINATIM_SERVICE_TOOL_BASE_URL";

    private const VAR_OPEN_ROUTE_SERVICE_AUTH_USERNAME         = "OPEN_ROUTE_SERVICE_TOOL_USERNAME";
    private const VAR_OPEN_ROUTE_SERVICE_AUTH_PASSWORD         = "OPEN_ROUTE_SERVICE_TOOL_PASSWORD";
    private const VAR_OPEN_ROUTE_SERVICE_SERVICE_TOOL_BASE_URL = "OPEN_ROUTE_SERVICE_TOOL_BASE_URL";

    /**
     * Return the base url that will be used to create absolute paths for calls toward nominatim
     *
     * @return string
     */
    public static function getNominatimBaseUrl(): string
    {
        return $_ENV[self::VAR_NOMINATIM_SERVICE_TOOL_BASE_URL];
    }

    /**
     * Return Nominatim service username
     *
     * @return string
     */
    public static function getNominatimUsername(): string
    {
        return $_ENV[self::VAR_NOMINATIM_AUTH_USERNAME];
    }

    /**
     * Return Nominatim service password
     *
     * @return string
     */
    public static function getNominatimPassword(): string
    {
        return $_ENV[self::VAR_NOMINATIM_AUTH_PASSWORD];
    }

    /**
     * Return the base url that will be used to create absolute paths for calls toward open route service
     *
     * @return string
     */
    public static function getOpenRouteServiceBaseUrl(): string
    {
        return $_ENV[self::VAR_OPEN_ROUTE_SERVICE_SERVICE_TOOL_BASE_URL];
    }

    /**
     * Return Open route service username
     *
     * @return string
     */
    public static function getOpenRouteServiceUsername(): string
    {
        return $_ENV[self::VAR_OPEN_ROUTE_SERVICE_AUTH_USERNAME];
    }

    /**
     * Return open route service password
     *
     * @return string
     */
    public static function getOpenRouteServicePassword(): string
    {
        return $_ENV[self::VAR_OPEN_ROUTE_SERVICE_AUTH_PASSWORD];
    }


}