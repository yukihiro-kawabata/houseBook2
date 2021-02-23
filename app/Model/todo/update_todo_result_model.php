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
            return;
        }

        self::todo_resultDao()
            ->where('id', $todo['todo_result_id'])
            ->update([
                'status' => $param['type'],
            ]);
    }

    /**
     * todo_resultの更新前に、更新対象が合っているか確認する
     * 毎週実行タスクだと、誤って来週分を更新する等を防ぐため
     */
    private function validate(array $data, array $todo) : bool
    {
        return (
            $data['day'] === (int)str_replace('-', '', $todo['todo_day']) // todoの実行日が一致しているか
            && preg_replace('/(\d{2})\:(\d{2})\:(\d{2})/', '$1:$2', $todo['time']) === preg_replace('/.*(\d{2})\:(\d{2})\:(\d{2})/', '$1:$2', $todo['todo_result_created_at']) // todoの実行時間が一致しているか
        );
    }


}
