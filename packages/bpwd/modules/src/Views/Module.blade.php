@extends('admin::Layouts.Edit')
@section('admin::edit.content')

        <!-- Title form input -->
        <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

        <!-- Content form input -->
        <?= Form::adminEditor('description', $data->description, 'Description:'); ?>

@stop