<?php

namespace GeoTool\Dto\Nominatim;

use GeoTool\Enum\Nominatim\OsmTypeEnum;
use GeoTool\Service\NominatimService\NominatimSearchService;

/**
 * Represents the data of the {@see NominatimSearchService::END_POINT}
 */
class SearchResult
{
    public function __construct(
        private int         $placeId,
        private OsmTypeEnum $osmType,
        private OsmTypeEnum $osmTypeShort,
        private int         $osmId,
        private float       $lattitude,
        private float       $longitude,
        private string      $category,
        private string      $type,
        private float       $importance
    ){}

    /**
     * @return int
     */
    public function getPlaceId(): int
    {
        return $this->placeId;
    }

    /**
     * @param int $placeId
     */
    public function setPlaceId(int $placeId): void
    {
        $this->placeId = $placeId;
    }

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
     * @return float
     */
    public function getLattitude(): float
    {
        return $this->lattitude;
    }

    /**
     * @param float $lattitude
     */
    public function setLattitude(float $lattitude): void
    {
        $this->lattitude = $lattitude;
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
     * @return float
     */
    public function getImportance(): float
    {
        return $this->importance;
    }

    /**
     * @param float $importance
     */
    public function setImportance(float $importance): void
    {
        $this->importance = $importance;
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

}