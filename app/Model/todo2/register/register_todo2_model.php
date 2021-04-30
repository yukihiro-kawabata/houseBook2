<?php

namespace App\Model\todo2\register;

use App\Model\todo2\todo2_model;

class register_todo2_model extends todo2_model
{
    private $created_at_time;
    private $todo_key;

    const TODO_YEAR_FUTURE = 3; // todoの繰り返しはxxx年後まで

    public function __construct()
    {
        $this->created_at_time = now();
        $this->todo_key        = random(16);
    }

    /** DB登録用にデータを整形する */
    private function create_data(array $request) : array
    {
        // 毎週の繰り返しで登録であれば
        if (! is_null($week = $request['week'])) {
            $data = [];
            foreach (get_all_days(self::TODO_YEAR_FUTURE) as $day_int => $detail) {
                $day = preg_replace('/(\d{4})(\d{2})(\d{2})/', '$1-$2-$3', $day_int); // 20200401 --> 2020-04-01

                // 今日より過去は処理しない
                if ((int)date('Ymd') > (int)$day_int) {
                    continue;
                }

                // 曜日が一致すればデータ生成
                if ($week === $detail['week']) {
                    $data[] = $this->add_column_data(array_merge($request, ['day' => $day]));
                }
            }
        } else {
            $data[0] = array_merge($this->add_column_data($request), ['week' => date('w', strtotime($request['day']))]);
        }

        return $data;
    }

    /** 登録するデータを補填する */
    private function add_column_data(array $data) : array
    {
        return $data + [
            'todo_key'   => $this->todo_key, 
            'created_at' => $this->created_at_time,
        ];
    }

    /** slack通知 */
    private function notice_slack(array $param) : void
    {
        // Slack通知する
        $msg  = "ToDoが追加されました。" . PHP_EOL;
        $msg .= "----------------------------------" . PHP_EOL;
        $msg .= "タイトル：" . $param['title'] . PHP_EOL;

        if (! is_null($week = $param['week'])) {
            $msg .= "繰り返し登録の曜日：" . self::DAY_OF_WEEKS[$week] . " " . $param['time'] . PHP_EOL;
        } else {
            $msg .= "実行日時：" . $param['day'] . " " . $param['time'] . PHP_EOL;
        }
    
        self::slack_push_model()->push_msg($msg);
    }

    /**
     * データをToDoに登録する
     * @param array $request
     */
    public function register_data(array $request) : void
    {
        $data = $this->create_data($request);

        self::todo2Dao()->insert($data);
        $this->notice_slack($request);
    }

}