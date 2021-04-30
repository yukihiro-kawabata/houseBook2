<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\db\todo2;

use App\Model\slack\slack_push_model;

class reminder_todo_batch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder_todo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ToDoの未着手のものをリマインドを通知するバッチ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private static function todo2Dao() : todo2
    {
        return new todo2();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $slack_push_model = new slack_push_model();
        
        for ($i = 9; $i <= 21; $i++) {
            $time[sprintf('%02d', $i).':00'] = $i;
        }

        // if (! array_key_exists(date('H:i'), $time)) {
        //     exit('リマインド時間の対象外です');
        // }

        // ToDoの未着手のものを取得する
        $today_todo = self::todo2Dao()->fetch_todo_not_yet();

        foreach($today_todo as $n => $data) {
            $msg  = "";
            $msg .= "下記を忘れてませんか？" . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
            $msg .= $data['title'] . PHP_EOL;
            $msg .= $data['text'] . PHP_EOL;
    
            $slack_push_model->push_msg($msg);

            // 大量のりマインドを防ぐため
            if ($n >= 10) {
                break;
            }

            // todo_resultにデータ登録する
            self::todo2Dao()
            ->where('id', $data['id'])
            ->update([
                'remind_total_count' => $data['remind_total_count'] + 1, // リマインド回数を加算
            ]);
        }
    }
}
