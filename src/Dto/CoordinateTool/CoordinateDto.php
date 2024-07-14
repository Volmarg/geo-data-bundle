<?php

namespace GeoTool\Dto\CoordinateTool;

class CoordinateDto
{
    /**
     * @var int|null $relatedEntityId
     */
    private ?int $relatedEntityId = null;

    /**
     * @var string|null $locationName
     */
    private ?string $locationName;

    /**
     * @var float|null $longitude
     */
    private ?float $longitude;

    /**
     * @var float|null $latitude
     */
    private ?float $latitude;

    /**
     * @return int|null
     */
    public function getRelatedEntityId(): ?int
    {
        return $this->relatedEntityId;
    }

    /**
     * @param int|null $relatedEntityId
     */
    public function setRelatedEntityId(?int $relatedEntityId): void
    {
        $this->relatedEntityId = $relatedEntityId;
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

}
