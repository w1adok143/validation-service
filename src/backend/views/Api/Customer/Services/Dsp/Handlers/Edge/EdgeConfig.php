<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Edge;

class EdgeConfig
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
            'direct' => [
                'min_length_detail' => 170,
                'min_width_detail' => 70
            ],
            'crooked' => [
                'min_length_detail' => 100,
                'min_width_detail' => 70
            ]
        ];
    }
}