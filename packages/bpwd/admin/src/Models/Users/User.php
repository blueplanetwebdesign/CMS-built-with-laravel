<?php

namespace Bpwd\Admin\Models\Users;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    protected $table = 'users';
    public $base_url = 'admin/users/users';

    public function permissions()
    {
        return $this->belongsToMany('Bpwd\Admin\Models\Users\Permission', 'role_permissions', 'role_id', 'permission_id');
    }

    public function roles()
    {
        return $this->belongsToMany('Bpwd\Admin\Models\Users\Role', 'user_roles', 'user_id', 'role_id');
    }

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Self::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->role_list = Role::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->first_name = $request->old('first_name', ($id)? $data->first_name : '');
        $data->selected_roles = $request->old('roles', ($id)? $data->roles->lists('id')->toArray() : array());

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }

}