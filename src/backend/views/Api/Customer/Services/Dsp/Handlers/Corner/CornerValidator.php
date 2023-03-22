<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Corner;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;

abstract class CornerValidator
{
    public function __construct(
        protected Detail $detail,
        protected Corner $corner,
        protected array $config = [],
        protected array $configEdge = []
    ) {}

    /**
     * Перевірити кут на дублікат
     *
     * @param int $current
     * @param Corner[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCornerDuplicate(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        unset($handlers[$current]);
        $handlers = array_filter($handlers, fn($corner) => $corner->getAngle() === $this->corner->getAngle());

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