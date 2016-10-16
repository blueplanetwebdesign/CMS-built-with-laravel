<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
         DB::table('permissions')->truncate();
         //insert some dummy records
         DB::table('permissions')->insert(
            array(
                array( 'id' => 1, 'name' => 'Access Admin Panel', 'slug' => 'access-admin-panel' ),
                array( 'id' => 2, 'name' => 'Access User Manager', 'slug' => 'access-user-manager' )
            )
        );
    }
}