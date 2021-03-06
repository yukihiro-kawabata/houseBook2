<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodoResultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo_result', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('todo_id')->comment('todoのID');
            $table->integer('status')->default(1)->comment('1:未完了 2:破棄 9:完了');
            $table->date('todo_day')->nullable()->comment('ToDo実行日');
            $table->time('todo_time')->comment('時間');
            $table->integer('remind_total_count')->default(0)->comment('リマインド合計回数');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todo_result');
    }
}
