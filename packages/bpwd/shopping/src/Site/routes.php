<?php

Route::group([
    'prefix' => 'shopping',
    'namespace'  => 'bpwd\shopping\Site\Controllers',
    'middleware' => ['web']
], function(){

    Route::get('/', 'CategoryController@All');

});