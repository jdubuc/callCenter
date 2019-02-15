<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('PersonGroup', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255); 
            $table->integer('idPersonCreator');
            $table->integer('idAccount');
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
          Schema::drop('PersonGroup');
    }
}
