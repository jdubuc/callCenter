<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Answer', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data', 400);
            $table->integer('idQuestion')->unsigned();
            $table->foreign('idQuestion')->references('id')->on('Question');
            $table->integer('idMessageSend')->unsigned();
            $table->foreign('idMessageSend')->references('id')->on('MessageSend');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('Answer');
    }
}
