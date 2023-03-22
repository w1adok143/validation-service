<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeederDepartment extends Seeder
{
    public function run()
    {
        DB::table('departments')->truncate();
        DB::table('departments')->insert([
            [
                'uid' => 49159,
                'name' => 'Житомир',
                'settings' => null
            ],
            [
                'uid' => 49311,
                'name' => 'Запоріжжя',
                'settings' => json_encode([
                    'min_length' => 350,
                    'min_width' => 100,
                    'max_length' => 3100,
                    'max_width' => 950
                ])
            ],
            [
                'uid' => 34935,
                'name' => 'Київ - Волинська',
                'settings' => null
            ],
            [
                'uid' => 54096,
                'name' => 'Київ - Корольова',
                'settings' => json_encode([
                    'min_length' => 200,
                    'min_width' => 70,
                    'max_length' => 3100,
                    'max_width' => 1250
                ])
            ],
            [
                'uid' => 72,
                'name' => 'Київ - Куренівська',
                'settings' => json_encode([
                    'min_length' => 350,
                    'min_width' => 100,
                    'max_length' => 3100,
                    'max_width' => 950
                ])
            ],
            [
                'uid' => 36408,
                'name' => 'Краматорськ',
                'settings' => null
            ],
            [
                'uid' => 36409,
                'name' => 'Маріуполь - М. Мазая',
                'settings' => null
            ],
            [
                'uid' => 36410,
                'name' => 'Одеса - Дальницька',
                'settings' => json_encode([
                    'min_length' => 200,
                    'min_width' => 70,
                    'max_length' => 3100,
                    'max_width' => 1250
                ])
            ],
            [
                'uid' => 48454,
                'name' => 'Одеса - Миколаївська',
                    'settings' => json_encode([
                    'min_length' => 200,
                    'min_width' => 70,
                    'max_length' => 3100,
                    'max_width' => 1250
                ])
            ],
            [
                'uid' => 148303,
                'name' => 'Київ - Сергієнко',
                'settings' => null
            ]
        ]);
    }
}
