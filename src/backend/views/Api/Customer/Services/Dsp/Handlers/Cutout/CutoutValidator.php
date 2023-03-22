<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;

abstract class CutoutValidator
{
    public function __construct(
        protected Detail $detail,
        protected Cutout $cutout,
        protected array $config = [],
        protected array $configEdge = []
    ) {}

    /**
     * Перевірити виріз на дублікат
     *
     * @param int $current
     * @param Cutout[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCutoutDuplicate(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        unset($handlers[$current]);
        $handlers = array_filter($handlers, fn($cutout) => $cutout->getSide() === $this->cutout->getSide());
        $handlers = array_filter($handlers, function($cutout) {
            return $cutout->getX() === $this->cutout->getX()
                && $cutout->getY() === $this->cutout->getY();
        });

        try {
            if (!empty($handlers)) {
                throw new DspHandlerException(['duplicate']);
            }
        } catch (DspHandlerException $e) {
            $e->setErrorOperationIndex(array_merge([$current], array_keys($handlers)));

            throw $e;
        }
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

        $minLength = $this->config['min_length'] ?? 0;
        $minWidth = $this->config['min_width'] ?? 0;

        if ($length < ($this->cutout->getX() + $this->cutout->getLength())) {
            throw new DspHandlerException(['outside']);
        }
        if ($width < ($this->cutout->getY() + $this->cutout->getWidth())) {
            throw new DspHandlerException(['outside']);
        }

        if ($minLength && $minLength > $this->cutout->getLength()) {
            throw new DspHandlerException(['min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->cutout->getWidth()) {
            throw new DspHandlerException(['min_width', [$minWidth]]);
        }
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
        $maxRadius = intval(min($this->cutout->getLength(), $this->cutout->getWidth()) / 2);
        $maxRadius = max($maxRadius, $minRadius);

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
        if ($minX && $minX > ($length - ($this->cutout->getX() + $this->cutout->getLength()))) {
            throw new DspHandlerException(['min_retreat_x', [$minX]]);
        }

        if ($minY && $minY > $this->cutout->getY()) {
            throw new DspHandlerException(['min_retreat_y', [$minY]]);
        }
        if ($minY && $minY > ($width - ($this->cutout->getY() + $this->cutout->getWidth()))) {
            throw new DspHandlerException(['min_retreat_y', [$minY]]);
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

        if (!empty($edge)) {
            throw new DspHandlerException(['edge__forbidden']);
        }
    }

    /**
     * Перевірити розмір деталі
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyDetailSize(): void
    {
        $minLength = $this->config['min_length_detail'] ?? 0;
        $minWidth = $this->config['min_width_detail'] ?? 0;

        if ($minLength && $minLength > $this->detail->getLength()) {
            throw new DspHandlerException(['detail__min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->detail->getWidth()) {
            throw new DspHandlerException(['detail__min_width', [$minWidth]]);
        }
    }
}