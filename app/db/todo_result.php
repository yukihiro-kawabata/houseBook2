<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\Config;

class todo_result extends Model
{
    protected $table = 'todo_result';

    private function get_const_todo_result_status_array()
    {
        $todo_model = new \App\Model\todo\todo_model();
        return $todo_model::TODO_RESULT_STATUS;
    }

    public function getColums()
    {
      return Schema::getColumnListing($this->table);
    }

    // 全ての情報
    public function fetch_all_date($sort_col = 'id', $sort_order = 'DESC')
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= "   ,id AS todo_result_id ";
        $sql .= "   ,created_at AS todo_result_created_at ";
        $sql .= "   ,updated_at AS todo_result_updated_at ";
        $sql .= " FROM $this->table ";
        $sql .= " ORDER BY $sort_col $sort_order ";

        $todo_result_status = $this->get_const_todo_result_status_array();

        $re = [];
        foreach(DB::select($sql) as $n => $data) {
            $tmp = (array)$data;
            $tmp['todo_result_status_name'] = array_key_exists($data->status, $todo_result_status) ? $todo_result_status[$data->status] : NULL;

            $re[] = $tmp;
        }

        return $re;
    }

}
