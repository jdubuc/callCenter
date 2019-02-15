<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PersonCampaign', function (Blueprint $table) {
            $table->increments('id');
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
        Schema::drop('PersonCampaign');
    }
}
