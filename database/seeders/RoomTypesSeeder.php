<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RoomTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_types')->insert(['name' => 'Additional single bed']);
        DB::table('room_types')->insert(['name' => 'Additional sofa bed']);
        DB::table('room_types')->insert(['name' => '1 bedroom', 'count_of_rooms' => 1]);
        for ($i=2; $i < 15; $i++) {
            DB::table('room_types')->insert(['name' => $i.' bedrooms', 'count_of_rooms' => $i]);
        }
    }
}
