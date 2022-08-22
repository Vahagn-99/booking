<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannexpropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channexproperties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('group_iden');
            $table->integer('property_iden');
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
        Schema::dropIfExists('channexproperties');
    }
}
