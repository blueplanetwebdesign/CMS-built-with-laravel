<?php

namespace Bpwd\Content\Models;

use Illuminate\Database\Eloquent\Model;

use FormHelper;

class Content extends Model {

    protected $table = 'content_content';
    protected $fillable = ['layout_id', 'name', 'page_title', 'meta_description', 'slug'];
    public $base_url = 'admin/content/content';

    public function getForm($request, $data)
    {
        $layout_id = '';

        $category_id = $request->get('category_id');
        $id = $request->get('id');

        if(!$category_id && $id) $category_id = $data->category_id;

        if($category_id) $layout_id = Category::select('layout_id')->where('id', '=', $category_id)->value('layout_id');

        if($layout_id == '' || $layout_id == 0){
            $layout_id = $request->get('layout_id');

            if(!$layout_id && $id) $layout_id = $data->layout_id;
        }

        $layout_id = 1;
        $layout_options = array(1 => 'layout-home.xml');

        $layout_path = base_path('packages/bpwd/content/src/Layouts/'.$layout_options[$layout_id]);

        $field_data = (isset($data->fields))? unserialize($data->fields) : array();

        FormHelper::getForm($layout_path, $field_data);
    }

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Content::find($id) : new \stdClass();
        $html = new \stdClass();

        //$data->category_list = Category::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->page_title = $request->old('page_title', ($id)? $data->page_title : '');
        $data->meta_description = $request->old('meta_description', ($id)? $data->meta_description : '');
        $data->slug = $request->old('slug', ($id)? $data->slug : '');
        $data->fields = $request->old('fields', ($id)? $data->fields : '');
        //$data->selected_categories = $request->old('categories', ($id)? $data->categories->lists('id') : array());

        $html->base_url = $this->base_url;
        $html->initially_selected_step = ($id)? 2 : 0;

        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarCloseButton());
    }

}