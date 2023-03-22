<?php

namespace Kronas\Lib\Detail;

use Kronas\Lib\Material\Material;

class Detail 
{
    public function __construct(
        private Material $material,
        private float $length,
        private float $width
    ) {}

    public function getMaterial(): Material
    {
        return $this->material;
    }

    public function getLength(string $side = null): float
    {
        return in_array($side, ['left', 'right']) ? $this->width : $this->length;
    }

    public function getWidth(string $side = null): float
    {
        return in_array($side, ['left', 'top', 'right', 'bottom']) ? $this->getThickness() : $this->width;
    }

    public function getThickness(): float
    {
        return $this->material->getThickness();
    }

    /**
     * Перевірити чи це глухий отвір
     *
     * @param string $side
     * @param float $depth
     * @return bool
     */
    public function isDeaf(string $side, float $depth): bool
    {
        return in_array($side, ['front', 'back']) && $depth < $this->material->getThickness();
    }

    /**
     * Перевірити чи це наскрізний отвір
     *
     * @param string $side
     * @param float $depth
     * @return bool
     */
    public function isThrough(string $side, float $depth): bool
    {
        return in_array($side, ['front', 'back']) && $depth >= $this->material->getThickness();
    }

    /**
     * Перевірити чи це торцева сторона
     *
     * @param string $side
     * @return bool
     */
    public function isEnd(string $side): bool
    {
        return in_array($side, ['left', 'top', 'right', 'bottom']);
    }

    /**
     * Перевірити чи це передня або задня сторона
     *
     * @param string $side
     * @return bool
     */
    public function isArea(string $side): bool
    {
        return in_array($side, ['front', 'back']);
    }
}