<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agenciescommission', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('agencyask', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('agencyproperties', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('agencywish', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('authcodes', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('bathrooms', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('bedrooms', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('cancellation', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('channexgroups', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('channexrateidens', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('channexroomidens', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('checkedcalendars', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('contactform', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('countrestoday', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('currency', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('customizeinfo', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('discounts', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('icals', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('livings', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('periods', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('channexperiodsidens', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('pricebedrooms', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('properties', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('reservations', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('channexpropertyidens', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('similar', function (Blueprint $table) {
            $table->timestamps();
        });
        Schema::table('users', function (Blueprint $table) {
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
        //
    }
}
