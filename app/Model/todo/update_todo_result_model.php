<?php

namespace App\Model\todo;

use App\Model\todo\todo_model;

class update_todo_result_model extends todo_model
{
    /**
     * todo_resultのデータを更新する
     */
    public function update_todo_result(array $param) : void
    {
        // 画面からjson形式で送信されるので配列にする
        $data = json_decode($param['json'], true);
        $todo = $data['todo'][$param['todo_num']];

        if (! $this->validate($data, $todo)) {

            // 未来の単発ToDoは物理的に削除するようにする
            if ((string)array_flip(self::TODO_RESULT_STATUS)['削除'] === $param['type'] && is_null($todo['week'])) {
                self::todoDao()
                    ->where([
                        ['title', $todo['title']],
                        ['day', $todo['day']],
                        ['time', $todo['time']],
                    ])
                    ->delete();
            }
            return;
        }

        self::todo_resultDao()
            ->where('id', $todo['todo_result_id'])
            ->update([
                'status' => $param['type'],
            ]);

            // Slack通知する
            $msg  = "ToDoが変更されました。" . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
            $msg .= "タイトル：" . $todo['title'] . PHP_EOL;
            $msg .= "実行日時：" . $todo['day'] . " " . $todo['time'] . PHP_EOL;
            $msg .= "ステータス：" . self::TODO_RESULT_STATUS[$param['type']]   .PHP_EOL;
            
            self::slack_push_model()->push_msg($msg);
    }

    /**
     * todo_resultの更新前に、更新対象が合っているか確認する
     * 毎週実行タスクだと、誤って来週分を更新する等を防ぐため
     */
    private function validate(array $data, array $todo) : bool
    {
        // まだToDo実行日に達していないとデータが出来ていないので
        // todo_result_id は無の状態で来る
        if (!array_key_exists('todo_result_id', $todo)) {
            return false;
        }

        return (
            $data['day'] >= (int)str_replace('-', '', $todo['todo_day']) // todoの実行日が対象の日付より過去なのか
            && preg_replace('/(\d{2})\:(\d{2})\:(\d{2})/', '$1:$2', $todo['time']) === preg_replace('/.*(\d{2})\:(\d{2})\:(\d{2})/', '$1:$2', $todo['todo_result_created_at']) // todoの実行時間が一致しているか
        );
    }


}
