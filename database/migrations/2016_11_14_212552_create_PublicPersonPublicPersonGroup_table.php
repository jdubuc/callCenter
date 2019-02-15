<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicPersonPublicPersonGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('PublicPersonPublicPersonGroup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idPublicPerson')->unsigned();
            $table->foreign('idPublicPerson')->references('id')->on('PublicPerson');

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
        Schema::drop('PublicPersonPublicPersonGroup');
    }
}
