<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('タイトル');
            $table->string('text')->nullable()->comment('内容');
            $table->date('day')->nullable()->comment('日付');
            $table->time('time')->comment('時間');
            $table->integer('week')->nullable()->comment('日:0  月:1  火:2  水:3  木:4  金:5  土:6');
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
        Schema::dropIfExists('remind');
    }
}
