<?php

namespace Kronas\Lib\Geometry;

class Geometry
{
    /**
     * Отримати відстань між двома точками
     *
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @return float
     */
    public static function hypot(float $x1, float $y1, float $x2, float $y2): float
    {
        $x = $x2 - $x1;
        $y = $y2 - $y1;

        return hypot($x, $y);
    }

    /**
     * Перевірити мінімальний відступ між точками з урахуванням діаметру
     *
     * @param float $retreat
     * @param float $x1
     * @param float $y1
     * @param float $diam1
     * @param float $x2
     * @param float $y2
     * @param float $diam2
     * @return float
     */
    public static function isOffsetDiam(float $retreat, float $x1, float $y1, float $diam1, float $x2, float $y2, float $diam2): float
    {
        $r1 = self::calcRadius($diam1);
        $r2 = self::calcRadius($diam2);

        $x1Arr = [
            ['x' => $x1, 'y' => $y1],
            ['x' => $x1 + $r1, 'y' => $y1],
            ['x' => $x1 - $r1, 'y' => $y1],
            ['x' => $x1, 'y' => $y1 + $r1],
            ['x' => $x1, 'y' => $y1 - $r1]
        ];

        $x2Arr = [
            ['x' => $x2, 'y' => $y2],
            ['x' => $x2 + $r2, 'y' => $y2],
            ['x' => $x2 - $r2, 'y' => $y2],
            ['x' => $x2, 'y' => $y2 + $r2],
            ['x' => $x2, 'y' => $y2 - $r2]
        ];

        foreach ($x1Arr as $row1) {
            foreach ($x2Arr as $row2) {
                if ($retreat > self::hypot($row1['x'], $row1['y'], $row2['x'], $row2['y'])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Обчислити радіус за діаметром
     *
     * @param float $diam
     * @return float
     */
    public static function calcRadius(float $diam): float
    {
        return $diam ? $diam / 2 : 0;
    }
}