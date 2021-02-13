<?php

namespace App\Model\todo;

use App\Model\todo\todo_model;

class view_todo_list_model extends todo_model
{
    /**
     * 一覧で使用するデータをまとめる
     * @return array $re データ
     */ 
    public function view_list() : array
    {
        return [
            'view_week' => self::DAY_OF_WEEKS, // weeks 
            'list'      => $this->fetch_view_todo_list(),
        ];
    }

    /**
     * todoリストで表示するデータをまとめる
     */
    private function fetch_view_todo_list() : array
    {
        // todoに登録されているデータを取得する
        $todo = $this->fetch_todo_data();
        $todo_result = $this->fetch_todo_result_data();
        $todo_result_col = array_flip(self::todo_resultDao()->getColums()) + ['todo_result_status_name' => ''];

        $re = [];
        foreach (get_all_days() as $n => $data) {
            $tmp         = $data;
            $tmp['todo'] = [];
            $day         = $data['day'];
            $week        = $data['week'];

            if (array_key_exists($day, $todo)) {
                $result = $todo_result_col; // todo_resultテーブルのカラムを配列のkeyに補填
                
                foreach ($todo[$day] as $todo_time => $todo_data) {
                    // $jug_key = $n . $todo[$day]['time'];
                    $jug_key = $n . $todo_time;
                    if (array_key_exists($jug_key, $todo_result)) {
                        $result = $todo_result[$jug_key];
                    }
                    $tmp['todo'][] =  array_merge($todo[$day][$todo_time], $result);
                }
            }

            if (array_key_exists($week, $todo)) {
                // 定期的なToDoの場合、過去のtodo_resultのステータスが未来に影響を与えるので調整する
                $result = $todo_result_col; // todo_resultテーブルのカラムを配列のkeyに補填
                $jug_key = $n . $todo[$week]['time'];
                if (array_key_exists($jug_key, $todo_result)) {
                    $result = $todo_result[$jug_key];
                }
                $tmp['todo'][] =  array_merge($todo[$week], $result);
            }

            if (!empty($tmp['todo'])) {
                $re[] = $tmp;
            }
        }

        return $re;
    }

    /** todoに登録されているデータをまとめる */
    private function fetch_todo_data() : array
    {
        $re = [];
        foreach (self::todoDao()->fetch_all_date('time', 'ASC') as $n => $data) {
            if (! is_null($data['day'])) {
                $re[preg_replace('/\-/', '', $data['day'])][$data['time']]  = $data; // ex). 2020-12-01 ---> 20201201 をkeyとする
            }

            if (! is_null($data['week'])) {
                $re[$data['week']] = $data;
            }
        }
        return $re;
    }

    /** todo_resultに登録されているデータを取得する */
    private function fetch_todo_result_data() : array
    {
        $re = [];
        foreach (self::todo_resultDao()->fetch_all_date() as $n => $data) {

            if ($data['status'] === 9) {
                $data['todo_fixed_flg'] = 1;
            }

            $re[str_replace('-', '', $data['todo_day']) . $data['todo_time']] = $data;
        }
        return $re;
    }

}
