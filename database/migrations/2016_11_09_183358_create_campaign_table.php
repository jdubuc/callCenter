<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Campaign', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('campaignMessage', 255);
            $table->string('type', 255)->nullable();
            $table->boolean('active');

            $table->dateTime('dateTimeStart');
            $table->dateTime('dateTimeEnd');
            $table->integer('idAccount');
            

            $table->integer('idPersonCreator')->unsigned();
            $table->foreign('idPersonCreator')->references('id')->on('Person');
            
            $table->integer('idPersonModificator')->unsigned();
            $table->foreign('idPersonModificator')->references('id')->on('Person');
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
        Schema::drop('Campaign');
    }
}
