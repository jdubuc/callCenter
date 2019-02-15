<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicPersonGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PublicPersonGroup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255); 

            $table->integer('idPersonCreator')->unsigned();
            $table->foreign('idPersonCreator')->references('id')->on('Person');
            $table->string('idAccount', 255); 
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
        Schema::drop('PublicPersonGroup');
    }
}
