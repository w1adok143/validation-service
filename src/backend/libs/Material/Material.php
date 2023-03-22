<?php

namespace Kronas\Lib\Material;

class Material
{
    public function __construct(
        private float $thickness
    ) {}

    public function getThickness(): float
    {
        return $this->thickness;
    }
}