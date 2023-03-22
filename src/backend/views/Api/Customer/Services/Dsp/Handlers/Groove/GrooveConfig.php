<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Groove;

class GrooveConfig
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
                'fullGroove' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 100,
                    'min_thickness_detail' => 10,
                    'max_thickness_detail' => 60,
                    'min_width' => 3.2,
                    'min_length' => 3.2,
                    'min_retreat_x' => 6,
                    'min_retreat_y' => 10
                ],
                'notFullGroove' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 100,
                    'min_thickness_detail' => 10,
                    'max_thickness_detail' => 60,
                    'min_width' => 3.2,
                    'min_length' => 10,
                    'min_retreat_x' => 6,
                    'min_retreat_y' => 10,
                    'min_radius' => 3
                ]
            ],
            'end' => [
                'fullGroove' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 100,
                    'min_thickness_detail' => 16,
                    'max_thickness_detail' => 60,
                    'min_width' => 2.5,
                    'min_length' => 2.5,
                    'max_depth' => 25,
                    'min_retreat_x' => 3,
                    'min_retreat_z' => 5
                ],
                'notFullGroove' => [
                    'min_length_detail' => 350,
                    'min_width_detail' => 100,
                    'min_thickness_detail' => 16,
                    'max_thickness_detail' => 60,
                    'min_width' => 2.5,
                    'min_length' => 10,
                    'max_depth' => 25,
                    'min_retreat_x' => 3,
                    'min_retreat_z' => 5
                ]
            ]
        ];
    }
}