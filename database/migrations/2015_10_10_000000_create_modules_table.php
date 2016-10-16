<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_modules', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name', 255);
            $table->string('module_slug', 255);
            $table->string('position_slug', 255);
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
        Schema::drop('modules_modules');
    }
}
