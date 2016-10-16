<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MenuTableSeeder extends Seeder
{
    public function run()
    {
        //delete menu table records
         DB::table('menu')->truncate();
         //insert some dummy records
         DB::table('menu')->insert(
            array(
                array( 'id' => 1,
                      'type_id' => 0,
                      'slug' => 'root',
                      'path' => '',
                      'name' => 'root',
                      'home' => 0,
                      'parent_id' => 0,
                      'component_id' => 0,
                      'depth' => 0,
                      'lft' => 0,
                      'rgt' => 3,
                      'published' => 1
                      ),
                array( 'id' => 2,
                      'type_id' => 1,
                      'slug' => 'home',
                      'path' => '',
                      'name' => 'Home',
                      'home' => 1,
                      'parent_id' => 1,
                      'component_id' => 0,
                      'depth' => 1,
                      'lft' => 1,
                      'rgt' => 2,
                      'published' => 1
                      )
            )
        );
    }
}