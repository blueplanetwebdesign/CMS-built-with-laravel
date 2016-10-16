<?php

namespace Bpwd\Admin\Libraries;

use Illuminate\Database\Eloquent\Model;

use Exception;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder;
use LogicException;
use MongoDB\Driver\Query;
use DB;

class NestedSetsModel extends Model
{
    /**
     * Object property to hold the location type to use when storing the row.
     *
     * Possible values are: ['before', 'after', 'first-child', 'last-child'].
     *
     * @var    string
     */
    protected $_location;

    /**
     * Object property to hold the primary key of the location reference node to use when storing the row.
     *
     * A combination of location type and reference node describes where to store the current node in the tree.
     *
     * @var    integer
     */
    protected $_location_id;

    /**
     * Cache for the root ID
     *
     * @var    integer
     */
    protected static $root_id = 0;

    protected static $recursive_query_rebuild;

    public function getParentOptions($id = null, $type_id = null)
    {
        $options = array();

        $instance = new static;

        $query = $instance->newQuery()
            ->select('a.id', 'a.name', 'a.depth')
            ->from('menu AS a')
            ->leftJoin('menu AS b', function ($join) {
                $join->on('a.lft', '>', 'b.lft')
                    ->on('a.rgt', '<', 'b.rgt');
            });

        if ($type_id) $query->where('a.type_id', '=', $type_id);
        else $query->where('a.type_id', '!=', '');

        if ($id) {
            $query->leftJoin('menu AS p', 'p.id', '=', DB::raw((int)$id));

            //echo $query->toSql();
            //die();

            $query->whereRaw('not( a.lft >= p.lft and a.rgt <= p.rgt )');
        }

        //$query->where('a.published', '!=', '');
        $query->groupBy('a.id', 'a.name', 'a.depth', 'a.lft', 'a.rgt', 'a.type_id', 'a.parent_id', 'a.published')
            ->orderBy('a.lft', 'ASC');

        try {
            $data = $query->get()->toArray();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        $options = array();
        $options[1] = 'Menu Item Root';

        foreach ($data AS $value) {
            $options[$value['id']] = str_repeat('- ', $value['depth']) . $value['name'];
        }

        return $options;
    }

    public function getOrderingOptions($parent_id, $type_id)
    {
        $options = array();

        $instance = new static;

        if (empty($parent_id))
        {
            return false;
        }

        $query = DB::table('menu AS a')
            ->select('a.id', 'a.name')
            ->from('menu AS a')
            ->where('a.published', '>=', 0)
            ->where('a.parent_id', '=', $parent_id);

        if ($type_id)
        {
            $query->where('a.type_id', '=', $type_id);
        }
        else
        {
            $query->where('a.type_id', '!=', '');
        }

        $query->orderBy('a.lft', 'ASC');



        try {
            $options = $query->lists('name', 'id');
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());
        }

        //dd(DB::getQueryLog());

        //print_r($options);
        //die();

        $options = array('-1' => 'First') + $options + array('-2' => 'Last');

        return $options;
    }

    public function setLocation($referenceId, $position = 'after')
    {
        // Make sure the location is valid.
        if (($position != 'before') && ($position != 'after') && ($position != 'first-child') && ($position != 'last-child'))
        {
            throw new InvalidArgumentException(sprintf('%s::setLocation(%d, *%s*)', get_class($this), $referenceId, $position));
        }

        // Set the location properties.
        $this->_location = $position;
        $this->_location_id = $referenceId;
    }

