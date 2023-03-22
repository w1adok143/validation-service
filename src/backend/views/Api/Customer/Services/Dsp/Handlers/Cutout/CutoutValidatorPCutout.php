<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeValidator;

class CutoutValidatorPCutout extends CutoutValidator
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
        $this->verifyCutoutRetreat();
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

        /** Виріз ліворуч */
        if ($this->cutout->isPCutoutLeft($width)) {
            $maxLength = $length - $minX;
            $maxLength = max($maxLength, $minLength);

            $maxWidth = $width - ($this->cutout->getY() + $minY);
            $maxWidth = max($maxWidth, $minWidth);

            if ($minLength && $minLength > $this->cutout->getLength()) {
                throw new DspHandlerException(['min_width', [$minLength]]);
            }
            if ($minX && $maxLength < $this->cutout->getLength()) {
                throw new DspHandlerException(['max_width', [$maxLength]]);
            }

            if ($minWidth && $minWidth > $this->cutout->getWidth()) {
                throw new DspHandlerException(['min_length', [$minWidth]]);
            }
            if ($minY && $maxWidth < $this->cutout->getWidth()) {
                throw new DspHandlerException(['max_length', [$maxWidth]]);
            }
        }

        /** Виріз зверху */
        if ($this->cutout->isPCutoutTop($length)) {
            $maxLength = $length - ($this->cutout->getX() + $minX);
            $maxLength = max($maxLength, $minLength);

            $maxWidth = $width - $minY;
            $maxWidth = max($maxWidth, $minWidth);

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

        /** Виріз праворуч */
        if ($this->cutout->isPCutoutRight($width)) {
            $maxLength = $length - $minX;
            $maxLength = max($maxLength, $minLength);

            $maxWidth = $width - ($this->cutout->getY() + $minY);
            $maxWidth = max($maxWidth, $minWidth);

            if ($minLength && $minLength > $this->cutout->getLength()) {
                throw new DspHandlerException(['min_width', [$minLength]]);
            }
            if ($minX && $maxLength < $this->cutout->getLength()) {
                throw new DspHandlerException(['max_width', [$maxLength]]);
            }

            if ($minWidth && $minWidth > $this->cutout->getWidth()) {
                throw new DspHandlerException(['min_length', [$minWidth]]);
            }
            if ($minY && $maxWidth < $this->cutout->getWidth()) {
                throw new DspHandlerException(['max_length', [$maxWidth]]);
            }
        }

        /** Виріз знизу */
        if ($this->cutout->isPCutoutBottom($length)) {
            $maxLength = $length - ($this->cutout->getX() + $minX);
            $maxLength = max($maxLength, $minLength);

            $maxWidth = $width - $minY;
            $maxWidth = max($maxWidth, $minWidth);

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

        /** Виріз ліворуч */
        if ($this->cutout->isPCutoutLeft($width)) {
            if ($minY && $minY > $this->cutout->getY()) {
                throw new DspHandlerException(['min_retreat', [$minY]]);
            }
            if ($minY && $minY > ($width - ($this->cutout->getY() + $this->cutout->getWidth()))) {
                throw new DspHandlerException(['min_retreat', [$minY]]);
            }
        }

        /** Виріз зверху */
        if ($this->cutout->isPCutoutTop($length)) {
            if ($minX && $minX > $this->cutout->getX()) {
                throw new DspHandlerException(['min_retreat', [$minX]]);
            }
            if ($minX && $minX > ($length - ($this->cutout->getX() + $this->cutout->getLength()))) {
                throw new DspHandlerException(['min_retreat', [$minX]]);
            }
        }

        /** Виріз праворуч */
        if ($this->cutout->isPCutoutRight($width)) {
            if ($minY && $minY > $this->cutout->getY()) {
                throw new DspHandlerException(['min_retreat', [$minY]]);
            }
            if ($minY && $minY > ($width - ($this->cutout->getY() + $this->cutout->getWidth()))) {
                throw new DspHandlerException(['min_retreat', [$minY]]);
            }
        }

        /** Виріз знизу */
        if ($this->cutout->isPCutoutBottom($length)) {
            if ($minX && $minX > $this->cutout->getX()) {
                throw new DspHandlerException(['min_retreat', [$minX]]);
            }
            if ($minX && $minX > ($length - ($this->cutout->getX() + $this->cutout->getLength()))) {
                throw new DspHandlerException(['min_retreat', [$minX]]);
            }
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