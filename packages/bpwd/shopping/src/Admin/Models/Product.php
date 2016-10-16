<?php

namespace Bpwd\Shopping\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model {

    protected $table = 'shopping_products';
    public $base_url = 'admin/shopping/products';

    protected $fillable = ['description', 'name', 'price'];

    public function categories()
    {
        return $this->belongsToMany('Bpwd\Shopping\Admin\Models\Category', 'shopping_product_categories', 'product_id', 'category_id');
    }

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Product::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->category_list = Category::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->price = $request->old('price', ($id)? $data->price : '');
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