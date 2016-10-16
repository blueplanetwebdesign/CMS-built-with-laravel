<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ContentCategoriesTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('content_categories')->truncate();
         
         //factory(Bpwd\Shopping\Models\Shopping\Product::class, 20)->create();
         //insert some dummy records
         DB::table('content_categories')->insert(array(
             array('name'=>'Cat one', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat two', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat three', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
             array('name'=>'Cat four', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'),  'updated_at' => Carbon::now()->format('Y-m-d H:i:s')),
          ));
       }
 
}