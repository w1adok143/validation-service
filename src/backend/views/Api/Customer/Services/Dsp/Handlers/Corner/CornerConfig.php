<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Corner;

class CornerConfig
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
            'radius' => [
                'min_length_detail' => 350,
                'min_width_detail' => 100,
                'min_radius' => 10
            ],
            'line' => [
                'min_length_detail' => 350,
                'min_width_detail' => 100,
                'min_retreat_x' => 10,
                'min_retreat_y' => 10
            ]
        ];
    }
}