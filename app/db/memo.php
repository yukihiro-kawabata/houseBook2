<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class memo extends Model
{
    protected $table = 'memo';

    // カラムをkeyにした配列を返す
    public function get_col()
    {
        // array_flipだと、valueに余計な数字(元々のkey)が入るので、この形にした
        foreach (Schema::getColumnListing($this->table) as $val => $col) {
            $re[$col] = '';
        }
        return $re;
    }

    // 全ての情報
    public function fetch_all_date($sort_col = 'id', $sort_order = 'DESC')
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " ORDER BY $sort_col $sort_order ";
        
        return put_laravel_db_array(DB::select($sql));
    }

    // idを元にデータを取得すr
    public function fetch_date_by_id($id)
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM $this->table ";
        $sql .= " WHERE id = $id ";
        
        $re = DB::select($sql);
        return empty($re) ? [] : (array)$re[0];
    }

}
