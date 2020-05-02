<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class cash extends Model
{
    protected $table = 'cash';

    // 明細情報
    public function fetch_all_detail_date(string $from, string $to)
    {
        $sql  = "";
        $sql .= " SELECT * ";
        $sql .= " FROM `cash` ";
        $sql .= " WHERE delete_flg = 0 ";
        $sql .= " AND ( ";
        $sql .= "     date LIKE '$from%' ";
        $sql .= "     OR date LIKE '$to%' ";
        $sql .= " ) ";
        $sql .= " ORDER BY date DESC, created_at DESC ";
        return DB::select($sql);
    }

    // 集計科目ごとの集計
    public function fetch_kamoku_sum_price($date, $user_name = null)
    {
        if (empty($date)) $date = date('Y-m');

        $sql = "";
        $sql .= " SELECT kamoku_mst.kamoku_sum, kamoku_mst.amount_flg, sum(cash.price) AS amount ";
        $sql .= " FROM `cash`";
        $sql .= " INNER JOIN kamoku_mst ON cash.kamoku_id = kamoku_mst.kamoku_id ";
        $sql .= " WHERE cash.date LIKE '$date%' AND cash.delete_flg = 0 ";
        if (!is_null($user_name)) $sql .= "     AND cash.name = '$user_name'"; // NULLのときは全データ書き出し時
        $sql .= " GROUP BY kamoku_mst.kamoku_sum, kamoku_mst.amount_flg ";
        $sql .= " ORDER BY kamoku_mst.amount_flg DESC, amount DESC";

        return DB::select($sql);
    }

    // 年月・集計科目ごとに金額を集計する
    public function fetch_kamoku_sum_price_all()
    {
        if (empty($date)) $date = date('Y-m');

        $sql = "";
        $sql .= " SELECT kamoku_mst.kamoku_sum, kamoku_mst.amount_flg, ";
        $sql .= "        DATE_FORMAT(cash.date, '%Y%m') AS month, ";
        $sql .= "        sum(cash.price) AS amount ";
        $sql .= " FROM `cash`";
        $sql .= " INNER JOIN kamoku_mst ON cash.kamoku_id = kamoku_mst.kamoku_id ";
        $sql .= " WHERE cash.delete_flg = 0 ";
        $sql .= "      AND kamoku_mst.kamoku_sum != '調整勘定' ";
        $sql .= " GROUP BY kamoku_mst.kamoku_sum, kamoku_mst.amount_flg, cash.date ";
        $sql .= " ORDER BY kamoku_mst.amount_flg DESC, kamoku_mst.kamoku_sum DESC ";

        return DB::select($sql);
    }

    // 残高を取得する
    // 今までの残高を取得するなら、$from = 2020-02-01 にする(システムスタートが2020-02-01だから)
    public function sum_balance($from = '2020-02-01', $to = null)
    {
        if (is_null($to)) $to = date('Y-m-d');

        $sql = "";
        $sql .= " SELECT ";
        $sql .= " kamoku_mst.amount_flg, ";
        $sql .= " SUM( ";
        $sql .= "     CASE kamoku_mst.amount_flg ";
        $sql .= "         WHEN 1 THEN cash.price ";
        $sql .= "         WHEN 2 THEN cash.price * -1 ";
        $sql .= "     END ";
        $sql .= " ) AS balance ";
        $sql .= " FROM `cash` ";
        $sql .= " INNER JOIN kamoku_mst ON cash.kamoku_id = kamoku_mst.kamoku_id ";
        $sql .= " WHERE cash.delete_flg = 0 ";
        $sql .= "     AND cash.date BETWEEN '$from' AND '$to' ";
        $sql .= "     AND cash.name != 'yukihiro' AND cash.name != 'kabigon' ";
        $sql .= " GROUP BY kamoku_mst.amount_flg ";

        return DB::select($sql);
    }

    // デビットカードの使用金額
    public function devit_pay_amont(string $from, string $to)
    {
        $sql  = "";
        $sql .= " SELECT SUM(price) AS sum_price ";
        $sql .= " FROM `cash` ";
        $sql .= " WHERE delete_flg = 0 ";
        $sql .= "     AND name = 'devit' ";
        $sql .= "     AND date BETWEEN '$from' AND '$to' ";

        return DB::select($sql);
    }

}
