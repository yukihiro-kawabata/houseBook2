<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Model\cash\import_mail_model;



class cash_import_mail_batch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:cash_import_mail_batch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '新着メールから家計簿に明細を登録するバッチ';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        require __DIR__ . '/../../../function.php';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $import_mail_model = new import_mail_model();
        $import_mail_model->import_data();
    }
}
