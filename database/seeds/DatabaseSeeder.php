<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(UserRolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolePermissionsTableSeeder::class);
        
        $this->call(ShoppingCategoriesTableSeeder::class);
        $this->call(ShoppingProductCategoriesTableSeeder::class);
        $this->call(ShoppingProductsTableSeeder::class);
        
        $this->call(ContentCategoriesTableSeeder::class);
        
        $this->call(MenuTypesTableSeeder::class);
        $this->call(MenuTableSeeder::class);
    }
}