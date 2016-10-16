<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenuTypesTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
         DB::table('menu_types')->truncate();
         //insert some dummy records
         DB::table('menu_types')->insert(
            array(
                array( 'id' => 1, 'name' => 'Top Menu' ),
                array( 'id' => 2, 'name' => 'Footer Menu' )
            )
        );
    }
}