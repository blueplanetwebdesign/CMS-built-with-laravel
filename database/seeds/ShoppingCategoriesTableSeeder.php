<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShoppingCategoriesTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('shopping_categories')->truncate();
         
         //factory(Bpwd\Shopping\Models\Shopping\Product::class, 20)->create();
         //insert some dummy records
         DB::table('shopping_categories')->insert(array(
             array('name'=>'Cat one','description'=>'Cat one description', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat two','description'=>'Cat two description', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat three','description'=>'Cat three description', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat four','description'=>'Cat four description', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
          ));
       }
 
}