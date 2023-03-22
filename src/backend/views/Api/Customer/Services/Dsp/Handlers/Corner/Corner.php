<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Corner;

use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\Edge;

class Corner
{
    public function __construct(
        private string $angle,
        private float $x,
        private float $y,
        private string $type,
        private float $radius,
        private ?Edge $edge = null,
        private ?string $subtype = null // назва операції зі сторони фронта
    ) {}

    public function getAngle(): string
    {
        return $this->angle;
    }

    public function getX(): float
    {
        return $this->x;
    }

    public function getY(): float
    {
        return $this->y;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRadius(): float
    {
        return $this->radius;
    }

    public function getEdge(): ?Edge
    {
        return $this->edge;
    }

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }
}