    /**
     * Method to rebuild the node's path field from the alias values of the nodes from the current node to the root node of the tree.
     *
     * @param   integer  $pk  Primary key of the node for which to get the path.
     *
     * @return  boolean  True on success.
     *
     * @since   11.1
     */
    public function rebuildPath($pk = null)
    {
        //$fields = $this->getFields();

        // If there is no alias or path field, just return true.
        //if (!array_key_exists('alias', $fields) || !array_key_exists('path', $fields))
        //{
        //    return true;
        //}

        $k = $this->_tbl_key;
        $pk = (is_null($pk)) ? $this->$k : $pk;

        // Get the aliases for the path from the node to the root node.

        $test = DB::table(DB::Raw($this->_tbl . ' AS n, ' . $this->_tbl . ' AS p'))
            ->select('p.alias')
            ->whereRaw('n.lft BETWEEN p.lft AND p.rgt')
            ->where('n.'. $this->_tbl_key, '=', $pk)
            ->orderBy('p.lft');

        $r = $test->get();
        print_r($r);
        die();


        $query = $this->_db->getQuery(true)
            ->select('p.alias')
            ->from($this->_tbl . ' AS n, ' . $this->_tbl . ' AS p')
            ->where('n.lft BETWEEN p.lft AND p.rgt')
            ->where('n.' . $this->_tbl_key . ' = ' . (int) $pk)
            ->order('p.lft');
        $this->_db->setQuery($query);

        $segments = $this->_db->loadColumn();

        // Make sure to remove the root path if it exists in the list.
        if ($segments[0] == 'root')
        {
            array_shift($segments);
        }

        // Build the path.
        $path = trim(implode('/', $segments), ' /\\');

        // Update the path field for the node.
        $query->clear()
            ->update($this->_tbl)
            ->set('path = ' . $this->_db->quote($path))
            ->where($this->_tbl_key . ' = ' . (int) $pk);

        $this->_db->setQuery($query)->execute();

        // Update the current record's path to the new one:
        $this->path = $path;

        return true;
    }

    /**
     * Method to get nested set properties for a node in the tree.
     *
     * @param   integer  $id   Value to look up the node by.
     * @param   string   $key  An optional key to look up the node by (parent | left | right).
     *                         If omitted, the primary key of the table is used.
     *
     * @return  mixed    Boolean false on failure or node object on success.
     *
     * @since   11.1
     * @throws  RuntimeException on database error.
     */
    protected function _getNode($id, $key = null)
    {
        // Determine which key to get the node base on.
        switch ($key)
        {
            case 'parent':
                $k = 'parent_id';
                break;

            case 'left':
                $k = 'lft';
                break;

            case 'right':
                $k = 'rgt';
                break;

            default:
                $k = $this->_tbl_key;
                break;
        }

        $query = DB::table($this->_tbl)
                    ->select($this->_tbl_key, 'parent_id', 'depth', 'lft', 'rgt')
                    ->where($k, '=', $id);

        $row = $query->first();

        // Check for no $row returned
        if (empty($row))
        {
            $e = new UnexpectedValueException(sprintf('%s::_getNode(%d, %s) failed.', get_class($this), $id, $key));
            $this->setError($e);

            return false;
        }

        // Do some simple calculations.
        $row->numChildren = (int) ($row->rgt - $row->lft - 1) / 2;
        $row->width = (int) $row->rgt - $row->lft + 1;

        return $row;
    }

