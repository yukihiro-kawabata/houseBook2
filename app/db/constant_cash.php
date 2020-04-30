<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class constant_cash extends Model
{
    protected $table = 'constant_cash';

    // 全てのデータを取得する
    public function fetch_all_data()
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        return DB::select($sql);
    }

    // 格納されている名前を取得する
    public function fetch_all_name()
    {
        $sql  = "";
        $sql .= " SELECT name ";
        $sql .= " FROM $this->table ";
        $sql .= " GROUP BY name";
        return DB::select($sql);
    }

}
