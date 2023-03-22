<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Hole;

class Hole
{
    public function __construct(
        private string $side,
        private float $x,
        private float $y,
        private float $z,
        private float $depth,
        private float $diam,
        private ?string $subtype = null // назва операції зі сторони фронта
    ) {}

    public function getSide(): string
    {
        return $this->side;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getZ(): float
    {
        return $this->z;
    }

    public function getDepth(): float
    {
        return $this->depth;
    }

    public function getDiam(): float
    {
        return $this->diam;
    }

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }
}