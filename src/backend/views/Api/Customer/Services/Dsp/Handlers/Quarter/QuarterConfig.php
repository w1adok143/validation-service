<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Quarter;

class QuarterConfig
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
                'min_length_detail' => 350,
                'min_width_detail' => 100,
                'min_width' => 3,
                'min_length' => 3,
                'min_depth' => 5
            ]
        ];
    }
}