    /**
     * Method to get various data necessary to make room in the tree at a location
     * for a node and its children.  The returned data object includes conditions
     * for SQL WHERE clauses for updating left and right id values to make room for
     * the node as well as the new left and right ids for the node.
     *
     * @param   object   $referenceNode  A node object with at least a 'lft' and 'rgt' with
     *                                   which to make room in the tree around for a new node.
     * @param   integer  $nodeWidth      The width of the node for which to make room in the tree.
     * @param   string   $position       The position relative to the reference node where the room
     *                                   should be made.
     *
     * @return  mixed    Boolean false on failure or data object on success.
     *
     * @since   11.1
     */
    protected function _getTreeRepositionData($referenceNode, $nodeWidth, $position = 'before')
    {
        // Make sure the reference an object with a left and right id.
        if (!is_object($referenceNode) || !(isset($referenceNode->lft) || !(isset($referenceNode->rgt))))
        {
            return false;
        }

        // A valid node cannot have a width less than 2.
        if ($nodeWidth < 2)
        {
            return false;
        }

        $k = $this->_tbl_key;
        $data = new \stdClass;

        // Run the calculations and build the data object by reference position.
        switch ($position)
        {
            case 'first-child':
                $data->left_where = array("col" => "lft", "operator" => ">", "value" => $referenceNode->lft);
                $data->right_where = array("col" => "rgt", "operator" => ">=", "value" => $referenceNode->lft);

                $data->new_lft = $referenceNode->lft + 1;
                $data->new_rgt = $referenceNode->lft + $nodeWidth;
                $data->new_parent_id = $referenceNode->$k;
                $data->new_depth = $referenceNode->depth + 1;
                break;

            case 'last-child':
                $data->left_where = array("col" => "lft", "operator" => ">", "value" => $referenceNode->rgt);
                $data->right_where = array("col" => "rgt",  "operator" => ">=", "value" => $referenceNode->rgt);

                $data->new_lft = $referenceNode->rgt;
                $data->new_rgt = $referenceNode->rgt + $nodeWidth - 1;
                $data->new_parent_id = $referenceNode->$k;
                $data->new_depth = $referenceNode->depth + 1;
                break;

            case 'before':

                $data->left_where = array("col" => "lft", "operator" => ">=", "value" => $referenceNode->lft);
                $data->right_where = array("col" => "rgt", "operator" => ">=", "value" => $referenceNode->lft);

                $data->new_lft = $referenceNode->lft;
                $data->new_rgt = $referenceNode->lft + $nodeWidth - 1;
                $data->new_parent_id = $referenceNode->parent_id;
                $data->new_depth = $referenceNode->depth;
                break;

            default:
            case 'after':

                $data->left_where = array("col" => "lft", "operator" => ">", "value" => $referenceNode->rgt);
                $data->right_where = array("col" => "rgt", "operator" => ">", "value" => $referenceNode->rgt);

                $data->new_lft = $referenceNode->rgt + 1;
                $data->new_rgt = $referenceNode->rgt + $nodeWidth;
                $data->new_parent_id = $referenceNode->parent_id;
                $data->new_depth = $referenceNode->depth;
                break;
        }

        // @codeCoverageIgnoreStart
        if ($this->_debug)
        {
            echo "\nRepositioning Data for $position" . "\n-----------------------------------" . "\nLeft Where:    $data->left_where"
                . "\nRight Where:   $data->right_where" . "\nNew Lft:       $data->new_lft" . "\nNew Rgt:       $data->new_rgt"
                . "\nNew Parent ID: $data->new_parent_id" . "\nNew Level:     $data->new_level" . "\n";
        }
        // @codeCoverageIgnoreEnd

        return $data;
    }


