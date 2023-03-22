<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;

class CutoutValidatorCircleCutout extends CutoutValidator
{
    /**
     * Перевірити виріз
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCutout(): void
    {
        $this->verifyDetailSize();
        $this->verifyCutoutRadius();
        $this->verifyCutoutRetreat();
        $this->verifyCutoutEdge();
    }

    /**
     * Перевірити радіус виріза
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyCutoutRadius(): void
    {
        $minRadius = $this->config['min_radius'] ?? 0;
        $maxRadius = intval(max($this->detail->getLength(), $this->detail->getWidth()) / 4);

        if ($minRadius && $minRadius > $this->cutout->getRadius()) {
            throw new DspHandlerException(['min_radius', [$minRadius]]);
        }
        if ($maxRadius < $this->cutout->getRadius()) {
            throw new DspHandlerException(['max_radius', [$maxRadius]]);
        }
    }

    /**
     * Перевірити відступ виріза
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyCutoutRetreat(): void
    {
        $length = $this->detail->getLength();
        $width = $this->detail->getWidth();

        $minX = $this->config['min_retreat_x'] ?? 0;
        $minY = $this->config['min_retreat_y'] ?? 0;

        if ($length < $this->cutout->getX()) {
            throw new DspHandlerException(['outside']);
        }
        if ($width < $this->cutout->getY()) {
            throw new DspHandlerException(['outside']);
        }

        if ($minX && $minX > $this->cutout->getX()) {
            throw new DspHandlerException(['min_retreat_x', [$minX]]);
        }
        if ($minX && $minX > ($length - $this->cutout->getX())) {
            throw new DspHandlerException(['min_retreat_x', [$minX]]);
        }

        if ($minY && $minY > $this->cutout->getY()) {
            throw new DspHandlerException(['min_retreat_y', [$minY]]);
        }
        if ($minY && $minY > ($width - $this->cutout->getY())) {
            throw new DspHandlerException(['min_retreat_y', [$minY]]);
        }
    }
}