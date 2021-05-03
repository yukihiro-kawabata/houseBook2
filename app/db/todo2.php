<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

class todo2 extends Model
{
    protected $table = 'todo2';

    private function get_const_week_array()
    {
        $todo_model = new \App\Model\todo2\todo2_model();
        return $todo_model::DAY_OF_WEEKS;
    }

    private function get_const_todo_result_status_array()
    {
        $todo_model = new \App\Model\todo2\todo2_model();
        return $todo_model::TODO_STATUS;
    }

    // 一覧表示用の情報
    public function fetch_view_list($sort_col = 'day', $sort_order = 'ASC')
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " WHERE status = 1 ";
        $sql .= " ORDER BY $sort_col $sort_order ";
        
        $week_const = $this->get_const_week_array();
        $status_const = $this->get_const_todo_result_status_array();

        $re = [];
        foreach(DB::select($sql) as $n => $data) {
            $tmp = (array)$data;
            $tmp['week_name']   = array_key_exists($data->week, $week_const)     ? $week_const[$data->week]     : NULL;
            $tmp['status_name'] = array_key_exists($data->status, $status_const) ? $status_const[$data->status] : NULL;

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
        $sql .= " WHERE ";
        $sql .= "   status = 1 ";
        $sql .= "   AND day = '" . date('Y-m-d') . "'";
        $sql .= "   AND time LIKE '" . $time . "%'";
        return DB::select($sql);
    }

    // 未着手のToDoを取得する（当日中のもの）
    // @param int $remind_total_count_limit リマインドn回以内のデータを取得する
    public function fetch_todo_not_yet(int $remind_total_count_limit = 3) : array
    {
        $today = date('Y-m-d');
        $time  = date('H:i:s');

        $sql = "
            SELECT *
            FROM $this->table
            WHERE 
                `status` = 1 
                AND `day` < '$today'
        "; // AND `remind_total_count` < $remind_total_count_limit

        $re = [];
        foreach(DB::select($sql) as $n => $data) {
            $re[] = (array)$data;
        }

        return $re;
    }
}
