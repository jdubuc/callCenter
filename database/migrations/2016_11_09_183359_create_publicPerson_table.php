<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicPersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PublicPerson', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstName', 255);
            $table->string('lastName', 255);
            $table->string('phoneNumber', 255);
            $table->string('email', 255);
            $table->string('cellPhone', 255);
            //$table->integer('telegramId');
            $table->string('cedula', 255);
            $table->string('twitter', 255)->nullable();
            $table->string('identification', 255)->nullable();
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
        Schema::drop('PublicPerson');
    }
}
