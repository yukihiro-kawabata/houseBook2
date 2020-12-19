<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Model\cash\cash_batch_model;

use App\Model\slack\slack_push_model;

class cash_pay_off_batch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cash_pay_off';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '月末の精算結果を通知するバッチ';

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
        // 引数に対象月が入っていたら
        $month = date("m", strtotime("-1 month"));

        $cash_batch_model = new cash_batch_model();
        $msg = $cash_batch_model->pay_off_notice($month);

        $msg_body =  "$month 月の精算を行います" . PHP_EOL;
        $msg_body .= "----------------------------------" . PHP_EOL;

        $slack_push_model = new slack_push_model();
        $slack_push_model->push_msg($msg_body . $msg);
    }
}