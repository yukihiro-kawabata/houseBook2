<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\db\remind;

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time = date('H:i');

        $slack_push_model = new slack_push_model();
        $remindDao = new remind();

        foreach($remindDao->fetch_remind_taget_data($time) as $n => $data) {
            $msg  = "";
            $msg .= "== リマインド ==" . PHP_EOL;
            $msg .= "$data->title" . PHP_EOL;
            $msg .= "----------------------------------" . PHP_EOL;
            $msg .= "$data->text" . PHP_EOL;
    
            $slack_push_model->push_msg($msg);
        }
    }
}
