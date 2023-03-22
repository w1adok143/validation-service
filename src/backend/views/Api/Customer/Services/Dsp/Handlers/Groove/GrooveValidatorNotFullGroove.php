<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Groove;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;

class GrooveValidatorNotFullGroove extends GrooveValidator
{
    /**
     * Перевірити паз
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyGroove(): void
    {
        $this->verifyDetailSize();
        $this->verifyGrooveWidth();
        $this->verifyGrooveDepth();
        $this->verifyGrooveRetreat();
        $this->verifyGrooveRadius();
    }

    /**
     * Перевірити ширину, довжину паза
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyGrooveWidth(): void
    {
        $length = $this->detail->getLength($this->groove->getSide());
        $width = $this->detail->getWidth($this->groove->getSide());

        $minLength = $this->config['min_length'] ?? 0;
        $minWidth = $this->config['min_width'] ?? 0;

        /** Паз на площині */
        if ($this->detail->isArea($this->groove->getSide())) {
            if ($length < ($this->groove->getX() + $this->groove->getLength())) {
                throw new DspHandlerException(['outside']);
            }
            if ($width < ($this->groove->getY() + $this->groove->getWidth())) {
                throw new DspHandlerException(['outside']);
            }

            if ($minLength && $minLength > $this->groove->getLength()) {
                throw new DspHandlerException(['min_length', [$minLength]]);
            }
            if ($minWidth && $minWidth > $this->groove->getWidth()) {
                throw new DspHandlerException(['min_width', [$minWidth]]);
            }

            return;
        }
    }

    /**
     * Перевірити відступ паза
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyGrooveRetreat(): void
    {
        $length = $this->detail->getLength($this->groove->getSide());
        $width = $this->detail->getWidth($this->groove->getSide());

        $minX = $this->config['min_retreat_x'] ?? 0;
        $minY = $this->config['min_retreat_y'] ?? 0;
        $minZ = $this->config['min_retreat_z'] ?? 0;

        /** Паз на площині */
        if ($this->detail->isArea($this->groove->getSide())) {
            if ($length < $this->groove->getX()) {
                throw new DspHandlerException(['outside']);
            }
            if ($length < $this->groove->getY()) {
                throw new DspHandlerException(['outside']);
            }

            if ($minX && $minX > $this->groove->getX()) {
                throw new DspHandlerException(['min_retreat_x', [$minX]]);
            }
            if ($minX && $minX > ($length - ($this->groove->getX() + $this->groove->getLength()))) {
                throw new DspHandlerException(['min_retreat_x', [$minX]]);
            }

            if ($minY && $minY > $this->groove->getY()) {
                throw new DspHandlerException(['min_retreat_y', [$minY]]);
            }
            if ($minY && $minY > ($width - ($this->groove->getY() + $this->groove->getWidth()))) {
                throw new DspHandlerException(['min_retreat_y', [$minY]]);
            }

            return;
        }
    }

    /**
     * Перевірити радіус паза
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyGrooveRadius(): void
    {
        $minRadius = $this->config['min_radius'] ?? 0;
        $maxRadius = intval(min($this->groove->getLength(), $this->groove->getWidth()) / 2);
        $maxRadius = max($maxRadius, $minRadius);

        /** Паз на площині */
        if ($this->detail->isArea($this->groove->getSide())) {
            if ($minRadius && $minRadius > $this->groove->getRadius()) {
                throw new DspHandlerException(['min_radius', [$minRadius]]);
            }
            if ($maxRadius < $this->groove->getRadius()) {
                throw new DspHandlerException(['max_radius', [$maxRadius]]);
            }
        }
    }
}