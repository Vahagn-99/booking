<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannexratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channexrates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('rate_iden');
            $table->integer('property_iden');
            $table->integer('room_iden');
            $table->string('channel_iden');
            $table->dateTime('changed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channexrates');
    }
}
