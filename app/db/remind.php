<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

class remind extends Model
{
    protected $table = 'remind';

    private function get_const_week_array()
    {
        $remind_model = new \App\Model\remind\remind_model();
        return $remind_model::DAY_OF_WEEKS;
    }

    // 全ての情報
    public function fetch_all_date()
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " ORDER BY id DESC ";
        
        $week_const = $this->get_const_week_array();

        $re = [];
        foreach(DB::select($sql) as $n => $data) {
            $tmp = $data;
            $tmp->week_name = array_key_exists($data->week, $week_const) ? $week_const[$data->week] : NULL;

            $re[] = $tmp;
        }
        return $re;
    }

    // 今、リマンドする対象データを取得する
    public function fetch_remind_taget_data($time)
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " WHERE ( ";
        $sql .= "       day = '" . date('Y-m-d') . "'";
        $sql .= "   OR week = '" . date('w') . "'";
        $sql .= "      ) ";
        $sql .= "   AND time = '" . $time . "'";
        return DB::select($sql);
    }
}
