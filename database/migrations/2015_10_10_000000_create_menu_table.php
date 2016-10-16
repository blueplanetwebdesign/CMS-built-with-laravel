<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('type_id');
            $table->string('slug', 255);
            $table->string('path', 255);
            $table->string('name', 255);
            $table->boolean('home')->default(0);
            $table->integer('parent_id');
            $table->integer('component_id');
            $table->integer('depth');
            $table->integer('lft');
            $table->integer('rgt');
            $table->boolean('published')->default(1);
      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu');
    }
}
