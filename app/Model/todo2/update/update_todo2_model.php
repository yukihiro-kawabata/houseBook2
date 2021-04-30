<?php

namespace App\Model\todo2\update;

use App\Model\todo2\todo2_model;

class update_todo2_model extends todo2_model
{
    /**
     * todo_resultのデータを更新する
     */
    public function update_todo_result(array $param) : void
    {
        $id     = $param['id'];
        $status = $param['status'];

        self::todo2Dao()
            ->where('id', $id)
            ->update([
                'status' => $status,
            ]);

        // Slack通知する
        $msg  = 'ToDoが"'. self::TODO_STATUS[$status] . '"に変更されました。' . PHP_EOL;
        $msg .= "----------------------------------" . PHP_EOL;
        $msg .= "タイトル：" . $param['title'] . PHP_EOL;
        $msg .= "実行日時：" . $param['day'] . PHP_EOL;
        
        self::slack_push_model()->push_msg($msg);
    }
}
