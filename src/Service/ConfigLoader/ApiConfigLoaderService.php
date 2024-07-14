<?php

namespace GeoTool\Service\ConfigLoader;

use GeoTool\Dto\Internal\GeoToolApiCredentials;

/**
 * Contains api related configurations
 */
class ApiConfigLoaderService
{
    /**
     * @var GeoToolApiCredentials $positionStackApiCredential
     */
    private GeoToolApiCredentials $positionStackApiCredential;

    /**
     * @return GeoToolApiCredentials
     */
    public function getPositionStackApiCredential(): GeoToolApiCredentials
    {
        return $this->positionStackApiCredential;
    }

    /**
     * @param string $login
     * @param string $password
     * @param string $domain
     */
    public function setPositionStackApiCredential(string $login, string $password, string $domain): void
    {
        $this->positionStackApiCredential = new GeoToolApiCredentials($login, $password, $domain);
    }

}