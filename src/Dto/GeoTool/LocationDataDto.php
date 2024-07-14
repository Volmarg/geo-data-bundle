<?php

namespace GeoTool\Dto\GeoTool;

class LocationDataDto
{
    /**
     * @var int|null $id
     */
    private ?int $id;

    /**
     * @var float $latitude
     */
    private float $latitude;

    /**
     * @var float $longitude
     */
    private float $longitude;

    /**
     * @var string|null $region
     */
    private ?string $region;

    /**
     * @var string|null $regionCode
     */
    private ?string $regionCode;

    /**
     * @var string|null $locationName
     */
    private ?string $locationName;

    /**
     * @var string|null $nativeLanguageCityName
     */
    private ?string $nativeLanguageCityName;

    /**
     * @var string $country
     */
    private string $country;

    /**
     * @var string $countryCode
     */
    private string $countryCode;

    /**
     * @var string|null $continent
     */
    private ?string  $continent;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     */
    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string|null
     */
    public function getRegion(): ?string
    {
        return $this->region;
    }

    /**
     * @param string|null $region
     */
    public function setRegion(?string $region): void
    {
        $this->region = $region;
    }

    /**
     * @return string|null
     */
    public function getRegionCode(): ?string
    {
        return $this->regionCode;
    }

    /**
     * @param string|null $regionCode
     */
    public function setRegionCode(?string $regionCode): void
    {
        $this->regionCode = $regionCode;
    }

    /**
     * @return string|null
     */
    public function getLocationName(): ?string
    {
        return $this->locationName;
    }

    /**
     * @param string|null $locationName
     */
    public function setLocationName(?string $locationName): void
    {
        $this->locationName = $locationName;
    }

    /**
     * @return string|null
     */
    public function getNativeLanguageCityName(): ?string
    {
        return $this->nativeLanguageCityName;
    }

    /**
     * @param string|null $nativeLanguageCityName
     */
    public function setNativeLanguageCityName(?string $nativeLanguageCityName): void
    {
        $this->nativeLanguageCityName = $nativeLanguageCityName;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string|null
     */
    public function getContinent(): ?string
    {
        return $this->continent;
    }

    /**
     * @param string|null $continent
     */
    public function setContinent(?string $continent): void
    {
        $this->continent = $continent;
    }

}
