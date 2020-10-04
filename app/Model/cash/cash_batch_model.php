<?php

namespace App\Model\cash;

use Illuminate\Database\Eloquent\Model;

use App\db\cash;
use App\db\constant_cash;

use  App\Model\cash\cashModel;
use App\Model\api\api_model;
use App\Model\slack\slack_push_model;

use Illuminate\Support\Facades\Config;

class cash_batch_model extends cashModel
{
    // 登録する項目、※dateカラムは別で格納している
    private $regist_col = [
        'name'      => '名前',
        'price'     => '金額',
        'comment'   => '概要',
        'kamoku_id' => '勘定科目',
        'half_flg'  => '月末精算',
    ];

    /*
     * データ登録
     */ 
    public function regist_constant_cash() : void
    {
        $constant_cashDao = new constant_cash();
        $api_model = new api_model();

        // 自動登録設定されているデータを明細に登録する
        foreach ($constant_cashDao->fetch_all_data() as $num => $data) {
            $post = [];
            foreach ($this->regist_col as $key => $val) {
                $post[$key] = $data->{$key};
            }
            $post['date'] = date('Y-m-01', strtotime(date('Y-m-01').'+1 month'));
            sleep(1); // slack側にスパムだと認定されないように
            $api_model->post_request_send("http://houseBook2.jp/cash/indexexecute", $post);
        }
    }

    /*
     * リマインド用
     */ 
    public function remind_constant_cash_list() : void
    {
        $constant_cashDao = new constant_cash();

        // 合計額を格納するための配列
        foreach ($constant_cashDao->fetch_all_name() as $num => $name_data) {
            $sum[$name_data->name] = 0;
        }

        // 自動登録設定されているデータをまとめる
        $msg = "次月に自動登録されるデータを送信します" . PHP_EOL;
        $msg .= "------------------------------------------------" . PHP_EOL;
        foreach ($constant_cashDao->fetch_all_data() as $num => $data) {
            $msg .= "$data->name | ". number_format($data->price) . " | " . mb_substr($data->comment, 0, 16) . PHP_EOL;
            $sum[$data->name] = $sum[$data->name] + $data->price;
        }
        foreach ($sum as $sum_name => $sum_price) {
            $msg .= PHP_EOL . "合計  : $sum_name  = " . number_format($sum_price);
        }
        
        parent::slack_push_msg($msg);
    }

    /**
     * 月末精算の通知
     * @param int $month
     */
    public function pay_off_notice(int $month) : string
    {
        $cashDao = new cash();

        // 精算登録されたものをユーザごとにまとめる
        $re = [];
        foreach($cashDao->fetch_pay_off($month) as $num => $data) {
            $re[$data->name] = $data->price;
        }
        // 精算対象者、全員分の精算金額をまとめる
        foreach (Config::get('cash_const.pay_off_user') as $user) {
            $user_pay[$user] = array_key_exists($user, $re) ? $re[$user] : 0;
        }
        
        $msg = '◆個人負担金' . PHP_EOL;
        $msg .= 'kabigon: '. number_format($user_pay['kabigon']) . '円' . PHP_EOL;
        $msg .= 'yukihiro: '. number_format($user_pay['yukihiro']) . '円' . PHP_EOL;
        $msg .= PHP_EOL;
        $msg .= '◆精算方法' . PHP_EOL;

        switch (true) {
            case $user_pay['kabigon'] > $user_pay['yukihiro'] :
                $str = 'kabigon に ' . number_format(round($user_pay['kabigon'] - $user_pay['yukihiro'], 0) / 2) . '円を支払いましょう';
                break;
            case $user_pay['kabigon'] < $user_pay['yukihiro'] :
                $str = 'yukihiro に ' . number_format(round($user_pay['yukihiro'] - $user_pay['kabigon'], 0) / 2) . '円を支払いましょう';
                break;
            default :
                $str = '精算は特になしです';
        }

        return $msg . $str;
    }

}
