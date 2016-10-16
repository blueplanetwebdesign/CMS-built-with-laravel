<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShoppingProductCategoriesTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('shopping_product_categories')->truncate();
       }
 
}