    /**
     * Method to move a node and its children to a new location in the tree.
     *
     * @param   integer  $referenceId  The primary key of the node to reference new location by.
     * @param   string   $position     Location type string. ['before', 'after', 'first-child', 'last-child']
     * @param   integer  $pk           The primary key of the node to move.
     *
     * @return  boolean  True on success.
     *
     * @since   11.1
     * @throws  RuntimeException on database error.
     */
    public function moveByReference($referenceId, $position = 'after', $pk = null)
    {
        // @codeCoverageIgnoreStart
        if ($this->_debug)
        {
            echo "\nMoving ReferenceId:$referenceId, Position:$position, PK:$pk";
        }
        // @codeCoverageIgnoreEnd

        $k = $this->_tbl_key;
        $pk = (is_null($pk)) ? $this->$k : $pk;

        // Get the node by id.
        if (!$node = $this->_getNode($pk))
        {
            // Error message set in getNode method.
            return false;
        }

        $query = DB::table($this->_tbl)
                    ->select($k)
                    ->whereBetween('lft', [$node->lft, $node->rgt])
                    ->lockForUpdate();

        $children = $query->pluck($k);

        // Cannot move the node to be a child of itself.
        if (in_array($referenceId, $children))
        {
            $e = new UnexpectedValueException(
                sprintf('%s::moveByReference(%d, %s, %d) parenting to child.', get_class($this), $referenceId, $position, $pk)
            );
            $this->setError($e);

            return false;
        }

        /*
         * Move the sub-tree out of the nested sets by negating its left and right values.
         */
        DB::table($this->_tbl)
            ->whereBetween('lft', [$node->lft, $node->rgt])
            ->update(['lft' => DB::raw('lft * (-1)'), 'rgt' => DB::raw('rgt * (-1)')]);

        /*
         * Close the hole in the tree that was opened by removing the sub-tree from the nested sets.
         */
        // Compress the left values.
        DB::update('UPDATE '.$this->_tbl.' SET `lft` = `lft` - ? WHERE `lft` > ?', [$node->width, $node->rgt]);

        // Compress the right values.
        DB::update('UPDATE '.$this->_tbl.' SET `rgt` = `rgt` - ? WHERE `rgt` > ?', [$node->width, $node->rgt]);

        // We are moving the tree relative to a reference node.
        if ($referenceId)
        {
            // Get the reference node by primary key.
            if (!$reference = $this->_getNode($referenceId))
            {
                // Error message set in getNode method.
                $this->_unlock();

                return false;
            }

            // Get the reposition data for shifting the tree and re-inserting the node.
            if (!$repositionData = $this->_getTreeRepositionData($reference, $node->width, $position))
            {
                // Error message set in getNode method.
                $this->_unlock();

                return false;
            }
        }
        // We are moving the tree to be the last child of the root node
        else
        {
            // Get the last root node as the reference node.
            $query->clear()
                ->select($this->_tbl_key . ', parent_id, level, lft, rgt')
                ->from($this->_tbl)
                ->where('parent_id = 0')
                ->order('lft DESC');
            $this->_db->setQuery($query, 0, 1);
            $reference = $this->_db->loadObject();

            // @codeCoverageIgnoreStart
            if ($this->_debug)
            {
                $this->_logtable(false);
            }
            // @codeCoverageIgnoreEnd

            // Get the reposition data for re-inserting the node after the found root.
            if (!$repositionData = $this->_getTreeRepositionData($reference, $node->width, 'last-child'))
            {
                // Error message set in getNode method.
                $this->_unlock();

                return false;
            }
        }

        /*
         * Create space in the nested sets at the new location for the moved sub-tree.
         */

        // Shift left values.
        DB::update('UPDATE '.$this->_tbl.' SET `lft` = `lft` + ? WHERE '.$repositionData->left_where["col"].' '.$repositionData->left_where["operator"].' ?', [$node->width, $repositionData->left_where["value"]]);

        // Shift right values.
        DB::update('UPDATE '.$this->_tbl.' SET `rgt` = `rgt` + ? WHERE '.$repositionData->right_where["col"].' '.$repositionData->right_where["operator"].' ?', [$node->width, $repositionData->right_where["value"]]);

        /*
         * Calculate the offset between where the node used to be in the tree and
         * where it needs to be in the tree for left ids (also works for right ids).
         */
        $offset = $repositionData->new_lft - $node->lft;
        $depthOffset = $repositionData->new_depth - $node->depth;

        // Move the nodes back into position in the tree using the calculated offsets.
        DB::update('UPDATE '.$this->_tbl.' 
                    SET `rgt` = ? - `rgt`, 
                        `lft` = ? - `lft`, 
                        `depth` = `depth` + ?
                    WHERE lft < 0',
                    [$offset, $offset, $depthOffset]
                    );

        // Set the correct parent id for the moved node if required.
        if ($node->parent_id != $repositionData->new_parent_id)
        {
            $query = DB::table($this->_tbl)
                        ->where($this->_tbl_key, '=', $node->$k);;



            // Update the title and alias fields if they exist for the table.
            //$fields = $this->getFields();

            //if (property_exists($this, 'name') && $this->title !== null)
            //{
            //    $query->set('title = ' . $this->_db->quote($this->title));
            //}

            //if (array_key_exists('alias', $fields)  && $this->alias !== null)
            //{
            //    $query->set('alias = ' . $this->_db->quote($this->alias));
            //}

            //$query->set('parent_id = ' . (int) $repositionData->new_parent_id)
            //    ->where($this->_tbl_key . ' = ' . (int) $node->$k);

            $query->update(['parent_id' => $repositionData->new_parent_id]);

        }

        // Set the object values.
        $this->parent_id = $repositionData->new_parent_id;
        $this->depth = $repositionData->new_depth;
        $this->lft = $repositionData->new_lft;
        $this->rgt = $repositionData->new_rgt;

        return true;
    }

