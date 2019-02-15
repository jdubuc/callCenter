<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignPersonGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('CampaignPersonGroup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idCampaign')->unsigned();
            $table->foreign('idCampaign')->references('id')->on('Campaign');

            $table->integer('idPersonGroup')->unsigned();
            $table->foreign('idPersonGroup')->references('id')->on('PersonGroup');

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
        Schema::drop('CampaignPersonGroup');
    }
}
