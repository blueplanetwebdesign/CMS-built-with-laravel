<?php

namespace Bpwd\Admin\Models\Users;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model {

    protected $table = 'permissions';
    public $timestamps = false;

    public $base_url = 'admin/users/permissions';

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Self::find($id) : new \stdClass();
        $html = new \stdClass();

        //$data->role_list = Role::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->slug = $request->old('slug', ($id)? $data->slug : '');
        //$data->selected_roles = $request->old('roles', ($id)? $data->roles->lists('id')->toArray() : array());

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }

}