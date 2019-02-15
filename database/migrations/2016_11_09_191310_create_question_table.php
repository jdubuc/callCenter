<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Question', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data', 255);
            $table->string('option', 255);
            $table->integer('order');
            $table->text('QuestionDestinatary');
            $table->integer('idQuestionCondition');
            $table->integer('idPersonCreator');
            $table->integer('idPersonModificator');
            $table->boolean('allowNotAnswer');

            $table->integer('idCampaign')->unsigned();
            $table->foreign('idCampaign')->references('id')->on('Campaign');

            $table->integer('idQuestionType')->unsigned();
            $table->foreign('idQuestionType')->references('id')->on('QuestionType');
            
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
        Schema::drop('Question');
    }
}
