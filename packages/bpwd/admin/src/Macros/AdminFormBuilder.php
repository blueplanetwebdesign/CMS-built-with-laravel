<?php

Form::macro('adminFilterClearButton', function()
{
    return '<button class="btn btn-default list-tools-btn-clear" title="" type="button" data-original-title="Clear">
                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                Clear
            </button>';
});

Form::macro('adminFilterSelect', function($name, $values, $label, $selected = null, $hide_label = false)
{
    $label = array('' => $label);
    if($hide_label == false) $values = $label + $values;
    
    return '<div class="col-md-3 col-sm-3 col-xs-3 pull-right">'.Form::select($name, $values, $selected, array("onchange" => "this.form.submit();", "class" => "form-control")).'</div>';
});

Form::macro('adminGridSort', function($label, $name, $direction, $order)
{
    $icon = '';
    if($name == $order){
        if($direction == 'ASC'){
            $direction = 'DESC';
            $icon = ' <i class="fa fa-sort-asc"></i>';
        }
        else{
            $direction = 'ASC';
            $icon = ' <i class="fa fa-sort-desc"></i>';
        }
    }
    return '
    <a href="" class="list-tools-column-order" data-direction="'.$direction.'" data-order="'.$name.'">'.$label.$icon.'</a>
    ';
});

Form::macro('adminFilterSearch', function($value)
{
    return '
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for..." value="'.$value.'" name="filter[search]">
        <span class="input-group-btn">
          <button class="btn btn-default" type="submit">Go!</button>
        </span>
    </div>
    ';
});

Form::macro('adminListLimit', function($selected_limit)
{
    $limits[5] = 5;
    $limits[10] = 10;
    $limits[15] = 15;
    $limits[20] = 20;
    $limits[25] = 25;
    $limits[30] = 30;
    $limits[50] = 50;
    $limits[100] = 100;
    return Form::select('list[limit]', $limits, $selected_limit, array('class' => 'form-control', 'onchange' => 'this.form.submit();'));
});

Form::macro('adminCheckbox', function($name, $value, $checked = false)
{
    $checked = ($checked)? 'checked' : '';
    return '
        <input type="checkbox" class="flat" name="'.$name.'" value="'.$value.'" '.$checked.'>
    ';
});

Form::macro('adminPublishButton', function($published, $view, $id)
{
    if($published)
    {
        $published = 'unpublish';
        $checked = ' checked';
    }
    else
    {
        $published = 'publish';
        $checked = '';
    }

    return '
        <div onclick="listItemTask(\''.$published.'\', \''.$view.'\', '.$id.')" class="state iradio_flat-green'.$checked.'"></div>
    ';
});

Form::macro('adminText', function($name, $value, $label, $attributes = '')
{
    return '
    <div class="item form-group">
            '.Form::label($name, $label, ["class" => "col-md-1 col-sm-3 col-xs-12"]).'
            <div class="col-md-6 col-sm-6 col-xs-12">
                '.Form::text($name, $value, $attributes).'
            </div>
       </div>
    ';
});

Form::macro('adminSelect', function($name, $values, $label, $selected = array(), $attributes = array())
{
    $values = (is_array($values) || count($values) == 0)? $values : $values->toArray();

    return '
    <div class="item form-group">
            '.Form::label($name, $label, ["class" => "col-md-1 col-sm-3 col-xs-12"]).'
            <div class="col-md-3 col-sm-3 col-xs-12">
                '.Form::select($name, $values, $selected, $attributes).'
            </div>
       </div>
    ';
});

Form::macro('adminSelectMultiple', function($name, $values, $label, $selected = array(), $attributes = array())
{
    $values = (is_array($values) || count($values) == 0)? $values : $values->toArray();

    return '
    <div class="item form-group">
            '.Form::label($name, $label, ["class" => "col-md-1 col-sm-3 col-xs-12"]).'
            <div class="col-md-6 col-sm-6 col-xs-12">
                '.Form::select($name, $values, $selected, $attributes).'
            </div>
       </div>
    ';
});

Form::macro('adminTextarea', function($name, $value, $label, $attributes = '')
{
    return '
    <div class="item form-group">
            '.Form::label($name, $label, ["class" => "col-md-1 col-sm-3 col-xs-12"]).'
            <div class="col-md-6 col-sm-6 col-xs-12">
                '.Form::textarea($name, $value, $attributes).'
            </div>
       </div>
    ';
});

Form::macro('adminEditor', function($name, $value, $label)
{
    return '
    <div class="item form-group">
            '.Form::label($name, $label, ["class" => "col-md-1 col-sm-3 col-xs-12"]).'
            <div class="col-md-6 col-sm-6 col-xs-12">
                    <textarea name="'.$name.'" class="form-control editor">'.$value.'</textarea>
            </div>
    </div>
    ';
});