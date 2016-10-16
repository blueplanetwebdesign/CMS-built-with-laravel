<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UserRolesTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
         DB::table('user_roles')->truncate();
         //insert some dummy records
         DB::table('user_roles')->insert(
            array(
                array( 'user_id' => 1, 'role_id' => '1' ), // Super Admin
                array( 'user_id' => 2, 'role_id' => '2' ) // Admin
            )
        );
    }
}