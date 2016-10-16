<?php

Route::group([
    'prefix' => 'admin/',
    'namespace'  => 'bpwd\admin\Controllers\Users',
    'middleware' => ['web', 'auth.admin', 'acl:access-admin-panel']
], function(){


    Route::get('/', function(){
        return view('admin::Pages/Dashboard');
    });

    Route::group([
        'prefix' => 'users',
        'middleware' => ['web', 'auth.admin', 'acl:access-user-manager']
    ], function() {
        Route::post('users/publish', 'UserController@publish');
        Route::post('users/unpublish', 'UserController@unpublish');
        Route::get('users/{id}/publish', 'UserController@publish');
        Route::get('users/{id}/unpublish', 'UserController@unpublish');
        Route::post('users/delete', 'UserController@delete');
        Route::post('users/list-update', 'UserController@listUpdate');
        Route::resource('users', 'UserController');

        Route::post('roles/publish', 'RoleController@publish');
        Route::post('roles/unpublish', 'RoleController@unpublish');
        Route::get('roles/{id}/publish', 'RoleController@publish');
        Route::get('roles/{id}/unpublish', 'RoleController@unpublish');
        Route::post('roles/delete', 'RoleController@delete');
        Route::post('roles/list-update', 'RoleController@listUpdate');
        Route::resource('roles', 'RoleController');

        Route::post('permissions/publish', 'PermissionController@publish');
        Route::post('permissions/unpublish', 'PermissionController@unpublish');
        Route::get('permissions/{id}/publish', 'PermissionController@publish');
        Route::get('permissions/{id}/unpublish', 'PermissionController@unpublish');
        Route::post('permissions/delete', 'PermissionController@delete');
        Route::post('permissions/list-update', 'PermissionController@listUpdate');
        Route::resource('permissions', 'PermissionController');
    });

    //Route::get('/shopping/products', 'bpwd\admin\Controllers\Shopping\ProductsController@index');
});


Route::group([
    'prefix' => 'admin',
    'namespace'  => 'bpwd\admin\Controllers',
    'middleware' => ['web']
], function () {
    Route::auth();
});

