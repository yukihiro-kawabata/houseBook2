<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\db\todo;
use App\db\todo_result;

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
        $slack_push_model = new slack_push_model();
        
        foreach(self::todoResultDao()->fetch_todo_not_yet() as $n => $data) {
            $msg  = "";
            $msg .= "== ToDo(未) ==" . PHP_EOL;
            $msg .= $data['title'] . PHP_EOL;
            $msg .= $data['text'] . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
    
            $slack_push_model->push_msg($msg);

            // todo_resultにデータ登録する
            self::todoResultDao()
                ->where('id', $data['todo_result_id'])
                ->update([
                    'remind_total_count' => $data['remind_total_count'] + 1, // リマインド回数を加算
                ]);
        }
    }
}
