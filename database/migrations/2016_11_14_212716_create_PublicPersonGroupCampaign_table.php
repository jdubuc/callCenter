<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicPersonGroupCampaignTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PublicPersonGroupCampaign', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idCampaign')->unsigned();
            $table->foreign('idCampaign')->references('id')->on('Campaign');

            $table->integer('idPublicPersonGroup')->unsigned();
            $table->foreign('idPublicPersonGroup')->references('id')->on('PublicPersonGroup');

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
        Schema::drop('PublicPersonGroupCampaign');
    }
}
