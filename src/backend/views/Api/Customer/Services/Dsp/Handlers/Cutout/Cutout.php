<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\Edge;

class Cutout
{
    public function __construct(
        private string $side,
        private float $x,
        private float $y,
        private float $length,
        private float $width,
        private float $depth,
        private float $radius,
        private ?Edge $edge = null,
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

    public function getEdge(): ?Edge
    {
        return $this->edge;
    }

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }

    public function getSubside(): ?string
    {
        return $this->subside;
    }

    /**
     * Прямокутний виріз
     *
     * @return bool
     */
    public function isWindowCutout(): bool
    {
        return $this->subtype === 'rect';
    }

    /**
     * Круговий виріз
     *
     * @return bool
     */
    public function isCircleCutout(): bool
    {
        return $this->subtype === 'circle';
    }

    /**
     * П - подібний виріз
     *
     * @param int $length
     * @param int $width
     * @return bool
     */
    public function isPCutout(int $length, int $width): bool
    {
        if ($this->isPCutoutLeft($width)) {
            return true;
        }
        if ($this->isPCutoutTop($length)) {
            return true;
        }
        if ($this->isPCutoutRight($width)) {
            return true;
        }
        if ($this->isPCutoutBottom($length)) {
            return true;
        }

        return false;
    }

    /**
     * П - подібний виріз (ліворуч)
     *
     * @param float $width
     * @return bool
     */
    public function isPCutoutLeft(float $width): bool
    {
        return $this->subtype === 'ushape'
            && $this->subside === 'left'
            && $this->y > 0
            && ($this->y + $this->width) < $width;
    }

    /**
     * П - подібний виріз (зверху)
     *
     * @param float $length
     * @return bool
     */
    public function isPCutoutTop(float $length): bool
    {
        return $this->subtype === 'ushape'
            && $this->subside === 'top'
            && $this->x > 0
            && ($this->x + $this->length) < $length;
    }

    /**
     * П - подібний виріз (праворуч)
     *
     * @param float $width
     * @return bool
     */
    public function isPCutoutRight(float $width): bool
    {
        return $this->subtype === 'ushape'
            && $this->subside === 'right'
            && $this->y > 0
            && ($this->y + $this->width) < $width;
    }

    /**
     * П - подібний виріз (знизу)
     *
     * @param float $length
     * @return bool
     */
    public function isPCutoutBottom(float $length): bool
    {
        return $this->subtype === 'ushape'
            && $this->subside === 'bottom'
            && $this->x > 0
            && ($this->x + $this->length) < $length;
    }

    /**
     * Г - подібний виріз
     *
     * @param float $length
     * @param float $width
     * @return bool
     */
    public function isGCutout(float $length, float $width): bool
    {
        if ($this->isGCutoutLeft($width)) {
            return true;
        }
        if ($this->isGCutoutTop($length)) {
            return true;
        }
        if ($this->isGCutoutRight($width)) {
            return true;
        }
        if ($this->isGCutoutBottom($length)) {
            return true;
        }

        return false;
    }

    /**
     * Г - подібний виріз (ліворуч)
     *
     * @param float $width
     * @return bool
     */
    public function isGCutoutLeft(float $width): bool
    {
        if ($this->subtype !== 'ushape') {
            return false;
        }
        if ($this->subside !== 'left') {
            return false;
        }
        if ($this->y == 0) {
            return true;
        }
        if ($this->y > 0 && ($this->y + $this->width) >= $width) {
            return true;
        }

        return false;
    }

    /**
     * Г - подібний виріз (зверху)
     *
     * @param float $length
     * @return bool
     */
    public function isGCutoutTop(float $length): bool
    {
        if ($this->subtype !== 'ushape') {
            return false;
        }
        if ($this->subside !== 'top') {
            return false;
        }
        if ($this->x == 0) {
            return true;
        }
        if ($this->x > 0 && ($this->x + $this->length) >= $length) {
            return true;
        }

        return false;
    }

    /**
     * Г - подібний виріз (праворуч)
     *
     * @param float $width
     * @return bool
     */
    public function isGCutoutRight(float $width): bool
    {
        if ($this->subtype !== 'ushape') {
            return false;
        }
        if ($this->subside !== 'right') {
            return false;
        }
        if ($this->y == 0) {
            return true;
        }
        if ($this->y > 0 && ($this->y + $this->width) >= $width) {
            return true;
        }

        return false;
    }

    /**
     * Г - подібний виріз (знизу)
     *
     * @param float $length
     * @return bool
     */
    public function isGCutoutBottom(float $length): bool
    {
        if ($this->subtype !== 'ushape') {
            return false;
        }
        if ($this->subside !== 'bottom') {
            return false;
        }
        if ($this->x == 0) {
            return true;
        }
        if ($this->x > 0 && ($this->x + $this->length) >= $length) {
            return true;
        }

        return false;
    }
}