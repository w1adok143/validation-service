<?php

namespace Kronas\Api\Customer\Services\Dsp\Handlers\Hole;

class HoleConfig
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
            'deaf' => [
                '2' => [
                    'max_depth' => 4,
                    'min_retreat' => 3,
                    'min_retreat_x' => 5,
                    'min_retreat_y' => 5
                ],
                '5' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 6.5,
                    'min_retreat_y' => 6.5
                ],
                '8' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 8,
                    'min_retreat_y' => 8
                ],
                '10' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 9,
                    'min_retreat_y' => 9
                ],
                '15' => [
                    'max_depth' => 15,
                    'min_retreat' => 3,
                    'min_retreat_x' => 11.5,
                    'min_retreat_y' => 11.5
                ],
                '20' => [
                    'max_depth' => 15,
                    'min_retreat_x' => 9.5,
                    'min_retreat_y' => 9.5
                ],
                '26' => [
                    'max_depth' => 15,
                    'min_retreat_x' => 8.5,
                    'min_retreat_y' => 8.5
                ],
                '35' => [
                    'max_depth' => 15,
                    'min_retreat_x' => 8.5,
                    'min_retreat_y' => 8.5
                ]
            ],
            'through' => [
                '5' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 6.5,
                    'min_retreat_y' => 6.5,
                    'max_thickness_detail' => 25
                ],
                '7' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 7.5,
                    'min_retreat_y' => 7.5,
                    'max_thickness_detail' => 30
                ],
                '8' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 8,
                    'min_retreat_y' => 8,
                    'max_thickness_detail' => 30
                ],
                '10' => [
                    'min_retreat' => 3,
                    'min_retreat_x' => 9,
                    'min_retreat_y' => 9,
                    'max_thickness_detail' => 30
                ]
            ],
            'end' => [
                '4.5' => [
                    'max_depth' => 36,
                    'min_retreat' => 3,
                    'min_retreat_x' => 20
                ],
                '8' => [
                    'max_depth' => 36,
                    'min_retreat' => 3,
                    'min_retreat_x' => 20
                ]
            ]
        ];
    }
}