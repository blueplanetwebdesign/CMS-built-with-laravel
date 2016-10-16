<?php

namespace Bpwd\Menus\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Bpwd\Menus\Models\Menu;
use Bpwd\Shopping\Admin\Models\Type;

use Bpwd\Menus\Models\Fields\Type as TypeField;

use Bpwd\Menus\Requests\MenuRequest;

use Session;
use Redirect;
use DB;
use AdminToolbarHelper;

class MenuController extends Controller
{

    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'shopping.product.';

    public function __construct(Menu $main_model)
    {
        // PASS MODEL TO AdminListTrait
        $this->main_model = $main_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TypeField $type_field)
    {

        $filter = Session::get($this->session_prefix.'filter');
        $list = Session::get($this->session_prefix.'list');
        $html = new \stdClass();
        $html->title = 'Menus -> Menu List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        $type_options = $type_field->getOptions();

        $filter['type'] = (isset($filter['type']))? $filter['type'] : key($type_options);
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        if(isset($filter['type'])) $query = $query->where('type_id', '=', $filter['type']);
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);
        $query->where('depth', '!=', 0)
                ->orderBy('lft');

        $data = $query->paginate($list['limit']);

        $this->addFilter('filter[type]', 'Select Type', $type_options, $filter['type'], true);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('menu'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('menu'));
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());
        AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarRebuildButton('menu'));

        return view('menus::Menu/Menus', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
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
        $html->title = 'Menus -> Menu Edit';

        //print_r($this->main_model->getParentOptions(1, 2));
        //die();

        return view('menus::Menu/Menu', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
        $html->title = 'Menus -> Menu Edit';

        return view('menus::Menu/Menu', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(),]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {

        $task = $request->task;
        switch ($task){
            case 'save':
            case 'save-and-close':
            case 'save-and-new':
            case 'save-as-copy':

                // store
                if($request->id && $task != 'save-as-copy'){

                    $model = Menu::find($request->id);

                    if ($model->parent_id == $request->parent_id)
                    {
                        // If first is chosen make the item the first child of the selected parent.
                        if ($request->ordering == -1)
                        {
                            $model->setLocation($request->parent_id, 'first-child');
                        }
                        // If last is chosen make it the last child of the selected parent.
                        elseif ($request->ordering == -2)
                        {
                            $model->setLocation($request->parent_id, 'last-child');
                        }
                        // Don't try to put an item after itself. All other ones put after the selected item.
                        // $data['id'] is empty means it's a save as copy
                        elseif ($request->ordering && $model->id != $request->ordering || !$request->id)
                        {
                            $model->setLocation($request->ordering, 'after');
                        }
                        // Just leave it where it is if no change is made.
                        elseif ($request->ordering && $model->id == $request->ordering)
                        {
                            unset($request->ordering);
                        }
                    }
                    // Set the new parent id if parent id not matched and put in last position
                    else
                    {
                        $model->setLocation($request->parent_id, 'last-child');
                    }
                }
                else{
                    $model = new Menu;
                    $model->setLocation($request->parent_id, 'last-child');
                }

                if($request->slug == ''){
                    $request->offsetSet('slug', str_slug($request->name));
                    $this->validate($request, [
                        'slug' => 'unique:menu|required'
                    ]);
                }
                $model->fill($request->all());
                $model->save();


                // Rebuild the tree path.
                //if (!$model->rebuildPath())
                //{
                //    $this->setError($table->getError());

                //return false;
                //}

                $flash_message = 'Menu saved!';
                return self::getStoreRedirect($request, $flash_message, $task, $model);

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

    /**
     * Rebuild the nested set tree.
     *
     * @return  bool  False on failure or error, true on success.
     */
    public function rebuild()
    {
        //$this->setRedirect('index.php?option=com_menus&view=items');
        if ($this->main_model->rebuild())
        {
            // Reorder succeeded.
            $flash_message = 'Menu items rebuild success.';
        }
        else
        {
            $flash_message = 'Menu items rebuild failed.';
        }

        return Redirect::to($this->main_model->base_url)->with('flash_message', $flash_message);
    }
}
