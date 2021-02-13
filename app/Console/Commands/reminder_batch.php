<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\db\todo;
use App\db\todo_result;

use App\Model\slack\slack_push_model;

class reminder_batch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインドを通知するバッチ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private static function todoDao() : todo
    {
        return new todo();
    }

    private static function todoResultDao() : todo_result
    {
        return new todo_result();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = date('H:i');

        $slack_push_model = new slack_push_model();
        
        foreach(self::todoDao()->fetch_todo_taget_data($time) as $n => $data) {
            $msg  = "";
            $msg .= "== 下記の予定時間です ==" . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
            $msg .= "$data->title" . PHP_EOL;
            $msg .= "$data->text" . PHP_EOL;
    
            $slack_push_model->push_msg($msg);

            // todo_resultにデータ登録する
            $todoResultDao = self::todoResultDao();
            $todoResultDao->todo_id  = $data->id;
            $todoResultDao->todo_day = date('Y-m-d');
            $todoResultDao->todo_time = $data->time;
            $todoResultDao->save();
        }
    }
}
