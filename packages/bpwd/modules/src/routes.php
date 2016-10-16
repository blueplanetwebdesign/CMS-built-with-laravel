<?php

Route::group([
    'prefix' => 'admin/modules/',
    'namespace'  => 'bpwd\modules\Controllers',
    'middleware' => ['web', 'auth.admin', 'acl:access-admin-panel']
], function(){

    Route::post('modules/publish', 'ModuleController@publish');
    Route::post('modules/unpublish', 'ModuleController@unpublish');
    Route::get('modules/{id}/publish', 'ModuleController@publish');
    Route::get('modules/{id}/unpublish', 'ModuleController@unpublish');
    Route::post('modules/delete', 'ModuleController@delete');
    Route::post('modules/list-update', 'ModuleController@listUpdate');
    //Route::resource('modules', 'ModuleController');

    Route::get('modules', function(){
        echo 'Coming soon';
    });
});