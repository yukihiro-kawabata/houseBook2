<?php

namespace App\Model\cash;

use App\Model\common_model;
use Illuminate\Support\Facades\Config;

use App\db\cash;

class cashModel extends common_model
{
    private $devit_day_from = 15;
    private $devit_day_to = 15;

    /*
     * 一覧で使用する集計科目ごとのサマリーデータ
     * @param array $date 指定年月 ex). 2019-12
     */
    public function sum_kamoku_list($date) : array
    {
        if (empty($date) || mb_strlen($date) !== 6) $date = date('Ym');

        $year  = preg_replace('/\d{2}$/', '', $date);
        $month = preg_replace('/^\d{4}/', '', $date);

        $cashDao = new cash();
        $data = [];
        // null でユーザ制限をなくして取得ができる
        foreach (array_merge(Config::get('cash_const.user_name'), [null]) as $user_name) {
            $cashDatas = $cashDao->fetch_kamoku_sum_price("$year-$month", $user_name);
            $key_name = $user_name;
            if (is_null($key_name)) $key_name = "ALL"; // view側の仕様
            foreach ($cashDatas as $cashDataNum => $cashData) {
                $tmp = [];
                $tmp['kamoku_sum'] = $cashData->kamoku_sum;
                $tmp['amount']     = number_format((int)$cashData->amount);
                $tmp['amount_flg'] = $cashData->amount_flg;
                $data[$key_name][] = $tmp;
            }
        }
        return $data;
    }

    /**
     * 年月ごとに集計科目の金額を取得する
     */
    public function sum_kamoku_list_all()
    {
        $re  = [];
        $tmp = [];
        $kamoku = [];

        $cashDao = new cash();
        // 集計科目をkeyに年月ごとに金額をまとめる
        foreach ($cashDao->fetch_kamoku_sum_price_all() as $num => $data) {
            if (!array_key_exists($data->kamoku_sum, $tmp)) $tmp[$data->kamoku_sum] = [];
            // SQLでうまく集計できなかったので、集計科目・年月ごとに集計する
            if (!array_key_exists($data->month, $tmp[$data->kamoku_sum])) {
                $tmp[$data->kamoku_sum][$data->month] = (int)$data->amount;
            } else {
                $tmp[$data->kamoku_sum][$data->month] = $tmp[$data->kamoku_sum][$data->month] + $data->amount;
            }

            $kamoku[$data->kamoku_sum] = '';
        }
        $re['kamoku_list'] = $kamoku;
    
        // 今月から1年前までの配列を用意する（DBで取得するとデータがない月は上記の処理で年月なしで来るので補填するのが目的）
        foreach ($tmp as $sum_kamoku => $data) {
            foreach (all_year_month() as $month => $val) {
                if (!array_key_exists($month, $tmp[$sum_kamoku])) {
                    $re[$sum_kamoku][$month] = 0;
                } else {
                    $re[$sum_kamoku][$month] = $tmp[$sum_kamoku][$month];
                }
            }
        }
        return $re;
    }

    /*
     * 現在の残高を取得する
     * @return int $re 残高
     */
    public function sum_balance() : int
    {
        $re= 0;

        $cashDao = new cash();
        $sum_balance = $cashDao->sum_balance();
        foreach ($sum_balance as $num => $balance) {
            $re += $balance->balance; // クエリで支出はマイナスで取得するようにしたので単純に足し算すればOK
        }
        return $re;
    }

    /**
     * 現在の残高を取得する
     * @param string|date $date 指定年月
     * @return int $re 残高
     */
    public function sum_balance_target_month($date) : array
    {
        $re= ['income' => 0, 'expence' => 0, 'profit' => 0];

        $year  = preg_replace('/\d{2}$/', '', $date);
        $month = preg_replace('/^\d{4}/', '', $date);

        $cashDao = new cash();
        $sum_balance = $cashDao->sum_balance("$year-$month-01", "$year-$month-31");
        foreach ($sum_balance as $num => $data) {
            if ($data->amount_flg === 1) $re['income']  = $data->balance;
            if ($data->amount_flg === 2) $re['expence'] = $data->balance;
        }
        $re['profit'] = $re['income'] + $re['expence']; // 支出はマイナスがついているので演算処理は加算でOK
        return $re;
    }
        
    /*
     * 現在のデビットカードの使用金額を取得する
     * @param int $date yyyymm形式の年月
     */ 
    public function card_pay_fee(int $date) : int
    {
        if (empty($date) || mb_strlen($date) !== 6) $date = date('Ym');

        $year  = preg_replace('/\d{2}$/', '', $date);
        $month = (int)preg_replace('/^\d{4}/', '', $date);

        // 指定年月が今月　且つ　今月が15日を過ぎていなければ
        if ($month === (int)date('n') && (int)date('d') < 15) {
            $from = "$year-" . sprintf('%02d', $month - 1) . "-" . $this->devit_day_from;
            $to   = "$year-". sprintf('%02d', $month) . "-" . $this->devit_day_to;
        } else {
            $from = "$year-" . sprintf('%02d', $month) . "-" . $this->devit_day_from;
            $to   = "$year-" . sprintf('%02d', $month + 1) . "-" . $this->devit_day_to;
        }

        $cashDao = new cash();
        $re = 0;
        foreach ($cashDao->devit_pay_amont($from, $to) as $num => $data) {          
            $re = $data->sum_price;
            if (empty($re)) $re = 0;
            break;
        }
        return $re;
    }

    /*
     * 指定年月と指定年月の前月の明細情報を取得する
     */ 
    public function fetch_detail($date) : array
    {
        if (empty($date) || mb_strlen($date) !== 6) $data = date('Ym');

        $year  = preg_replace('/\d{2}$/', '', $date);
        $month = (int)preg_replace('/^\d{4}/', '', $date);

        // $from = "$year-" . sprintf('%02d', $month - 1); // 2ヶ月分表示しても閲覧していないので一時的にコメントアウト
        $from = "$year-" . sprintf('%02d', $month);
        $to   = "$year-" . sprintf('%02d', $month);

        $cashDao = new cash();
        return $cashDao->fetch_all_detail_date($from, $to);
    }

}
