<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Quarter;

class Quarter
{
    public function __construct(
        private string $side,
        private float $x,
        private float $y,
        private float $length,
        private float $width,
        private float $depth,
        private float $radius,
        private bool $isFull = false, // на всю довжину
        private ?string $subtype = null, // назва операції зі сторони фронта
        private ?string $subside = null // торець для сторони
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

    public function getLength(): float
    {
        return $this->length;
    }

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getDepth(): float
    {
        return $this->depth;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }

    public function getIsFull(): bool
    {
        return $this->isFull;
    }

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }

    public function getSubside(): ?string
    {
        return $this->subside;
    }
}