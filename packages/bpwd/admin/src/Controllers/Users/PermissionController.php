<?php

namespace Bpwd\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Validator;
use Session;
use Redirect;
use AdminToolbarHelper;

use Bpwd\Admin\Models\Users\Permission;

class PermissionController extends Controller
{
    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'users.permissions.';
    protected $plural = 'permission';
    protected $singular = 'permissions';

    public function __construct(Permission $main_model)
    {
        // PASS MODEL TO Traits
        $this->main_model = $main_model;
    }

    //protected $base_url = 'admin/shopping/categories';
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
        $html->title = 'Users -> Permission List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        //$filter['category'] = (isset($filter['category']))? $filter['category'] : null;
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        //if(isset($filter['category'])) $query = $query->whereHas('categories', function ($query) use ($filter) { $query->where('category_id', '=', $filter['category']); });
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);
        $data = $query->paginate($list['limit']);

        //$role_options = $role_field->getOptions();
        //$this->addFilter('filter[category]', 'Select Category', $category_options, $filter['category']);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('users'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('users'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());

        return view('admin::Pages/Users/Permissions', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
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
        $html->title = 'Users -> Permission Edit';

        return view('admin::Pages/Users/Permission', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
        $html->title = 'Users -> Permission New';

        return view('admin::Pages/Users/Permission', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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

                $rules = array(
                    'title' => 'required',
                    'description'=> 'required'
                );
                $validator = Validator::make($request->all(), $rules);

                // process the login
                if ($validator->fails()) {
                    return Redirect::to('admin/users/permissions/create')
                        ->withErrors($validator)
                        ->withInput($request->all());
                } else {

                    // store
                    if($request->id) $model = Permission::find($request->id);
                    else $model = new Permission;

                    $model->title = $request->title;
                    $model->slug = $request->slug;
                    $model->description = $request->description;

                    $model->save();

                    Session::flash('message', 'Successfully created nerd!');

                    if($task == 'save') return Redirect::back()->withInput($request->all());
                    elseif ($task == 'save-and-close') return Redirect::to($request->url());
                    elseif ($task == 'save-and-new') return Redirect::to($request->url().'/create');
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

    public function unpublish(Request $request){

        Category::whereIn('id', $request->id)->update(['published' => 0]);
        return Redirect::to($this->base_url);

    }

    public function publish(Request $request){

        Category::whereIn('id', $request->id)->update(['published' => 1]);
        return Redirect::to($this->base_url);

    }
}
