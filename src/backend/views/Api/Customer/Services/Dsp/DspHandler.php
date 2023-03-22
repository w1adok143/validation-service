<?php

namespace Kronas\Api\Customer\Services\Dsp;

use Kronas\Api\Customer\Services\Dsp\Handlers\Corner\Corner;
use Kronas\Api\Customer\Services\Dsp\Handlers\Corner\CornerConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Corner\CornerValidatorLine;
use Kronas\Api\Customer\Services\Dsp\Handlers\Corner\CornerValidatorRadius;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\Cutout;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutValidatorCircleCutout;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutValidatorGCutout;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutValidatorPCutout;
use Kronas\Api\Customer\Services\Dsp\Handlers\Cutout\CutoutValidatorWindowCutout;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\Edge;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Edge\EdgeValidator;
use Kronas\Api\Customer\Services\Dsp\Handlers\Groove\Groove;
use Kronas\Api\Customer\Services\Dsp\Handlers\Groove\GrooveConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Groove\GrooveValidatorFullGroove;
use Kronas\Api\Customer\Services\Dsp\Handlers\Groove\GrooveValidatorNotFullGroove;
use Kronas\Api\Customer\Services\Dsp\Handlers\Hole\Hole;
use Kronas\Api\Customer\Services\Dsp\Handlers\Hole\HoleConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Hole\HoleValidator;
use Kronas\Api\Customer\Services\Dsp\Handlers\Quarter\Quarter;
use Kronas\Api\Customer\Services\Dsp\Handlers\Quarter\QuarterConfig;
use Kronas\Api\Customer\Services\Dsp\Handlers\Quarter\QuarterValidator;
use Kronas\Lib\Detail\Detail;

class DspHandler
{
    public function __construct(
        private Detail $detail
    ) {}

    /**
     * Перевірити кромкування
     *
     * @param Edge[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyEdges(array $handlers): void
    {
        $config = app(EdgeConfig::class);
        $config = $config['direct'] ?? [];

        foreach ($handlers as $current => $edge) {
            try {
                if (empty($edge)) {
                    continue;
                }

                $validator = new EdgeValidator($this->detail, $edge, $config);
                $validator->verifyDetailSize();
                $validator->verifyEdgeWidth();
            } catch (DspHandlerException $e) {
                $e->setErrorOperationIndex([$current]);
                $e->setErrorHandler('edges');

                throw $e;
            }
        }
    }

    /**
     * Перевірити свердління
     *
     * @param Hole[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyHoles(array $handlers): void
    {
        $configHole = app(HoleConfig::class);

        if (empty($handlers)) {
            return;
        }

        foreach ($handlers as $current => $hole) {
            $config = [];

            try {
                if ($this->detail->isDeaf($hole->getSide(), $hole->getDepth())) {
                    $config = $configHole['deaf'] ?? [];
                }
                if ($this->detail->isThrough($hole->getSide(), $hole->getDepth())) {
                    $config = $configHole['through'] ?? [];
                }
                if ($this->detail->isEnd($hole->getSide())) {
                    $config = $configHole['end'] ?? [];
                }
                if (empty($config[strval($hole->getDiam())])) {
                    throw new DspHandlerException(['hole__config__diam__not_found', [$hole->getDiam()]]);
                }

                $validator = new HoleValidator($this->detail, $hole, $config[strval($hole->getDiam())]);
                $validator->verifyDetailSize();
                $validator->verifyHoleDuplicate($current, $handlers);
                $validator->verifyHoleDepth();
                $validator->verifyHoleRetreat($current, $handlers);
            } catch (DspHandlerException $e) {
                if (empty($e->getErrorOperationIndex())) {
                    $e->setErrorOperationIndex([$current]);
                }

                $e->setErrorOperationName($hole->getSubtype());
                $e->setErrorHandler('holes');

                throw $e;
            }
        }
    }

    /**
     * Перевірити пази
     *
     * @param Groove[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyGrooves(array $handlers): void
    {
        $configGroove = app(GrooveConfig::class);

        if (empty($handlers)) {
            return;
        }

        foreach ($handlers as $current => $groove) {
            $config = [];

            try {
                if ($this->detail->isArea($groove->getSide())) {
                    $config = $configGroove['area'] ?? [];
                }
                if ($this->detail->isEnd($groove->getSide())) {
                    $config = $configGroove['end'] ?? [];
                }

                /** Паз на всю довжину */
                if ($groove->isFull()) {
                    $config = $config['fullGroove'] ?? [];

                    $validator = new GrooveValidatorFullGroove($this->detail, $groove, $config);
                    $validator->verifyGrooveDuplicate($current, $handlers);
                    $validator->verifyGroove();

                    continue;
                }

