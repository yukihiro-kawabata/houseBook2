<?php

namespace App\Model\cash;

use Illuminate\Database\Eloquent\Model;

use  App\Model\cash\cashModel;

use App\db\constant_cash;

class view_cash_constant_list_model extends cashModel
{
    /*
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        $constant_cashDao = new constant_cash();
        $re['list'] = $constant_cashDao->fetch_all_data();
        return $re;
    }
}
