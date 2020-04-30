<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKamokuMstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kamoku_mst', function (Blueprint $table) {
            $table->bigIncrements('kamoku_id')->comment('科目ID');
            $table->string('kamoku')->comment('末端科目');
            $table->string('kamoku_sum')->comment('集計科目');
            $table->integer('amount_flg')->default(2)->comment('1:収入, 2:支出');
            $table->integer('priority_flg')->default(0)->comment('表示の優先度、数が大きいほど優先');
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
        Schema::dropIfExists('kamoku_mst');
    }
}
