<?php

Route::group([
    'prefix' => 'admin/menus/',
    'namespace'  => 'bpwd\menus\Controllers',
    'middleware' => ['web', 'auth.admin', 'acl:access-admin-panel']
], function(){

    Route::post('menu/publish', 'MenuController@publish');
    Route::post('menu/unpublish', 'MenuController@unpublish');
    Route::get('menu/{id}/publish', 'MenuController@publish');
    Route::get('menu/{id}/unpublish', 'MenuController@unpublish');
    Route::post('menu/delete', 'MenuController@delete');
    Route::post('menu/list-update', 'MenuController@listUpdate');
    Route::post('menu/rebuild', 'MenuController@rebuild');
    Route::resource('menu', 'MenuController');

    Route::post('type/delete', 'TypeController@delete');
    Route::post('type/list-update', 'TypeController@listUpdate');
    Route::resource('type', 'TypeController');

});