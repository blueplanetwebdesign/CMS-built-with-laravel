<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ShoppingProductsTableSeeder extends Seeder {
 
       public function run()
       {
         //delete users table records
         DB::table('shopping_products')->truncate();
         
         factory(Bpwd\Shopping\Admin\Models\Product::class, 20)->create()->each(function($product) {
            
            $product->categories()->sync(
                Bpwd\Shopping\Admin\Models\Category::all()->random(3)
            );
             
         });
       }
 
}