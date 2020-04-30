<?php

namespace App\Model\kamoku;

use Illuminate\Database\Eloquent\Model;
use App\Model\kamoku\kamoku_model;

class view_kamoku_list_model extends kamoku_model
{
 
    /**
     * view側のデータをまとめる
     */
    public function view_list() : array
    {
        $re = [];

        // 同じ集計科目の場合は、空値として表示させたい
        $tmp = '';
        foreach ($this->fetch_all_data() as $num => $data) {
            if ($tmp === $data['kamoku_sum']) {
                $kamoku_sum = '';
            } else {
                $kamoku_sum = $data['kamoku_sum'];
            }
            $tmp = $data['kamoku_sum'];
            $data['kamoku_sum'] = $kamoku_sum;
            $data['amont_name'] = $this->amount_flg[$data['amount_flg']];
            $re['list'][] = $data;
        }

        return $re;
    }

    /**
     * データ登録用にデータをまとめる
     */
    public function view_regist_data() : array
    {
        // 集計科目リスト
        $re['kamoku_sum_list'] = $this->fetch_all_sum_kamoku();
        $re['amount_flg'] = $this->amount_flg;
        return $re;
    }

}
