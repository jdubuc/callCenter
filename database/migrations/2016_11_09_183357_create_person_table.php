<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Person', function (Blueprint $table) {
            $table->increments('id');
            //$table->string('username', 255)->unique();
            $table->string('firstName', 255);
            $table->string('lastName', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('phoneNumber', 255);
            $table->string('idTelegram', 255)->nullable();
            $table->string('twitter', 255)->nullable();
            $table->string('pOperator', 255);
            $table->integer('cedula', 255);
            $table->integer('idAccount')->nullable();
            $table->integer('idPersonCreator')->nullable();
            $table->integer('idPersonModificator')->nullable();
            $table->boolean('confirmed')->default(0);
            $table->string('confirmation_code')->nullable();
            $table->rememberToken();
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
        Schema::drop('Person');
    }
}
