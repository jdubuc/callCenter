<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonPersonGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PersonPersonGroup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idPerson')->unsigned();
            $table->foreign('idPerson')->references('id')->on('Person');

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
          Schema::drop('PersonPersonGroup');
    }
}
