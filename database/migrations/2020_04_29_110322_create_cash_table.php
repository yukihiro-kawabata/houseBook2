<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable()->comment('名前');
            $table->date('date')->comment('対象日付');
            $table->integer('price')->comment('金額');
            $table->integer('kamoku_id')->comment('科目ID。kamoku_mstのID');
            $table->string('tag')->nullable()->comment('勘定科目');
            $table->string('comment')->nullable()->comment('概要');
            $table->text('detail')->nullable()->comment('詳細内容');
            $table->integer('priceFlg')->default(2)->comment('1:収入, 2:支出');
            $table->boolean('sumFlg')->nullable()->comment('0:集計対象外,1:集計対象');
            $table->boolean('delete_flg')->default(0)->comment('0:存在,1:削除');
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
        Schema::dropIfExists('cash');
    }
}
