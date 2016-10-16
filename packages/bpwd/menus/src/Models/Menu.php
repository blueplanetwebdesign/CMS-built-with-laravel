<?php

namespace Bpwd\Menus\Models;

use Bpwd\Admin\Libraries\NestedSetsModel;
use DB;

class Menu extends NestedSetsModel {

    protected $table = 'menu';

    protected $guarded = ['created_at', 'updated_at'];
    protected $fillable = ['type_id', 'slug', 'name', 'home', 'parent_id', 'component_id', 'depth', 'lft', 'rgt', 'published'];
    protected $_tbl_key = 'id';
    protected $_tbl = 'menu';
    protected $_debug = false;

    public $base_url = 'admin/menus/menu';

    public function getData($id, &$data, &$html, $request)
    {
        $data = ($id)? self::find($id) : new \stdClass();
        $html = new \stdClass();

        $data->id = ($id)? $data->id : '';
        $data->name = $request->old('name', ($id)? $data->name : '');
        $data->type_id = $request->old('type_id', ($id)? $data->type_id : '');
        $data->parent_id = $request->old('parent_id', ($id)? $data->parent_id : '');
        $data->slug = $request->old('slug', ($id)? $data->slug : '');
        //$data->description = $request->old('description', ($id)? $data->description : '');
        //$data->selected_categories = $request->old('categories', ($id)? $data->categories->lists('id') : array());

        $data->parent_list = self::getParentOptions($data->id, $data->type_id);
        $data->ordering_list = self::getOrderingOptions($data->parent_id, $data->type_id);

        $data->type_list = Type::lists('name', 'id');

        $html->base_url = $this->base_url;

        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndCloseButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAndNewButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarSaveAsCopyButton());
        \AdminToolbarHelper::addSlotForToolbar(\Form::adminToolbarCloseButton());
    }

    /**
     * Method to save the form data.
     *
     * @param   array  $data  The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
    public function save(array $options = Array())
    {
        $k = $this->_tbl_key;
        $instance = new static;

        DB::beginTransaction();

        /*
         * If the primary key is empty, then we assume we are inserting a new node into the
         * tree.  From this point we would need to determine where in the tree to insert it.
         */

        if (empty($this->$k))
        {
            /*
             * We are inserting a node somewhere in the tree with a known reference
             * node.  We have to make room for the new node and set the left and right
             * values before we insert the row.
             */

            if ($this->_location_id >= 0)
            {
                // We are inserting a node relative to the last root node.
                if ($this->_location_id == 0)
                {
                    $query = DB::table($this->_tbl)
                        ->select($this->_tbl_key, 'parent_id', 'depth', 'lft', 'rgt')
                        ->where('parent_id', '=', 0)
                        ->orderBy('lft', 'DESC')
                        ->sharedLock();
                    $reference = $query->first();
                }
                // We have a real node set as a location reference.
                else
                {
                    // Get the reference node by primary key.
                    if (!$reference = $this->_getNode($this->_location_id))
                    {
                        // Error message set in getNode method.
                        //DB::rollbackTransaction();

                        return false;
                    }
                }

                // Get the reposition data for shifting the tree and re-inserting the node.
                if (!($repositionData = $this->_getTreeRepositionData($reference, 2, $this->_location)))
                {
                    // Error message set in getNode method.
                    //DB::rollbackTransaction();
                    return false;
                }

                // Create space in the tree at the new location for the new node in left ids.
                DB::update('UPDATE '.$this->_tbl.' set `lft` = `lft` + 2 where '.$repositionData->left_where["col"].' '.$repositionData->left_where["operator"].' ?', [$repositionData->left_where["value"]]);

                // Create space in the tree at the new location for the new node in right ids.
                DB::update('UPDATE '.$this->_tbl.' set rgt = rgt + 2 where '.$repositionData->right_where["col"].' '.$repositionData->right_where["operator"].' ?', [$repositionData->right_where["value"]]);


                //DB::update('UPDATE '.$this->_tbl.' set slug = ?', ['test 3']);

                // Set the object values.
                $this->parent_id = $repositionData->new_parent_id;
                $this->depth = $repositionData->new_depth;
                $this->lft = $repositionData->new_lft;
                $this->rgt = $repositionData->new_rgt;
            }
            else
            {
                // Negative parent ids are invalid
                $e = new UnexpectedValueException(sprintf('%s::store() used a negative _location_id', get_class($this)));
                $this->setError($e);

                return false;
            }
        }
        /*
         * If we have a given primary key then we assume we are simply updating this
         * node in the tree.  We should assess whether or not we are moving the node
         * or just updating its data fields.
         */
        else
        {
            // If the location has been set, move the node to its new location.
            if ($this->_location_id > 0)
            {
                if (!$this->moveByReference($this->_location_id, $this->_location, $this->$k))
                {
                    // Error message set in move method.
                    return false;
                }
            }
        }

        $result = parent::save($options);
        DB::commit();

        return $result;
    }

}