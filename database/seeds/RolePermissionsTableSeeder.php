<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class RolePermissionsTableSeeder extends Seeder
{
    public function run()
    {
        //delete users table records
         DB::table('role_permissions')->truncate();
         //insert some dummy records
         DB::table('role_permissions')->insert(
            array(
                array( 'role_id' => 1, 'permission_id' => '1' ), // Super admin = access-admin-panel
                array( 'role_id' => 1, 'permission_id' => '2' ), // Super admin = access-user-manager
                array( 'role_id' => 2, 'permission_id' => '1' ) // Admin = access-admin-panel
            )
        );
    }
}