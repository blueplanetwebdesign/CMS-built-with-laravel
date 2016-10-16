<?php

namespace Bpwd\Admin\Traits\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Session;

trait AdminListTrait{

    private static $filters = array();

    public function unpublish(Request $request, $id = null)
    {
        if($id){
            $this->main_model->where('id', $id)->update(['published' => 0]);
            $number_of_items = 1;
        }
        else{
            $this->main_model->whereIn('id', $request->id)->update(['published' => 0]);
            $number_of_items = count($request->id);
        }

        if($number_of_items == 1) $message = '1 item unpublished.';
        else $message = $number_of_items.' items unpublished.';

        return Redirect::to($this->main_model->base_url)->with('flash_message', $message);
    }

    public function publish(Request $request, $id = null)
    {
        if($id){
            $this->main_model->where('id', $id)->update(['published' => 1]);
            $number_of_items = 1;
        }
        else{
            $this->main_model->whereIn('id', $request->id)->update(['published' => 1]);
            $number_of_items = count($request->id);
        }

        if($number_of_items == 1) $message = '1 item published.';
        else $message = $number_of_items.' items published.';

        return Redirect::to($this->main_model->base_url)->with('flash_message', $message);
    }

    public function delete(Request $request)
    {
        $this->main_model->where('id', $request->id)->delete();

        $number_of_items = count($request->id);
        if($number_of_items == 1) $message = '1 item deleted.';
        else $message = $number_of_items.' items deleted.';

        return Redirect::to($this->base_url)->with('flash_message', $message);
    }

    public function setUpIndex(&$filter, &$list, &$html, &$query)
    {
        $filter_search = '';
        $display_filter_options_dropdown_container = false;

        if(is_array($filter))
        {
            $filter = array_filter($filter, function($value){
                return ($value !== null && $value !== false && $value !== '');
            });

            $filter_search = (isset($filter['search']))? $filter['search'] : null;
            unset($filter['search']);

            if(count($filter) > 0) $display_filter_options_dropdown_container = true;
        }

        $list['order'] = (isset($list['order']))? $list['order'] : null;
        $list['direction'] = (isset($list['direction']))? $list['direction'] : 'DESC';
        $list['limit'] = (isset($list['limit']))? $list['limit'] : 20;

        $html->display_filter_options_dropdown_container = $display_filter_options_dropdown_container;
        $html->filter_search = $filter_search;

        $query = $this->main_model;
        if($filter_search != '') $query = $query->where('name', 'LIKE', '%'.$filter_search.'%');
        if((isset($list['order']) && $list['order'] != null) && (isset($list['direction']) && $list['direction'] != null)) $query = $query->orderBy($list['order'], $list['direction']);
    }

    public static function addFilter($name, $label, $options, $selected, $hide_label = false)
    {
        array_push(static::$filters, array('label' => $label, 'name' => $name, 'options' => $options, 'selected' => $selected, 'hide_label' => $hide_label));
    }

    public static function getFilters()
    {
        return static::$filters;
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

        return Redirect::to($this->main_model->base_url);
    }
}