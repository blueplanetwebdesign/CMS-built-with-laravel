<?php

namespace Bpwd\Modules\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model {

    protected $table = 'modules';
    public $base_url = 'admin/modules/modules';

    protected $fillable = ['description', 'name'];

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Self::find($id) : new \stdClass();
        $html = new \stdClass();

        //$data->category_list = Category::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->description = $request->old('description', ($id)? $data->description : '');
        $data->selected_categories = $request->old('categories', ($id)? $data->categories->lists('id')->toArray() : array());

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }
    
}