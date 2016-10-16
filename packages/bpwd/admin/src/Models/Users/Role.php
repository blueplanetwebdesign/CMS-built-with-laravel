<?php

namespace Bpwd\Admin\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $table = 'roles';
    public $base_url = 'admin/users/roles';

    public function permissions()
    {
        return $this->belongsToMany('Bpwd\Admin\Models\Users\Permission', 'role_permissions', 'role_id', 'permission_id');
    }

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Self::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->permission_list = Permission::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->slug = $request->old('slug', ($id)? $data->slug : '');
        $data->selected_permissions = $request->old('permissions', ($id)? $data->permissions->lists('id')->toArray() : array());

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }

}