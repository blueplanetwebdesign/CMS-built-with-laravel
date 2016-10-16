<?php

namespace Bpwd\Modules\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Bpwd\Modules\Models\Module;
//use Bpwd\Shopping\Models\Category;

use Bpwd\Modules\Models\Fields\Position as PositionField;

//use Bpwd\Shopping\Requests\ProductRequest;

use Session;
use Redirect;
use DB;

use FormHelper;

class ModuleController extends Controller
{

    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'modules.modules.';
    protected $plural = 'module';
    protected $singular = 'modules';

    public function __construct(Module $main_model)
    {
        // PASS MODEL TO Traits
        $this->main_model = $main_model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PositionField $position_field)
    {
        $filter = Session::get($this->session_prefix.'filter');
        $list = Session::get($this->session_prefix.'list');
        $html = new \stdClass();
        $html->title = 'Modules -> Module List';
        $html->base_url = $this->main_model->base_url;
        $query = null;

        self::setUpIndex($filter, $list, $html, $query);

        $filter['position'] = (isset($filter['position']))? $filter['position'] : null;
        $filter['published'] = (isset($filter['published']))? $filter['published'] : null;

        if(isset($filter['position'])) $query = $query->whereHas('categories', function ($query) use ($filter) { $query->where('position_slug', '=', $filter['position']); });
        if(isset($filter['published'])) $query = $query->where('published', '=', $filter['published']);
        $data = $query->paginate($list['limit']);

        $position_options = $position_field->getOptions();
        $this->addFilter('filter[position]', 'Select Position', $position_options, $filter['position']);
        $this->addFilter('filter[published]', 'Select Published', array(0 => 'Unpublished', 1 => 'Published'), $filter['published']);

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarNewButton('modules'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarEditButton('modules'));
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarPublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarDeleteButton());

        return view('module::Modules', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    public function store($request)
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

                //if($request->categories == null) $request->categories = array();
                //$model->categories()->sync($request->categories);

                $flash_message = 'Module saved!';
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

        return view('module::Module', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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

        return view('shopping::Product', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
