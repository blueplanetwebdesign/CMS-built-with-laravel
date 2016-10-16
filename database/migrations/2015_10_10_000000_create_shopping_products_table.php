<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShoppingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_products', function(Blueprint $table)
  {
      $table->increments('id');
      $table->string('name', 255);
      $table->float('price');
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
        Schema::drop('shopping_products');
    }
}
