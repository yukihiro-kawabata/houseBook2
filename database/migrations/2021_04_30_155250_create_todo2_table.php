<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTodo2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo2', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('タイトル');
            $table->string('text')->nullable()->comment('内容');
            $table->integer('status')->default(1)->comment('1:未完了 2:破棄 9:完了');
            $table->time('time')->comment('時間');
            $table->date('day')->nullable()->comment('日付');
            $table->integer('week')->nullable()->comment('日:0  月:1  火:2  水:3  木:4  金:5  土:6');
            $table->integer('remind_total_count')->default(0)->comment('リマインド合計回数');
            $table->string('todo_key');
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
        Schema::dropIfExists('todo2');
    }
}
