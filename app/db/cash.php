<?php

namespace App\db;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Config;

class cash extends Model
{
    protected $table = 'cash';

    // 明細情報
    public function fetch_all_detail_date(string $from, string $to)
    {
        $sql  = "";
        $sql .= " SELECT *, ";
        $sql .= " (CASE half_flg WHEN 1 THEN '月末精算' ELSE '' END) AS half_flg_str ";
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

    // 月末精算用のデータを取得する
    public function fetch_pay_off(int $month)
    {
        $sql  = "";
        $sql .= " SELECT name, SUM(price) AS price ";
        $sql .= " FROM $this->table ";
        $sql .= " WHERE `half_flg` = 1 AND `delete_flg` = 0 ";
        $sql .= "   AND `date` BETWEEN '2020-$month-01 00:00:00' AND '2020-$month-31 23:59:59' ";
        $sql .= " GROUP BY name";
        return DB::select($sql);
    }

    // 月末精算対象のユーザごとの数字を返す
    public function fetch_pay_off_user_each(int $month) : array
    {
        $month = sprintf('%02d', $month);

        // 精算登録されたものをユーザごとにまとめる
        $re = [];
        foreach($this->fetch_pay_off($month) as $num => $data) {
            $re[$data->name] = $data->price;
        }
        // 精算対象者、全員分の精算金額をまとめる
        foreach (Config::get('cash_const.pay_off_user') as $user) {
            $user_pay[$user] = array_key_exists($user, $re) ? $re[$user] : 0;
        }
        // 2人しかいない前提なので
        $user_pay['diff'] = abs($user_pay['yukihiro'] - $user_pay['kabigon']);
        $user_pay['half'] = round($user_pay['diff'] / 2);
        return $user_pay;
    }

}
