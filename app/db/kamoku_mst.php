<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class kamoku_mst extends Model
{
    protected $table = 'kamoku_mst';

    // 科目マスター一覧画面
    public function fetch_view_list_data()
    {
        $sql = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " ORDER BY kamoku_sum DESC, priority_flg DESC ";

        return DB::select($sql);
    }


    // 集計科目一覧
    public function fetch_all_sum_kamoku()
    {
        $sql = "";
        $sql .= " SELECT DISTINCT ";
        $sql .= " kamoku_sum ";
        $sql .= " FROM $this->table ";
        $sql .= " GROUP BY kamoku_sum ";
        $sql .= " ORDER BY kamoku_sum DESC ";

        return DB::select($sql);
    }    

}
