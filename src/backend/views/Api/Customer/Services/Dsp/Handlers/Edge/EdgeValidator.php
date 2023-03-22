<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Edge;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;

class EdgeValidator
{
    public function __construct(
        protected Detail $detail,
        protected ?Edge $edge,
        protected array $config = []
    ) {}

    /**
     * Перевірити ширину кромки
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyEdgeWidth(): void
    {
        if (!$this->edge) {
            return;
        }

        $minWidth = $this->detail->getThickness() + 4;
        $maxWidth = $this->detail->getThickness() * 2;

        if ($minWidth > $this->edge->getWidth()) {
            throw new DspHandlerException(['edge__min_width', [$minWidth]]);
        }
        if ($maxWidth < $this->edge->getWidth()) {
            throw new DspHandlerException(['edge__max_width', [$maxWidth]]);
        }
    }

    /**
     * Перевірити розмір деталі
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyDetailSize(): void
    {
        $minLength = $this->config['min_length_detail'] ?? 0;
        $minWidth = $this->config['min_width_detail'] ?? 0;
        $minThickness = $this->edge->getThickness() < 2 ? 8 : 12;

        if ($minLength && $minLength > $this->detail->getLength()) {
            throw new DspHandlerException(['edge__detail__min_length', [$minLength]]);
        }
        if ($minWidth && $minWidth > $this->detail->getWidth()) {
            throw new DspHandlerException(['edge__detail__min_width', [$minWidth]]);
        }
        if ($minThickness > $this->detail->getThickness()) {
            throw new DspHandlerException(['edge__detail__min_thickness', [$minThickness]]);
        }
    }
}