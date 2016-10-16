<?php

Route::group([
    'prefix' => 'admin/content/',
    'namespace'  => 'bpwd\content\Controllers',
    'middleware' => ['web', 'auth.admin', 'acl:access-admin-panel']
], function(){

    Route::post('content/publish', 'ContentController@publish');
    Route::post('content/unpublish', 'ContentController@unpublish');
    Route::get('content/{id}/publish', 'ContentController@publish');
    Route::get('content/{id}/unpublish', 'ContentController@unpublish');
    Route::post('content/delete', 'ContentController@delete');
    Route::post('content/list-update', 'ContentController@listUpdate');
    Route::get('content/steps', 'ContentController@steps');
    Route::resource('content', 'ContentController');

    Route::post('categories/publish', 'CategoryController@publish');
    Route::post('categories/unpublish', 'CategoryController@unpublish');
    Route::get('categories/{id}/publish', 'CategoryController@publish');
    Route::get('categories/{id}/unpublish', 'CategoryController@unpublish');
    Route::post('categories/delete', 'CategoryController@delete');
    Route::post('categories/list-update', 'CategoryController@listUpdate');
    Route::resource('categories', 'CategoryController');

    Route::post('tags/publish', 'TagController@publish');
    Route::post('tags/unpublish', 'TagController@unpublish');
    Route::get('tags/{id}/publish', 'TagController@publish');
    Route::get('tags/{id}/unpublish', 'TagController@unpublish');
    Route::post('tags/delete', 'TagController@delete');
    Route::post('tags/list-update', 'TagController@listUpdate');
    //Route::resource('tags', 'TagController');
    Route::get('tags', function(){
        echo 'Coming soon';
    });
});