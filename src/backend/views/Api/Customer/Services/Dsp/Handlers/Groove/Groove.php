<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Groove;

class Groove
{
    public function __construct(
        private string $side,
        private string $direction, // направлення
        private float $x,
        private float $y,
        private float $z,
        private float $length,
        private float $width,
        private float $depth,
        private float $radius,
        private bool $isFull = false, // на всю довжину
        private ?string $subtype = null // назва операції зі сторони фронта
    ) {}

    public function getSide(): string
    {
        return $this->side;
    }

    public function getDirection(): string
    {
        return $this->direction;
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

    public function getSubtype(): ?string
    {
        return $this->subtype;
    }

    /**
     * Паз на всю довжину
     *
     * @return bool
     */
    public function isFull(): bool
    {
        if ($this->isFullHorizontal()) {
            return true;
        }
        if ($this->isFullVertical()) {
            return true;
        }

        return false;
    }

    /**
     * Паз на всю довжину по горизонталі
     *
     * @return bool
     */
    public function isFullHorizontal(): bool
    {
        return $this->isFull && $this->direction === 'horizontal';
    }

    /**
     * Паз на всю довжину по вертикалі
     *
     * @return bool
     */
    public function isFullVertical(): bool
    {
        return $this->isFull && $this->direction === 'vertical';
    }

    /**
     * Паз не на всю довжину
     *
     * @return bool
     */
    public function isNotFull(): bool
    {
        if ($this->isNotFullHorizontal()) {
            return true;
        }
        if ($this->isNotFullVertical()) {
            return true;
        }

        return false;
    }

    /**
     * Паз не на всю довжину по горизонталі
     *
     * @return bool
     */
    public function isNotFullHorizontal(): bool
    {
        return !$this->isFull && $this->direction === 'horizontal';
    }

    /**
     * Паз не на всю довжину по вертикалі
     *
     * @return bool
     */
    public function isNotFullVertical(): bool
    {
        return !$this->isFull && $this->direction === 'vertical';
    }
}