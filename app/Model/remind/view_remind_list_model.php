<?php

namespace App\Model\remind;

use App\Model\remind\remind_model;

class view_remind_list_model extends remind_model
{
    /*
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [
            'view_week' => self::DAY_OF_WEEKS, // weeks 
            'list'      => self::remindDao()->fetch_all_date(), // register data
        ];
    }
}
