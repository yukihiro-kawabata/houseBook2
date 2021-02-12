<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

class todo_result extends Model
{
    protected $table = 'todo_result';

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
            $re[] = (array)$data;
        }
        return $re;
    }

}
