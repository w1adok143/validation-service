<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Groove;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;

abstract class GrooveValidator
{
    public function __construct(
        protected Detail $detail,
        protected Groove $groove,
        protected array $config = []
    ) {}

    /**
     * Перевірити на дублікат
     *
     * @param int $current
     * @param Groove[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyGrooveDuplicate(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        unset($handlers[$current]);
        $handlers = array_filter($handlers, fn($groove) => $groove->getSide() === $this->groove->getSide());
        $handlers = array_filter($handlers, function($groove) {
            return $groove->getX() === $this->groove->getX()
                && $groove->getY() === $this->groove->getY()
                && $groove->getZ() === $this->groove->getZ();
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
     * Перевірити глибину паза
     *
     * @return void
     * @throws DspHandlerException
     */
    protected function verifyGrooveDepth(): void
    {
        $maxDepth = $this->config['max_depth'] ?? 0;
        $maxDepth = !empty($maxDepth) ? $maxDepth : $this->detail->getThickness() / 2 + 1;

        if ($maxDepth && $maxDepth < $this->groove->getDepth()) {
            throw new DspHandlerException(['max_depth', [$maxDepth]]);
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

        $minThickness = $this->config['min_thickness_detail'] ?? 0;
        $maxThickness = $this->config['max_thickness_detail'] ?? 0;

        if ($minLength && $minLength > $this->detail->getLength()) {
            throw new DspHandlerException(['detail__min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->detail->getWidth()) {
            throw new DspHandlerException(['detail__min_width', [$minWidth]]);
        }
        if ($minThickness && $minThickness > $this->detail->getThickness()) {
            throw new DspHandlerException(['detail__min_thickness', [$minThickness]]);
        }
        if ($maxThickness && $maxThickness < $this->detail->getThickness()) {
            throw new DspHandlerException(['detail__max_thickness', [$maxThickness]]);
        }
    }
}