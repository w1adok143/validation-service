<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Corner;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeValidator;

class CornerValidatorLine extends CornerValidator
{
    /**
     * Перевірити кут
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCorner(): void
    {
        $this->verifyDetailSize();
        $this->verifyCornerRetreat();
        $this->verifyCornerEdge();
    }

    /**
     * Перевірити відступ кута
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyCornerRetreat(): void
    {
        $length = $this->detail->getLength();
        $width = $this->detail->getWidth();

        $minX = $this->config['min_retreat_x'] ?? 0;
        $maxX = $length;

        $minY = $this->config['min_retreat_y'] ?? 0;
        $maxY = $width;

        if ($length < $this->corner->getX()) {
            throw new DspHandlerException(['outside']);
        }
        if ($width < $this->corner->getY()) {
            throw new DspHandlerException(['outside']);
        }

        if ($minX && $minX > $this->corner->getX()) {
            throw new DspHandlerException(['min_x', [$minX]]);
        }
        if ($minX && $maxX < $this->corner->getX()) {
            throw new DspHandlerException(['max_x', [$maxX]]);
        }

        if ($minY && $minY > $this->corner->getY()) {
            throw new DspHandlerException(['min_y', [$minY]]);
        }
        if ($minY && $maxY < $this->corner->getY()) {
            throw new DspHandlerException(['max_y', [$maxY]]);
        }
    }

    /**
     * Перевірити кромкування кута
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyCornerEdge(): void
    {
        $edge = $this->corner->getEdge();

        if (empty($edge)) {
            return;
        }

        $validatorEdge = new EdgeValidator($this->detail, $edge, $this->configEdge);
        $validatorEdge->verifyDetailSize();
        $validatorEdge->verifyEdgeWidth();
    }
}