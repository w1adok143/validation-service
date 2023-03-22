<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Hole;

use Kronas\Api\Customer\Services\Dsp\DspHandlerException;
use Kronas\Lib\Detail\Detail;
use Kronas\Lib\Geometry\Geometry;

class HoleValidator
{
    public function __construct(
        private Detail $detail,
        private Hole $hole,
        private array $config = []
    ) {}

    /**
     * Перевірити отвір на дублікат
     *
     * @param int $current
     * @param Hole[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyHoleDuplicate(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        unset($handlers[$current]);
        $handlers = array_filter($handlers, fn($hole) => $hole->getSide() === $this->hole->getSide());
        $handlers = array_filter($handlers, function($hole) {
            return $hole->getX() === $this->hole->getX()
                && $hole->getY() === $this->hole->getY()
                && $hole->getZ() === $this->hole->getZ();
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
     * Перевірити глибину отвору
     *
     * @return void
     * @throws DspHandlerException
     */
    public function verifyHoleDepth(): void
    {
        $maxDepth = $this->config['max_depth'] ?? 0;

        /** Обчислити глибину для глухого отвору з діаметром 5, 8, 10 */
        if ($this->detail->isDeaf($this->hole->getSide(), $this->hole->getDepth()) && in_array(strval($this->hole->getDiam()), [5, 8, 10])) {
            $maxDepth = $this->detail->getThickness() - 3;
        }

        /** Обчислити глибину для наскрізного отвору */
        if ($this->detail->isThrough($this->hole->getSide(), $this->hole->getDepth())) {
            $maxDepth = $this->detail->getThickness() + 5;
        }

        /** Перевірити глибину */
        if ($maxDepth && $maxDepth < $this->hole->getDepth()) {
            throw new DspHandlerException(['max_depth', [$maxDepth]]);
        }
    }

    /**
     * Перевірити відступ по отворам
     *
     * @param int $current
     * @param Hole[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyHoleRetreat(int $current, array $handlers): void
    {
        if (empty($handlers)) {
            return;
        }

        $length = $this->detail->getLength($this->hole->getSide());
        $width = $this->detail->getWidth($this->hole->getSide());

        $minX = $this->config['min_retreat_x'] ?? 0;
        $minY = $this->config['min_retreat_y'] ?? 0;

        /** Відступ між свердліннями */
        $retreat = $this->config['min_retreat'] ?? 0;

        /** Перевірити відступ на площині */
        if ($this->detail->isArea($this->hole->getSide())) {
            if ($length < $this->hole->getX()) {
                throw new DspHandlerException(['outside']);
            }
            if ($width < $this->hole->getY()) {
                throw new DspHandlerException(['outside']);
            }

            if ($minX && $minX > $this->hole->getX()) {
                throw new DspHandlerException(['min_retreat_x', [$minX]]);
            }
            if ($minX && $minX > ($length - $this->hole->getX())) {
                throw new DspHandlerException(['min_retreat_x', [$minX]]);
            }

            if ($minY && $minY > $this->hole->getY()) {
                throw new DspHandlerException(['min_retreat_y', [$minY]]);
            }
            if ($minY && $minY > ($length - $this->hole->getY())) {
                throw new DspHandlerException(['min_retreat_y', [$minY]]);
            }
        }

        /** Перевірити відступ на торцях */
        if ($this->detail->isEnd($this->hole->getSide())) {
            if ($length < $this->hole->getX()) {
                throw new DspHandlerException(['outside']);
            }
            if ($width < $this->hole->getZ()) {
                throw new DspHandlerException(['outside']);
            }

            if ($minX && $minX > $this->hole->getX()) {
                throw new DspHandlerException(['min_retreat_y', [$minX]]);
            }
            if ($minX && $minX > ($length - $this->hole->getX())) {
                throw new DspHandlerException(['min_retreat_y', [$minX]]);
            }
        }

        /** Перевірити відступ між свердліннями */
        if ($retreat) {
            unset($handlers[$current]);
            $handlers = array_filter($handlers, fn($hole) => $hole->getSide() === $this->hole->getSide());
            $handlers = array_filter($handlers, fn($hole) => !in_array($hole->getDiam(), [20, 26, 35]));
            $handlers = array_filter($handlers, function($hole) use ($retreat) {
                $y1 = $this->detail->isEnd($this->hole->getSide()) ? $this->hole->getZ() : $this->hole->getY();
                $y2 = $this->detail->isEnd($hole->getSide()) ? $hole->getZ() : $hole->getY();

                return !Geometry::isOffsetDiam(
                    retreat: $retreat,
                    x1: $this->hole->getX(),
                    y1: $y1,
                    diam1: $this->hole->getDiam(),
                    x2: $hole->getX(),
                    y2: $y2,
                    diam2: $hole->getDiam()
                );
            });

            try {
                if (!empty($handlers)) {
                    throw new DspHandlerException(['hole__retreat']);
                }
            } catch (DspHandlerException $e) {
                $e->setErrorOperationIndex(array_merge([$current], array_keys($handlers)));

                throw $e;
            }
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
        $maxThickness = $this->config['max_thickness_detail'] ?? 0;

        if ($maxThickness && $maxThickness < $this->detail->getThickness()) {
            throw new DspHandlerException(['detail__max_thickness', [$maxThickness]]);
        }
    }
}