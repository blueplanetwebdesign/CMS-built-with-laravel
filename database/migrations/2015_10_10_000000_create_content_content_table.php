<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_content', function(Blueprint $table)
        {
            $table->increments('id');
      
            $table->string('name', 255);
            $table->string('page_title', 255);
            $table->string('meta_description', 255);
            $table->string('slug', 255);  
            $table->text('fields');
            $table->integer('layout_id');
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
        Schema::drop('pages_pages');
    }
}
