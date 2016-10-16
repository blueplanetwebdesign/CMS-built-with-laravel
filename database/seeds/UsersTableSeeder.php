<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
        DB::table('users')->truncate();
        //insert some dummy records
        DB::table('users')->insert(
            array(
                array('id' => 1,
                    'first_name' => 'Super Admin',
                    'last_name' => 'Super Admin',
                    'email' => 'super-admin@blueplanetwebdesign.co.uk',
                    'password' => bcrypt('super-admin'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ),
                array('id' => 2,
                    'first_name' => 'Admin',
                    'last_name' => 'Admin',
                    'email' => 'admin@blueplanetwebdesign.co.uk',
                    'password' => bcrypt('admin'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                ),
                array('id' => 3,
                    'first_name' => 'Registered',
                    'last_name' => 'Registered',
                    'email' => 'andrew3@blueplanetwebdesign.co.uk',
                    'password' => bcrypt('registered'),
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                )
            )
        );
    }
}