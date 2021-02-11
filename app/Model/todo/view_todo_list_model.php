<?php

namespace App\Model\todo;

use App\Model\todo\todo_model;

class view_todo_list_model extends todo_model
{
    /*
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [
            'view_week' => self::DAY_OF_WEEKS, // weeks 
            'list'      => self::todoDao()->fetch_all_date(), // register data
        ];
    }
}
