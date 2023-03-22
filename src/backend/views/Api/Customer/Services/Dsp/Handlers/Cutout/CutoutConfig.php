<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Cutout;

class CutoutConfig
{
    private array $config = [];

    /**
     * Отримати налаштування
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Завантажити налаштування з бд
     *
     * @return void
     */
    public function load(): void
    {
        $this->config = [
            'area' => [
                'windowCutout' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 250,
                    'min_length' => 30,
                    'min_width' => 30,
                    'min_radius' => 10,
                    'min_retreat_x' => 160,
                    'min_retreat_y' => 50
                ],
                'circleCutout' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 250,
                    'min_radius' => 10,
                    'min_retreat_x' => 160,
                    'min_retreat_y' => 50
                ],
                'pCutout' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 250,
                    'min_length' => 30,
                    'min_width' => 30,
                    'min_radius' => 10,
                    'min_retreat_x' => 160,
                    'min_retreat_y' => 100
                ],
                'gCutout' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 250,
                    'min_length' => 30,
                    'min_width' => 30,
                    'min_radius' => 10,
                    'min_retreat_x' => 160,
                    'min_retreat_y' => 100
                ]
            ]
        ];
    }
}