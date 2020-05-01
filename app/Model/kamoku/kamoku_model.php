<?php

namespace App\Model\kamoku;

use App\Model\common_model;

use App\db\kamoku_mst;

class kamoku_model extends common_model
{
    protected $amount_flg = [1 => '収入', 2 => '支出'];

    /**
     * 科目マスターに登録されているデータを全て取得する
     */
    public function fetch_all_data()
    {
        $re = [];

        $kamoku_mstDao = new kamoku_mst();
        foreach ($kamoku_mstDao->fetch_view_list_data() as $num => $data) {
            $re[] = (array)$data;
        }
        return $re;
    }

    /**
     * 科目マスターの集計科目だけを取得する
     */
    public function fetch_all_sum_kamoku() : array
    {
        $re = [];

        $kamoku_mstDao = new kamoku_mst();
        foreach ($kamoku_mstDao->fetch_all_sum_kamoku() as $num => $data) {
            $re[] = (array)$data;
        }
        return $re;
    }

}
