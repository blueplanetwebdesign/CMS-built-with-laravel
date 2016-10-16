<?php

namespace Bpwd\Shopping\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Bpwd\Shopping\Admin\Models\Product;
use Bpwd\Shopping\Admin\Models\Category;

use Bpwd\Shopping\Admin\Models\Fields\Category as CategoryField;

use Bpwd\Shopping\Admin\Requests\ProductRequest;

use Session;
use Redirect;
use DB;

use FormHelper;

class ProductController extends Controller
{

    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'shopping.product.';
    protected $plural = 'product';
    protected $singular = 'products';

    public function __construct(Product $main_model)
    {
        // PASS MODEL TO Traits
        $this->main_model = $main_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryField $category_field)
    {
        $filter = Session::get($this->session_prefix.'filter');
        $list = Session::get($this->session_prefix.'list');
        $html = new \stdClass();
        $html->title = 'Shopping -> Product List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        $filter['category'] = (isset($filter['category']))? $filter['category'] : null;
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        if(isset($filter['category'])) $query = $query->whereHas('categories', function ($query) use ($filter) { $query->where('category_id', '=', $filter['category']); });
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);
        $data = $query->paginate($list['limit']);

        $category_options = $category_field->getOptions();
        $this->addFilter('filter[category]', 'Select Category', $category_options, $filter['category']);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('products'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('products'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());

        return view('shopping-admin::Products', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    public function store(ProductRequest $request)
    {
        $task = $request->task;
        switch ($task){
            case 'save':
            case 'save-and-close':
            case 'save-and-new':
            case 'save-as-copy':

                // store
                if($request->id && $task != 'save-as-copy') $model = Product::find($request->id);
                else $model = new Product;

                $model->fill($request->all())
                    ->save();

                if($request->categories == null) $request->categories = array();
                $model->categories()->sync($request->categories);

                $flash_message = 'Product saved!';
                return self::getStoreRedirect($request, $flash_message, $task, $model);

                break;
            default:
                return Redirect::to($request->url());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = null;
        $html = null;
        $this->main_model->getData(null, $data, $html, $request);
        $html->title = 'Shopping -> Product New';

        return view('shopping-admin::Product', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $data = null;
        $html = null;
        $this->main_model->getData($id, $data, $html, $request);
        $html->title = 'Shopping -> Product Edit';

        return view('shopping-admin::Product', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
