<?php

namespace GeoTool\Dto\Nominatim;

use GeoTool\Enum\Nominatim\OsmTypeEnum;

class DetailsResult
{
    public function __construct(
        private OsmTypeEnum $osmType,
        private OsmTypeEnum $osmTypeShort,
        private int         $osmId,
        private string      $category,
        private string      $type,
        private string      $localName,
        private string      $countryCode,
    ){}

    /**
     * @return OsmTypeEnum
     */
    public function getOsmType(): OsmTypeEnum
    {
        return $this->osmType;
    }

    /**
     * @param OsmTypeEnum $osmType
     */
    public function setOsmType(OsmTypeEnum $osmType): void
    {
        $this->osmType = $osmType;
    }

    /**
     * @return OsmTypeEnum
     */
    public function getOsmTypeShort(): OsmTypeEnum
    {
        return $this->osmTypeShort;
    }

    /**
     * @param OsmTypeEnum $osmTypeShort
     */
    public function setOsmTypeShort(OsmTypeEnum $osmTypeShort): void
    {
        $this->osmTypeShort = $osmTypeShort;
    }

    /**
     * @return int
     */
    public function getOsmId(): int
    {
        return $this->osmId;
    }

    /**
     * @param int $osmId
     */
    public function setOsmId(int $osmId): void
    {
        $this->osmId = $osmId;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLocalName(): string
    {
        return $this->localName;
    }

    /**
     * @param string $localName
     */
    public function setLocalName(string $localName): void
    {
        $this->localName = $localName;
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

}