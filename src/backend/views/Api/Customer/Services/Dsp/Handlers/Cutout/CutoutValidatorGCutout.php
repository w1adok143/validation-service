<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeValidator;

class CutoutValidatorGCutout extends CutoutValidator
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
        $this->verifyCutoutWidth();
        $this->verifyCutoutRadius();
        $this->verifyCutoutEdge();
    }

    /**
     * Перевірити ширину, довжину виріза
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyCutoutWidth(): void
    {
        $length = $this->detail->getLength();
        $width = $this->detail->getWidth();

        $minX = $this->config['min_retreat_x'] ?? 0;
        $minY = $this->config['min_retreat_y'] ?? 0;

        $minLength = $this->config['min_length'] ?? 0;
        $minWidth = $this->config['min_width'] ?? 0;

        $maxLength = $length - $minX;
        $maxLength = max($maxLength, $minLength);

        $maxWidth = $width - $minY;
        $maxWidth = max($maxWidth, $minWidth);

        if ($length < ($this->cutout->getX() + $this->cutout->getLength())) {
            throw new DspHandlerException(['outside']);
        }
        if ($width < ($this->cutout->getY() + $this->cutout->getWidth())) {
            throw new DspHandlerException(['outside']);
        }

        if ($minLength && $minLength > $this->cutout->getLength()) {
            throw new DspHandlerException(['min_length', [$minLength]]);
        }
        if ($minX && $maxLength < $this->cutout->getLength()) {
            throw new DspHandlerException(['max_length', [$maxLength]]);
        }

        if ($minWidth && $minWidth > $this->cutout->getWidth()) {
            throw new DspHandlerException(['min_width', [$minWidth]]);
        }
        if ($minY && $maxWidth < $this->cutout->getWidth()) {
            throw new DspHandlerException(['max_width', [$maxWidth]]);
        }
    }

    /**
     * Перевірити кромкування виріза
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyCutoutEdge(): void
    {
        $edge = $this->cutout->getEdge();

        if (empty($edge)) {
            return;
        }

        $validatorEdge = new EdgeValidator($this->detail, $edge, $this->configEdge);
        $validatorEdge->verifyDetailSize();
        $validatorEdge->verifyEdgeWidth();
    }
}