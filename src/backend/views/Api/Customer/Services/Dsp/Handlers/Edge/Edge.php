<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Edge;

class Edge
{
    public function __construct(
        private float $width,
        private float $thickness
    ) {}

    public function getWidth(): float
    {
        return $this->width;
    }

    public function getThickness(): float
    {
        return $this->thickness;
    }
}