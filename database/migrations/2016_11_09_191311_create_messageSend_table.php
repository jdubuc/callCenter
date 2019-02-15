<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageSendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('MessageSend', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('message', 255);
            $table->boolean('hang');
            $table->boolean('answer');
            $table->integer('tries');
            $table->integer('duration');

            $table->dateTime('dateTimeStart');
            $table->dateTime('dateTimeEnd');
            $table->dateTime('dateTimeModification');

            $table->integer('idPublicPerson')->unsigned();
            $table->foreign('idPublicPerson')->references('id')->on('PublicPerson');

            $table->integer('idCampaign')->unsigned();
            $table->foreign('idCampaign')->references('id')->on('Campaign');

            $table->integer('idPerson')->unsigned();
            $table->foreign('idPerson')->references('id')->on('Person');
            
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
         Schema::drop('MessageSend');
    }
}
