<?php

namespace App\Model\cash;

use Illuminate\Database\Eloquent\Model;

use App\db\cash;
use App\db\constant_cash;

use  App\Model\cash\cashModel;
use App\Model\api\api_model;
use App\Model\slack\slack_push_model;

class cash_batch_model extends cashModel
{
    // 登録する項目、※dateカラムは別で格納している
    private $regist_col = [
        'name'      => '名前',
        'price'     => '金額',
        'comment'   => '概要',
        'kamoku_id' => '勘定科目',
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
            $post['date'] = date('Y-m-d');
            sleep(1000); // slack側にスパムだと認定されないように
            $api_model->post_request_send("http://myapitool.jp/cash/indexexecute", $post);
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
        
        $slack_push_model = new slack_push_model();
        $slack_push_model->push_msg($msg);
    }

}
