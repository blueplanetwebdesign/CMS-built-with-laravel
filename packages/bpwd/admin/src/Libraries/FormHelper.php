<?php

namespace Bpwd\Admin\Libraries;

use Form;

class FormHelper
{
    private static $layout = array();
    private static $fields = array();

    static public function getForm($form_directory, $field_data = null)
    {
        //$layout_directory = base_path('packages/bpwd/pages/src/Layouts/');
        $layout = array();
        $fields = array();
        $xml = simplexml_load_file($form_directory);

        foreach($xml->children() AS $fieldsets){
            
            $fieldset_name = (string)$fieldsets->attributes()->name;
            $layout[$fieldset_name]['label'] = (string)$fieldsets->attributes()->label;
            
            $i = 0;
            foreach($fieldsets->field AS $field){
                
                $name = (string)$field->attributes()->name;
                
                $layout[$fieldset_name]['fields'][$i] = new \StdClass;
                $fields[$name] = new \StdClass;
                $layout[$fieldset_name]['fields'][$i]->name = $fields[$name]->name = $name;
                $layout[$fieldset_name]['fields'][$i]->type = $fields[$name]->type = (string)$field->attributes()->type;
                $layout[$fieldset_name]['fields'][$i]->label = $fields[$name]->label = (string)$field->attributes()->label;
                $layout[$fieldset_name]['fields'][$i]->required = $fields[$name]->required = (string)$field->attributes()->required;
                $layout[$fieldset_name]['fields'][$i]->value = (isset($field_data[$name]))? $field_data[$name] : '';
                
                ++ $i;
            }   
        }

        self::$layout = $layout;
        self::$fields = $fields;
    }

    public function getField($field)
    {
        if($field->type == 'editor') return Form::adminEditor($field->name, $field->value, $field->label);
    }

    public function getAllFields()
    {
        return self::$fields;
    }

    public function getLayout()
    {
        return self::$layout;
    }

}