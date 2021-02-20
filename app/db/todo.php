<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

class todo extends Model
{
    protected $table = 'todo';

    private function get_const_week_array()
    {
        $todo_model = new \App\Model\todo\todo_model();
        return $todo_model::DAY_OF_WEEKS;
    }

    private function get_const_todo_result_status_array()
    {
        $todo_model = new \App\Model\todo\todo_model();
        return $todo_model::TODO_RESULT_STATUS;
    }

    // 全ての情報
    public function fetch_all_date($sort_col = 'id', $sort_order = 'DESC')
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " ORDER BY $sort_col $sort_order ";
        
        $week_const = $this->get_const_week_array();

        $re = [];
        foreach(DB::select($sql) as $n => $data) {
            $tmp = (array)$data;
            $tmp['week_name'] = array_key_exists($data->week, $week_const) ? $week_const[$data->week] : NULL;

            $re[] = $tmp;
        }

        return $re;
    }

    // 今、リマインドする対象データを取得する
    public function fetch_todo_taget_data($time)
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " WHERE ( ";
        $sql .= "       day = '" . date('Y-m-d') . "'";
        $sql .= "   OR week = '" . date('w') . "'";
        $sql .= "      ) ";
        $sql .= "   AND time LIKE '" . $time . "%'";
        return DB::select($sql);
    }
}
