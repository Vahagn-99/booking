<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RentalTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rental_types')->insert(['title' => 'Single']);
        DB::table('rental_types')->insert(['title' => 'Double']);
        DB::table('rental_types')->insert(['title' => 'Twin']);
        DB::table('rental_types')->insert(['title' => 'Twin/Double']);
        DB::table('rental_types')->insert(['title' => 'Triple']);
        DB::table('rental_types')->insert(['title' => 'Quadruple']);
        DB::table('rental_types')->insert(['title' => 'Family']);
        DB::table('rental_types')->insert(['title' => 'Suite']);
        DB::table('rental_types')->insert(['title' => 'Studio']);
        DB::table('rental_types')->insert(['title' => 'Dormitory room']);
        DB::table('rental_types')->insert(['title' => 'Bed in Dormitory']);
        DB::table('rental_types')->insert(['title' => 'Chalet']);
        DB::table('rental_types')->insert(['title' => 'Villa']);
        DB::table('rental_types')->insert(['title' => 'Holiday Home']);
        DB::table('rental_types')->insert(['title' => 'Mobile Home']);
        DB::table('rental_types')->insert(['title' => 'Tent']);
        DB::table('rental_types')->insert(['title' => 'Apartment']);
        DB::table('rental_types')->insert(['title' => 'Bungalow']);
        DB::table('rental_types')->insert(['title' => 'Cottage']);
    }
}
