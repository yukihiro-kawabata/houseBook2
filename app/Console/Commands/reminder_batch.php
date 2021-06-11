<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\db\todo2;

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
        $time = date('H:i');

        $slack_push_model = new slack_push_model();
        
        foreach(self::todo2Dao()->fetch_todo_taget_data($time) as $n => $data) {
            $msg  = "";
            $msg .= "下記の予定時間です" . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
            $msg .= "$data->title" . PHP_EOL;
            $msg .= "$data->text" . PHP_EOL;
            $msg .= '↓↓完了にする↓↓' . PHP_EOL;
            $msg .= "http://192.168.10.109/todo/result/updateexecute?id=".$data->id."&title=".$data->title."&status=9&day=".$data->day . PHP_EOL;
    
            $slack_push_model->push_msg($msg);
        }
    }
}
