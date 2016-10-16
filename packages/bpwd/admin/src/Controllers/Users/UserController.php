<?php

namespace Bpwd\Admin\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Bpwd\Admin\Models\Users\User;
use Bpwd\Admin\Models\Users\Role;

use Bpwd\Admin\Models\Users\Fields\Role as RoleField;
use Bpwd\Admin\Models\Users\Fields\Permission as PermissionField;

use Validator;
use Session;
use Redirect;
use AdminToolbarHelper;

class UserController extends Controller
{
    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'users.users.';
    protected $plural = 'user';
    protected $singular = 'users';

    public function __construct(User $main_model)
    {
        // PASS MODEL TO Traits
        $this->main_model = $main_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RoleField $role_field, PermissionField $permission_field)
    {
        $filter = Session::get($this->session_prefix.'filter');
        $list = Session::get($this->session_prefix.'list');
        $html = new \stdClass();
        $html->title = 'Users -> User List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        $filter['permission'] = (isset($filter['permission']))? $filter['permission'] : null;
        $filter['role'] = (isset($filter['role']))? $filter['role'] : null;
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        if(isset($filter['permission'])) $query = $query->whereHas('roles', function ($query) use ($filter) {
            $query->whereHas('permissions', function ($query) use ($filter) { $query->where('permission_id', '=', $filter['permission']); });
        });

        if(isset($filter['role'])) $query = $query->whereHas('roles', function ($query) use ($filter) { $query->where('role_id', '=', $filter['role']); });
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);

        $data = $query->paginate($list['limit']);

        $role_options = $role_field->getOptions();
        $permission_options = $permission_field->getOptions();

        $this->addFilter('filter[permission]', 'Select Permission', $permission_options, $filter['permission']);
        $this->addFilter('filter[role]', 'Select Role', $role_options, $filter['role']);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('users'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('users'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());

        return view('admin::Pages/Users/Users', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
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
        $html->title = 'Users -> User New';

        return view('admin::Pages/Users/User', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
        $html->title = 'Users -> User Edit';

        return view('admin::Pages/Users/User', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
                    'name' => 'required',
                    'description'=> 'required'
                );
                $validator = Validator::make($request->all(), $rules);

                // process the login
                if ($validator->fails()) {
                    return Redirect::back()
                        ->withErrors($validator)
                        ->withInput($request->all());
                } else {
                    // store
                    if($request->id) $model = Category::find($request->id);
                    else $model = new Category;

                    $model->description = $request->description;
                    $model->name = $request->name;
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
}
