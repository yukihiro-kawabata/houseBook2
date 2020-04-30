<?php

namespace App\Model\slack;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Config;

class slack_push_model extends Model
{
    /*
     * slackのweb_logチャンネルにメッセージを送信する
     */ 
    public function push_msg(string $msg = '') : void
    {

        $url = Config::get('slack.api_url');

        // 送信メッセージ。
        $data['text'] = $msg;
        
        // curlを初期化
        $ch = curl_init();

        // 設定!
        curl_setopt($ch, CURLOPT_URL, $url); // 送り先
        curl_setopt($ch, CURLOPT_POST, true); // POSTですS
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // 送信データ
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 実行結果取得の設定

        curl_exec($ch);
        curl_close($ch);
    }

}
