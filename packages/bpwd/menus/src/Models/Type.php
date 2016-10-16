<?php

namespace Bpwd\Menus\Models;

use Illuminate\Database\Eloquent\Model;

class Type extends Model {

    protected $table = 'menu_types';
    public $base_url = 'admin/menus/type';

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? self::find($id) : new \stdClass();
        $html = new \stdClass();

        //$data->category_list = Type::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->description = $request->old('description', ($id)? $data->description : '');
        //$data->selected_categories = $request->old('categories', ($id)? $data->categories->lists('id') : array());

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }


}