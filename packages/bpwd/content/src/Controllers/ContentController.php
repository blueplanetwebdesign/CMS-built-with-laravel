<?php

namespace Bpwd\Content\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;
use Bpwd\Admin\Traits\Controllers\AdminEditTrait;

use Bpwd\Content\Models\Content;
use Bpwd\Content\Models\Category;

use Bpwd\Content\Models\Fields\Category as CategoryField;
use Bpwd\Content\Models\Fields\Layout as LayoutField;

use Validator;
use Session;
use Redirect;
use DB;
use FormHelper;

class ContentController extends Controller
{

    use AdminListTrait, AdminEditTrait;

    protected $session_prefix = 'content.content.';

    public function __construct(Content $main_model)
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
        $html->title = 'Content -> Content List';
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

        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarNewButton('content'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarEditButton('content'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarPublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarDeleteButton());

        return view('content::Content/Contents', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    public function steps(Request $request, CategoryField $category_field, LayoutField $layout_field)
    {
        $id = $request->get('id');
        $step_number = $request->get('step_number');

        //$data = new \stdClass();
        $data = ($id)? content::find($id) : new \stdClass();

        switch($step_number) {
            case 1:
                $data->category_list = $category_field->getOptions();

                return view('content::Content/Steps/StepOne', ['data' => $data]);
                break;
            case 2:
                $data->layout_list = $layout_field->getOptions();
                $data->disabled = '';

                $category_id = $request->get('category_id');
                if($category_id){
                    $data->selected_layout = Category::select('layout_id')->where('id', '=', $category_id)->value('layout_id');

                    // IF CATEGORY HAS LAYOUT SET THEN DISABLE THE ABILITY TO OVERRIDE CATEGORY LAYOUT
                    if($data->selected_layout)$data->disabled = 'disabled';
                }
                else $data->selected_layout = '';

                return view('content::Content/Steps/StepTwo', ['data' => $data]);
                break;
            case 3:
                $this->main_model->getForm($request, $data);
                $layout = FormHelper::getLayout();

                return view('content::Content/Steps/StepThree', ['layouts' => $layout]);
                break;
        }
        //return '<div>test</div>';
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
        $html->title = 'Content -> Content New';

        return view('content::Content/Content', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
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
        $html->title = 'Content -> Content Edit';

        return view('content::Content/Content', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request  $request)
    {
        $task = $request->task;
        switch ($task){
            case 'save':
            case 'save-and-close':
            case 'save-and-new':
            case 'save-as-copy':

                // store
                if($request->id && $task != 'save-as-copy') $model = Content::find($request->id);
                else $model = new Content;

                $this->main_model->getForm($request, $model);
                $field_list = FormHelper::getAllFields();

                $fields_data = array();

                foreach ($field_list AS $field){
                    $name = $field->name;
                    $fields_data[$name] = $request->$name;
                }

                $model->fields = serialize($fields_data);

                $model->fill($request->all())
                    ->save();

                $flash_message = 'Content saved!';


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
}
