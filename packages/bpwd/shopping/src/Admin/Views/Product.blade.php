@extends('admin::Layouts.Edit')
@section('admin::edit.content')

        <!-- Title form input -->
        <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

        <!-- Content form input -->
        <?= Form::adminText('price', $data->price, 'Price:'); ?>

        <!-- Content form input -->
        <?= Form::adminEditor('description', $data->description, 'Description:'); ?>

        <!-- Categories select -->
        <?= Form::adminSelect('categories', $data->category_list, "Categories:", $data->selected_categories, ["multiple" => "multiple", "name" => "categories[]", "class" => "select2_multiple form-control"]); ?>

@stop