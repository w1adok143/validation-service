<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Corner;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeValidator;

class CornerValidatorRadius extends CornerValidator
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
        $this->verifyCornerRadius();
        $this->verifyCornerEdge();
    }

    /**
     * Перевірити радіус кута
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyCornerRadius(): void
    {
        $minRadius = $this->config['min_radius'] ?? 0;
        $maxRadius = min($this->detail->getLength(), $this->detail->getWidth());
        $maxRadius = max($maxRadius, $minRadius);

        if ($minRadius && $minRadius > $this->corner->getRadius()) {
            throw new DspHandlerException(['min_radius', [$minRadius]]);
        }
        if ($maxRadius < $this->corner->getRadius()) {
            throw new DspHandlerException(['max_radius', [$maxRadius]]);
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