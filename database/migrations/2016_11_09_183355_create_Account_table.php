<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('Account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Name', 255);
            $table->string('contactName', 255);
            $table->string('email', 255)->unique();
            $table->string('rif', 255);
            $table->string('address', 255);
            $table->string('idArrangement', 255)->nullable();
            //$table->dropForeign('accountIdstatusForeign');
            $table->integer('idStatus')->unsigned();
            $table->foreign('idStatus')->references('id')->on('AccountStatus');
            
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
        Schema::drop('Account');
    }
}
