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

        $re = [];
        foreach (get_all_days() as $n => $data) {
            $tmp         = $data;
            $tmp['todo'] = [];
            $day         = $data['day'];
            $week        = $data['week'];

            if (array_key_exists($day, $todo)) {
                $tmp['todo'][] = $todo[$day];
            }

            if (array_key_exists($week, $todo)) {
                $tmp['todo'][] =  $todo[$week];
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
                $re[preg_replace('/\-/', '', $data['day'])]  = $data; // ex). 2020-12-01 ---> 20201201 をkeyとする
            }

            if (! is_null($data['week'])) {
                $re[$data['week']] = $data;
            }
        }
        return $re;
    }

}
