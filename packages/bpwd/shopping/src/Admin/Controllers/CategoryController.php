<?php

namespace Bpwd\Shopping\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Validator;
use Session;
use Redirect;
use DB;

use Bpwd\Shopping\Admin\Models\Category;

class CategoryController extends Controller
{

    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'shopping.category.';

    public function __construct(Category $main_model)
    {
        // PASS MODEL TO AdminListTrait
        $this->main_model = $main_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter = Session::get($this->session_prefix.'filter');
        $list = Session::get($this->session_prefix.'list');
        $html = new \stdClass();
        $html->title = 'Shopping -> Category List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        //$filter['category'] = (isset($filter['category']))? $filter['category'] : null;
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        //if(isset($filter['category'])) $query = $query->whereHas('categories', function ($query) use ($filter) { $query->where('category_id', '=', $filter['category']); });
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);
        $data = $query->paginate($list['limit']);

        //$category_options = $category_field->getOptions();
        //$this->addFilter('filter[category]', 'Select Category', $category_options, $filter['category']);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('categories'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('categories'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());

        return view('shopping-admin::Categories', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = null;
        $html = null;
        $this->main_model->getData(null, $data, $html, $request);
        $html->title = 'Shopping -> Category New';

        return view('shopping-admin::Category', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
        $html->title = 'Shopping -> Category Edit';

        return view('shopping-admin::Category', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                    if($request->id && $task != 'save-as-copy') $model = Category::find($request->id);
                    else $model = new Category;

                    $model->fill($request->all())
                        ->save();

                    $flash_message = 'Category saved!';
                    return self::getStoreRedirect($request, $flash_message, $task, $model);
                }

            break;
            default:
                if($task == null){
                    Session::set($this->session_prefix.'filter', $request->filter);
                    Session::set($this->session_prefix.'list', $request->list);
                }
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
