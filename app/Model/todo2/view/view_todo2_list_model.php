<?php

namespace App\Model\todo2\view;

use App\Model\todo2\todo2_model;

class view_todo2_list_model extends todo2_model
{
    /**
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [
            'view_week' => self::DAY_OF_WEEKS, // weeks 
            'list'      => self::todo2Dao()->fetch_view_list(),
        ];
    }

}
