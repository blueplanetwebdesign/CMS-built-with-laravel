<?php

namespace Bpwd\Shopping\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;

use Bpwd\Shopping\Admin\Models\Product;
use Bpwd\Shopping\Admin\Models\Category;

use Bpwd\Shopping\Admin\Models\Fields\Category as CategoryField;

use Bpwd\Shopping\Admin\Requests\ProductRequest;

use Validator;
use Session;
use Redirect;
use DB;

class Product2Controller extends Controller
{

    use AdminListTrait;

    protected $base_url = 'admin/shopping/products';
    protected $session_prefix = 'shopping.product.';

    public function __construct(Product $main_model)
    {
        // PASS MODEL TO AdminListTrait
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

        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolBarNewButton('products'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolBarEditButton('products'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolBarPublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolBarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolBarDeleteButton());

        return view('shopping::Products', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    private function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? Product::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->category_list = Category::lists('name', 'id');

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->description = $request->old('description', ($id)? $data->description : '');
        $data->selected_categories = $request->old('categories', ($id)? $data->categories->lists('id') : array());

        $html->title = 'Shopping -> Product Edit';
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
        self::getData(null, $data, $html, $request);

        return view('shopping::Product', ['data' => $data, 'html' => $html]);
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
        self::getData($id, $data, $html, $request);

        return view('shopping::Product', ['data' => $data, 'html' => $html]);
    }

    /**
     * Update filter and list sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function listUpdate(Request $request)
    {
        Session::set($this->session_prefix.'filter', $request->filter);
        Session::set($this->session_prefix.'list', $request->list);
        
        return Redirect::to($this->base_url);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest  $request)
    {

        $task = $request->task;
        switch ($task){
            case 'save':
            case 'save-and-close':
            case 'save-and-new':
            case 'save-as-copy':

                $rules = array(
                    'name' => 'required',
                    'description'=> 'required'
                );
                $validator = Validator::make($request->all(), $rules);

                // process the login
                if ($validator->fails()) {
                    return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
                } else {
                    // store
                    if($request->id && $task != 'save-as-copy') $model = Product::find($request->id);
                    else $model = new Product;

                    $model->description = $request->description;
                    $model->name = $request->name;
                    $model->save();

                    if($request->categories == null) $request->categories = array();
                    $model->categories()->sync($request->categories);

                    $flash_message = 'Product saved!';
                    if($task == 'save') return Redirect::back()->withInput()->with([
                        'flash_message'=> $flash_message,
                        'flash_message_important' => true
                    ]);
                    elseif ($task == 'save-and-close') return Redirect::to($request->url())->with('flash_message', $flash_message);
                    elseif ($task == 'save-and-new') return Redirect::to($request->url().'/create')->with('flash_message', $flash_message);
                    elseif ($task == 'save-as-copy') return Redirect::to($request->url().'/'.$model->id.'/edit')->with('flash_message', $flash_message);
                }

                break;
            default:
                return Redirect::to($request->url());
        }
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
