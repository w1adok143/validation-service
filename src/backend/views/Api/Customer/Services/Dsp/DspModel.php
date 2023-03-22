<?php

namespace Kronas\Api\Customer\Services\Dsp;

use Illuminate\Support\Facades\DB;
use Kronas\Api\BaseApiModel;

class DspModel extends BaseApiModel
{
    /**
     * Отримати філіал за ідентифікатором
     *
     * @param int $id
     * @return array|null
     */
    public function select(int $id): ?array
    {
        $rows = DB::select("
            SELECT *
            FROM departments
            WHERE uid = :id AND active = 1
        ", ['id' => $id]);

        $rows = array_reduce($rows, function($rows, $row) {
            $rows[] = [
                'id' => $row->id,
                'uid' => $row->uid,
                'settings' => json_decode($row->settings, true)
            ];

            return $rows;
        }, []);

        return !empty($rows) ? $rows[0] : null;
    }
}