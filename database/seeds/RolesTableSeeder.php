<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
         DB::table('roles')->truncate();
         //insert some dummy records
         DB::table('roles')->insert(
            array(
                array( 'id' => 1, 'name' => 'Super Admin', 'slug' => 'super-admin' ),
                array( 'id' => 2, 'name' => 'Admin', 'slug' => 'admin' ),
                array( 'id' => 3, 'name' => 'Registered', 'slug' => 'registered' )
            )
        );
    }
}