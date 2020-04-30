<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use App\Model\slack\slack_push_model;

class common_model extends Model
{
    
    /**
     * slackにメッセージを送信する
     * @param string $msg 送信メッセージ
     * @return void
     */
    public function slack_push_msg(string $msg = '') : void
    {
        $slack_push_model = new slack_push_model();
        $slack_push_model->push_msg($msg);
    }

}
