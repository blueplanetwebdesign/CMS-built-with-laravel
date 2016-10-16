<?php

Route::group([
    'prefix' => 'admin/shopping/',
    'namespace'  => 'bpwd\shopping\Admin\Controllers',
    'middleware' => ['web', 'auth.admin', 'acl:access-admin-panel']
], function(){

    Route::post('products/publish', 'ProductController@publish');
    Route::post('products/unpublish', 'ProductController@unpublish');
    Route::get('products/{id}/publish', 'ProductController@publish');
    Route::get('products/{id}/unpublish', 'ProductController@unpublish');
    Route::post('products/delete', 'ProductController@delete');
    Route::post('products/list-update', 'ProductController@listUpdate');
    Route::resource('products', 'ProductController');

    Route::post('categories/publish', 'CategoryController@publish');
    Route::post('categories/unpublish', 'CategoryController@unpublish');
    Route::get('categories/{id}/publish', 'CategoryController@publish');
    Route::get('categories/{id}/unpublish', 'CategoryController@unpublish');
    Route::post('categories/delete', 'CategoryController@delete');
    Route::post('categories/list-update', 'CategoryController@listUpdate');
    Route::resource('categories', 'CategoryController');

    Route::get('orders', function(){
        echo 'Coming soon';
    });
    
    Route::get('settings', function(){
        echo 'Coming soon';
    });
});