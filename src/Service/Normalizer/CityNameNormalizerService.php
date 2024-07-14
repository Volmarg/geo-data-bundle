<?php

namespace GeoTool\Service\Normalizer;

/**
 * Handles normalization of the `city name` used for searching geolocation data
 * This is required as some services might provide invalid city names such as:
 * - "64401 Groß-Bieberau"
 *
 * The "invalid" state really depends:
 * - VALID: some API uses this kind of addresses for more accurate search,
 * - INVALID: some service (job search handler) stores location name such as "64401 Groß-Bieberau", yet current project
 *            stores the "REAL VALID" location data, so it will save the "Groß-Bieberau" as location name
 *
 * With that the normalizer should be used with caution
 */
class CityNameNormalizerService
{
    public function normalize(string $cityName): string
    {
        $this->removeZipCode($cityName);

        return $cityName;
    }

    /**
     * Regexp in here is a general regexp to catch something that might be ZIP code.
     * Zip codes around the world are matching various of regexps:
     * - {@link https://github.com/unicode-org/cldr/blob/release-26-0-1/common/supplemental/postalCodeData.xml}
     *
     * @param $cityName
     *
     * @return string
     */
    private function removeZipCode(&$cityName): string
    {
        $zipCodeRegexp = "";
        $cityName = preg_replace($zipCodeRegexp, "", $cityName);
    }
}