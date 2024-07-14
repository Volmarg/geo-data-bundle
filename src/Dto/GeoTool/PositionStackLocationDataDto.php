<?php

namespace GeoTool\Dto\GeoTool;

/**
 * Represents set of data returned by api:
 * There are more keys in response but only few are used in the project
 *
 * @link https://positionstack.com/documentation
 */
class PositionStackLocationDataDto
{
    /**
     * @var float|null $latitude
     */
    private ?float $latitude = null;

    /**
     * @var float|null $longitude
     */
    private ?float $longitude = null;

    /**
     * @var string|null $type
     */
    private ?string $type = null;

    /**
     * @var string|null $name
     */
    private ?string $name = null;

    /**
     * @var string|null $number
     */
    private ?string $number = null;

    /**
     * @var string|null $postal_code
     */
    private ?string $postal_code = null;

    /**
     * @var string|null $street
     */
    private ?string $street = null;

    /**
     * @var float|null $confidence
     */
    private ?float $confidence = null;

    /**
     * @var string|null $region
     */
    private ?string $region = null;

    /**
     * @var string|null $regionCode
     */
    private ?string $region_code = null;

    /**
     * @var string|null $county
     */
    private ?string $county = null;

    /**
     * @var string|null $locality
     */
    private ?string $locality = null;

    /**
     * @var string|null $administrativeArea
     */
    private ?string $administrative_area;

    /**
     * @var string|null $neighbourhood
     */
    private ?string $neighbourhood;

    /**
     * @var string|null $country
     */
    private ?string $country;

    /**
     * @var string|null $countryCode
     */
    private ?string $country_code;

    /**
     * @var string|null $continent
     */
    private ?string $continent;

    /**
     * @var string|null $label
     */
    private ?string $label;

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    /**
     * @param float|null $latitude
     */
    public function setLatitude(?float $latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    /**
     * @param float|null $longitude
     */
    public function setLongitude(?float $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     */
    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @param string|null $number
     */
    public function setNumber(?string $number): void
    {
        $this->number = $number;
    }

    /**
     * @return string|null
     */
    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    /**
     * @param string|null $postal_code
     */
    public function setPostalCode(?string $postal_code): void
    {
        $this->postal_code = $postal_code;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return float|null
     */
    public function getConfidence(): ?float
    {
        return $this->confidence;
    }

    /**
     * @param float|null $confidence
     */
    public function setConfidence(?float $confidence): void
    {
        $this->confidence = $confidence;
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
        return $this->region_code;
    }

    /**
     * @param string|null $region_code
     */
    public function setRegionCode(?string $region_code): void
    {
        $this->region_code = $region_code;
    }

    /**
     * @return string|null
     */
    public function getCounty(): ?string
    {
        return $this->county;
    }

    /**
     * @param string|null $county
     */
    public function setCounty(?string $county): void
    {
        $this->county = $county;
    }

    /**
     * @return string|null
     */
    public function getLocality(): ?string
    {
        return $this->locality;
    }

    /**
     * @param string|null $locality
     */
    public function setLocality(?string $locality): void
    {
        $this->locality = $locality;
    }

    /**
     * @return string|null
     */
    public function getAdministrativeArea(): ?string
    {
        return $this->administrative_area;
    }

    /**
     * @param string|null $administrative_area
     */
    public function setAdministrativeArea(?string $administrative_area): void
    {
        $this->administrative_area = $administrative_area;
    }

    /**
     * @return string|null
     */
    public function getNeighbourhood(): ?string
    {
        return $this->neighbourhood;
    }

    /**
     * @param string|null $neighbourhood
     */
    public function setNeighbourhood(?string $neighbourhood): void
    {
        $this->neighbourhood = $neighbourhood;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->country_code;
    }

    /**
     * @param string|null $country_code
     */
    public function setCountryCode(?string $country_code): void
    {
        $this->country_code = $country_code;
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

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

}