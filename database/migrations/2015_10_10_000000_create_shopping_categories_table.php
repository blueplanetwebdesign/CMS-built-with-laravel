<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_categories', function(Blueprint $table)
  {
      $table->increments('id');

      $table->string('name', 255);      
      $table->text('description');
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
        Schema::drop('shopping_categories');
    }
}
