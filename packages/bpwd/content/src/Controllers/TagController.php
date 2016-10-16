<?php

namespace Bpwd\Content\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

use Bpwd\Admin\Traits\Controllers\AdminListTrait;

use Bpwd\Content\Models\Tag;

use Bpwd\Content\Models\Fields\Layout as LayoutField;

use Bpwd\Content\Requests\CategoryRequest;

use Session;
use Redirect;
use DB;

class TagController extends Controller
{

    use AdminListTrait;

    protected $base_url = 'admin/content/categories';
    protected $session_prefix = 'content.category.';

    public function __construct(Tag $main_model)
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
        $html->title = 'Content -> Tag List';
        $html->base_url = $this->base_url;
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

        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarNewButton('categories'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarEditButton('categories'));
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarPublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarUnpublishButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarDeleteButton());

        return view('content::Tag/Tags', ['data' => $data, 'filters' => $this->getFilters(), 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar(), 'list' => $list, 'html' => $html]);
    }

    private function getData($id, &$data, &$html, $request, $layout_field)
    {
        $data = ($id)? Category::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->layout_list = $layout_field->getOptions(true);

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->description = $request->old('description', ($id)? $data->description : '');
        $data->layout_id = $request->old('layout_id', ($id)? $data->layout_id : '');

        $html->title = 'Pages -> Category Edit';
        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolBar(\Form::adminToolbarCloseButton());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, LayoutField $layout_field)
    {
        $data = null;
        $html = null;
        self::getData(null, $data, $html, $request, $layout_field);

        return view('pages::Category/Category', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request, LayoutField $layout_field)
    {
        $data = null;
        $html = null;
        self::getData($id, $data, $html, $request, $layout_field);

        return view('pages::Category/Category', ['data' => $data, 'html' => $html, 'toolbar_slots' => \AdminToolbarHelper::getSlotsForToolBar()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        $task = $request->task;
        switch ($task){
            case 'save':
            case 'save-and-close':
            case 'save-and-new':
            case 'save-as-copy':

                // store
                if($request->id && $task != 'save-as-copy') $model = Category::find($request->id);
                else $model = new Category;

                $model->name = $request->name;
                $model->layout_id = $request->layout_id;
                $model->save();

                //if($request->categories == null) $request->categories = array();
                //$model->categories()->sync($request->categories);

                $flash_message = 'Category saved!';
                if($task == 'save') return Redirect::back()->withInput()->with([
                    'flash_message'=> $flash_message,
                    'flash_message_important' => true
                ]);
                elseif ($task == 'save-and-close') return Redirect::to($request->url())->with('flash_message', $flash_message);
                elseif ($task == 'save-and-new') return Redirect::to($request->url().'/create')->with('flash_message', $flash_message);
                elseif ($task == 'save-as-copy') return Redirect::to($request->url().'/'.$model->id.'/edit')->with('flash_message', $flash_message);


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