    /**
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  integer  1 + value of root rgt on success, false on failure
     *
     * @since   11.1
     * @throws  RuntimeException on database error.
     */
    public function rebuild($parentId = null, $leftId = 0, $depth = 0, $path = '')
    {
        // If no parent is provided, try to find it.
        if ($parentId === null)
        {
            // Get the root item.
            $parentId = $this->getRootId();

            if ($parentId === false)
            {
                return false;
            }
        }

        $query = DB::table($this->_tbl)
            ->select($this->_tbl_key, 'alias');

        // If the table has an ordering field, use that for ordering.
        if (property_exists($this, 'ordering'))
        {
            $query->orderBy('parent_id', 'ordering', 'lft');
        }
        else
        {
            $query->orderBy('parent_id', 'lft');
        }


        $query = $query->where('parent_id', '=', $parentId);

        $children = $query->get();

        // The right value of this node is the left value + 1
        $rightId = $leftId + 1;

        // Execute this function recursively over all children
        foreach ($children as $node)
        {

            //dd($children);
            /*
             * $rightId is the current right value, which is incremented on recursion return.
             * Increment the level for the children.
             * Add this item's alias to the path (but avoid a leading /)
             */
            $rightId = $this->rebuild($node->{$this->_tbl_key}, $rightId, $depth + 1, $path . (empty($path) ? '' : '/') . $node->alias);

            //print_r('parent: '.$parentId.' rightId:'.$rightId.'<br>');

            // If there is an update failure, return false to break out of the recursion.
            if ($rightId === false)
            {
                return false;
            }
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value.
        DB::table($this->_tbl)
            ->where($this->_tbl_key, '=', $parentId)
            ->update([
                'lft' => $leftId,
                'rgt' => $rightId,
                'depth' => $depth,
                'path' => $path,
            ]);

        //if($parentId == 44) dd(DB::getQueryLog());


        // Return the right value of this node + 1.
        return $rightId + 1;
    }

    /**
     * Gets the ID of the root item in the tree
     *
     * @return  mixed  The primary id of the root row, or false if not found and the internal error is set.
     *
     * @since   11.1
     */
    public function getRootId()
    {
        if ((int) self::$root_id > 0)
        {
            return self::$root_id;
        }

        // Get the root item.
        $k = $this->_tbl_key;

        // Test for a unique record with parent_id = 0
        $query = DB::table($this->_tbl)
            ->select($k)
            ->where('parent_id', '=', 0);

        $result = $query->pluck($k);

        if (count($result) == 1)
        {
            self::$root_id = $result[0];

            return self::$root_id;
        }

        // Test for a unique record with lft = 0
        $query = DB::table($this->_tbl)
            ->select($k)
            ->where('lft', '=', 0)
            ->where('parent_id', '=', 0);

        $result = $query->pluck($k);

        if (count($result) == 1)
        {
            self::$root_id = $result[0];

            return self::$root_id;
        }

        return false;
        die();

        $fields = $this->getFields();

        if (array_key_exists('alias', $fields))
        {
            // Test for a unique record alias = root
            $query->clear()
                ->select($k)
                ->from($this->_tbl)
                ->where('alias = ' . $this->_db->quote('root'));

            $result = $this->_db->setQuery($query)->loadColumn();

            if (count($result) == 1)
            {
                self::$root_id = $result[0];

                return self::$root_id;
            }
        }

        $e = new UnexpectedValueException(sprintf('%s::getRootId', get_class($this)));
        $this->setError($e);
        self::$root_id = false;

        return false;
    }
}
