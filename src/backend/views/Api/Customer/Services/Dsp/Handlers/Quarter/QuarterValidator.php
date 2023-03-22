<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Quarter;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;

class QuarterValidator
{
    public function __construct(
        private Detail $detail,
        private Quarter $quarter,
        private array $config = []
    ) {}

    /**
     * Перевірити чверть
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyQuarter(): void
    {
        $this->verifyDetailSize();
        $this->verifyQuarterWidth();
        $this->verifyQuarterDepth();
    }

    /**
     * Перевірити чверть на дублікат
     *
     * @param int $current
     * @param Quarter[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyQuarterDuplicate(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        unset($handlers[$current]);
        $handlers = array_filter($handlers, fn($quarter) => $quarter->getSide() === $this->quarter->getSide());
        $handlers = array_filter($handlers, function($quarter) {
            return $quarter->getX() === $this->quarter->getX()
                && $quarter->getY() === $this->quarter->getY();
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
     * Перевірити ширину, довжину чверті
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyQuarterWidth(): void
    {
        $length = $this->detail->getLength();
        $width = $this->detail->getWidth();

        $minLength = $this->config['min_length'] ?? 0;
        $minWidth = $this->config['min_width'] ?? 0;

        if ($length < ($this->quarter->getX() + $this->quarter->getLength())) {
            throw new DspHandlerException(['outside']);
        }
        if ($width < ($this->quarter->getY() + $this->quarter->getWidth())) {
            throw new DspHandlerException(['outside']);
        }

        if ($minLength && $minLength > $this->quarter->getLength()) {
            throw new DspHandlerException(['min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->quarter->getWidth()) {
            throw new DspHandlerException(['min_width', [$minWidth]]);
        }
    }

    /**
     * Перевірити глибину чверті
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyQuarterDepth(): void
    {
        $minDepth = $this->config['min_depth'] ?? 0;

        if ($minDepth && $minDepth > $this->quarter->getDepth()) {
            throw new DspHandlerException(['min_depth', [$minDepth]]);
        }
    }

    /**
     * Перевірити розмір деталі
     *
     * @return void
     * @throws DspHandlerException
     */
    private function verifyDetailSize(): void
    {
        $minLength = $this->config['min_length_detail'] ?? 0;
        $minWidth = $this->config['min_width_detail'] ?? 0;

        if ($minLength && $minLength > $this->detail->getLength()) {
            throw new DspHandlerException(['detail__min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->detail->getLength()) {
            throw new DspHandlerException(['detail__min_width', [$minWidth]]);
        }
    }
}