<?php

namespace GeoTool\Dto\DistanceTool;

use GeoTool\Dto\CoordinateTool\CoordinateDto;

/**
 * Represents the distance between w points
 */
class DistanceDto
{
    /**
     * @var string $startPoint
     */
    private string $startPoint;

    /**
     * @var string $endPoint
     */
    private string $endPoint;

    /**
     * @var float $distanceKm
     */
    private float $distanceKm;

    /**
     * @var CoordinateDto|null $startPointCoordinateDto
     */
    private ?CoordinateDto $startPointCoordinateDto = null;

    /**
     * @var CoordinateDto|null $endPointCoordinateDto
     */
    private ?CoordinateDto $endPointCoordinateDto = null;

    /**
     * @return string
     */
    public function getStartPoint(): string
    {
        return $this->startPoint;
    }

    /**
     * @param string|null $startPoint
     */
    public function setStartPoint(?string $startPoint): void
    {
        if (empty($startPoint)) {
            $this->startPoint = "";
            return;
        }

        $this->startPoint = $startPoint;
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * @param string|null $endPoint
     */
    public function setEndPoint(?string $endPoint): void
    {
        if (empty($endPoint)) {
            $this->endPoint = "";
            return;
        }

        $this->endPoint = $endPoint;
    }

    /**
     * @return float
     */
    public function getDistanceKm(): float
    {
        return $this->distanceKm;
    }

    /**
     * @param float $distanceKm
     */
    public function setDistanceKm(float $distanceKm): void
    {
        $this->distanceKm = $distanceKm;
    }

    /**
     * @return CoordinateDto|null
     */
    public function getStartPointCoordinateDto(): ?CoordinateDto
    {
        return $this->startPointCoordinateDto;
    }

    /**
     * @param CoordinateDto|null $startPointCoordinateDto
     */
    public function setStartPointCoordinateDto(?CoordinateDto $startPointCoordinateDto): void
    {
        $this->startPointCoordinateDto = $startPointCoordinateDto;
    }

    /**
     * @return CoordinateDto|null
     */
    public function getEndPointCoordinateDto(): ?CoordinateDto
    {
        return $this->endPointCoordinateDto;
    }

    /**
     * @param CoordinateDto|null $endPointCoordinateDto
     */
    public function setEndPointCoordinateDto(?CoordinateDto $endPointCoordinateDto): void
    {
        $this->endPointCoordinateDto = $endPointCoordinateDto;
    }

}