                /** Паз не на всю довжину */
                if ($groove->isNotFull()) {
                    $config = $config['notFullGroove'] ?? [];

                    $validator = new GrooveValidatorNotFullGroove($this->detail, $groove, $config);
                    $validator->verifyGrooveDuplicate($current, $handlers);
                    $validator->verifyGroove();

                    continue;
                }

                /** Паз не визначено */
                throw new DspHandlerException(['not_specified']);
            } catch (DspHandlerException $e) {
                if (empty($e->getErrorOperationIndex())) {
                    $e->setErrorOperationIndex([$current]);
                }

                $e->setErrorOperationName($groove->getSubtype());
                $e->setErrorHandler('grooves');

                throw $e;
            }
        }
    }

    /**
     * Перевірити чверті
     *
     * @param Quarter[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyQuarters(array $handlers): void
    {
        $config = app(QuarterConfig::class);
        $config = $config['area'] ?? [];

        if (empty($handlers)) {
            return;
        }

        foreach ($handlers as $current => $quarter) {
            try {

                /** Операція доступна тільки на площині */
                if (!$this->detail->isArea($quarter->getSide())) {
                    throw new DspHandlerException(['only_area']);
                }

                $validator = new QuarterValidator($this->detail, $quarter, $config);
                $validator->verifyQuarterDuplicate($current, $handlers);
                $validator->verifyQuarter();
            } catch (DspHandlerException $e) {
                if (empty($e->getErrorOperationIndex())) {
                    $e->setErrorOperationIndex([$current]);
                }

                $e->setErrorOperationName($quarter->getSubtype());
                $e->setErrorHandler('quarters');

                throw $e;
            }
        }
    }

    /**
     * Перевірити вирізи
     *
     * @param Cutout[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCutouts(array $handlers): void
    {
        $configCutout = app(CutoutConfig::class);
        $configCutoutEdge = app(EdgeConfig::class);

        if (empty($handlers)) {
            return;
        }

        $length = $this->detail->getLength();
        $width = $this->detail->getWidth();

        foreach ($handlers as $current => $cutout) {
            try {

                /** Операція доступна тільки на площині */
                if (!$this->detail->isArea($cutout->getSide())) {
                    throw new DspHandlerException(['only_area']);
                }

                /** Операція 'Прямокутний виріз' */
                if ($cutout->isWindowCutout()) {
                    $config = $configCutout['area']['windowCutout'] ?? [];

                    $validator = new CutoutValidatorWindowCutout($this->detail, $cutout, $config);
                    $validator->verifyCutoutDuplicate($current, $handlers);
                    $validator->verifyCutout();

                    continue;
                }

                /** Операція 'Круговий виріз' */
                if ($cutout->isCircleCutout()) {
                    $config = $configCutout['area']['circleCutout'] ?? [];

                    $validator = new CutoutValidatorCircleCutout($this->detail, $cutout, $config);
                    $validator->verifyCutoutDuplicate($current, $handlers);
                    $validator->verifyCutout();

                    continue;
                }

                /** Операція 'Г - подібний виріз' */
                if ($cutout->isGCutout($length, $width)) {
                    $config = $configCutout['area']['gCutout'] ?? [];
                    $configEdge = $configCutoutEdge['crooked'] ?? [];

                    $validator = new CutoutValidatorGCutout($this->detail, $cutout, $config, $configEdge);
                    $validator->verifyCutoutDuplicate($current, $handlers);
                    $validator->verifyCutout();

                    continue;
                }

                /** Операція 'П - подібний виріз' */
                if ($cutout->isPCutout($length, $width)) {
                    $config = $configCutout['area']['pCutout'] ?? [];
                    $configEdge = $configCutoutEdge['crooked'] ?? [];

                    $validator = new CutoutValidatorPCutout($this->detail, $cutout, $config, $configEdge);
                    $validator->verifyCutoutDuplicate($current, $handlers);
                    $validator->verifyCutout();

                    continue;
                }

                /** Операція не визначена */
                throw new DspHandlerException(['not_specified']);
            } catch (DspHandlerException $e) {
                $e->setErrorOperationIndex([$current]);
                $e->setErrorOperationName($cutout->getSubtype());
                $e->setErrorHandler('cutouts');

                throw $e;
            }
        }
    }

    /**
     * Перевірити кути
     *
     * @param Corner[] $handlers
     * @return void
     * @throws DspHandlerException
     */
    public function verifyCorners(array $handlers): void
    {
        $configCorner = app(CornerConfig::class);
        $configCornerEdge = app(EdgeConfig::class);

        if (empty($handlers)) {
            return;
        }

        foreach ($handlers as $current => $corner) {
            try {

                /** Тип обробки 'Радіус' */
                if ($corner->getType() === 'radius') {
                    $config = $configCorner['radius'] ?? [];
                    $configEdge = $configCornerEdge['crooked'] ?? [];

                    $validator = new CornerValidatorRadius($this->detail, $corner, $config, $configEdge);
                    $validator->verifyCornerDuplicate($current, $handlers);
                    $validator->verifyCorner();

                    continue;
                }

                /** Тип обробки 'Зріз' */
                if ($corner->getType() === 'line') {
                    $config = $configCorner['line'] ?? [];
                    $configEdge = $configCornerEdge['crooked'] ?? [];

                    $validator = new CornerValidatorLine($this->detail, $corner, $config, $configEdge);
                    $validator->verifyCornerDuplicate($current, $handlers);
                    $validator->verifyCorner();

                    continue;
                }
            } catch (DspHandlerException $e) {
                if (empty($e->getErrorOperationIndex())) {
                    $e->setErrorOperationIndex([$current]);
                }

                $e->setErrorOperationName($corner->getSubtype());
                $e->setErrorHandler('corners');

                throw $e;
            }
        }
    }

    /**
     * Створити кромкування
     *
     * @param array $handlers
     * @return Edge[]
     */
    public function createEdges(array $handlers): array
    {
        if (!empty($handlers['left'])) {
            $left = $handlers['left'];
            $left = new Edge($left['width'], $left['thickness']);
        } else {
            $left = null;
        }

        if (!empty($handlers['top'])) {
            $top = $handlers['top'];
            $top = new Edge($top['width'], $top['thickness']);
        } else {
            $top = null;
        }

        if (!empty($handlers['right'])) {
            $right = $handlers['right'];
            $right = new Edge($right['width'], $right['thickness']);
        } else {
            $right = null;
        }

        if (!empty($handlers['bottom'])) {
            $bottom = $handlers['bottom'];
            $bottom = new Edge($bottom['width'], $bottom['thickness']);
        } else {
            $bottom = null;
        }

        return [
            'left' => $left, 'top' => $top, 'right' => $right, 'bottom' => $bottom
        ];
    }

    /**
     * Створити свердління
     *
     * @param array $handlers
     * @return Hole[]
     */
    public function createHoles(array $handlers): array
    {
        return array_map(fn($handler) => new Hole(
            side: $handler['side'],
            x: $handler['x'],
            y: $handler['y'],
            z: $handler['z'],
            depth: $handler['depth'],
            diam: $handler['diam'],
            subtype: $handler['subtype'] ?? null
        ), $handlers);
    }

    /**
     * Створити пази
     *
     * @param array $handlers
     * @return Groove[]
     */
    public function createGrooves(array $handlers): array
    {
        return array_map(fn($handler) => new Groove(
            side: $handler['side'],
            direction: $handler['direction'],
            x: $handler['x'],
            y: $handler['y'],
            z: $handler['z'],
            length: $handler['height'],
            width: $handler['width'],
            depth: $handler['depth'],
            radius: $handler['r'],
            isFull: $handler['isFull'],
            subtype: $handler['subtype'] ?? null
        ), $handlers);
    }

    /**
     * Створити чверті
     *
     * @param array $handlers
     * @return Quarter[]
     */
    public function createQuarters(array $handlers): array
    {
        return array_map(fn($handler) => new Quarter(
            side: $handler['side'],
            x: $handler['x'],
            y: $handler['y'],
            length: $handler['height'],
            width: $handler['width'],
            depth: $handler['depth'],
            radius: $handler['r'],
            isFull: $handler['isFull'],
            subtype: $handler['subtype'] ?? null,
            subside: $handler['subside'] ?? null
        ), $handlers);
    }

    /**
     * Створити вирізи
     *
     * @param array $handlers
     * @return Cutout[]
     */
    public function createCutouts(array $handlers): array
    {
        return array_map(function($handler) {
            $edge = $handler['edge'];

            if (!empty($edge)) {
                $handler['edge'] = new Edge($edge['width'], $edge['thickness']);
            }

            return new Cutout(
                side: $handler['side'],
                x: $handler['x'],
                y: $handler['y'],
                length: $handler['height'],
                width: $handler['width'],
                depth: $handler['depth'],
                radius: $handler['r'],
                edge: $handler['edge'],
                subtype: $handler['subtype'], // Обов'язково мають передати 'subtype'
                subside: $handler['subside'] ?? null
            );
        }, $handlers);
    }

    /**
     * Створити кути
     *
     * @param array $handlers
     * @return Corner[]
     */
    public function createCorners(array $handlers): array
    {
        return array_map(function($handler) {
            $edge = $handler['edge'];

            if (!empty($edge)) {
                $handler['edge'] = new Edge($edge['width'], $edge['thickness']);
            }

            return new Corner(
                angle: $handler['angle'],
                x: $handler['x'],
                y: $handler['y'],
                type: $handler['type'],
                radius: $handler['r'],
                edge: $handler['edge'],
                subtype: $handler['subtype'] ?? null
            );
        }, $handlers);
    }
}