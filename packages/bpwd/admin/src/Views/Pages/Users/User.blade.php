@extends('admin::Layouts.Edit')
@section('admin::edit.content')

    <!-- First Name form input -->
    <?= Form::adminText('first_name', $data->first_name, 'First Name:*', ["class" => "form-control", "required" => 'required']); ?>

    <!-- Last Name form input -->
    <?= Form::adminText('last_name', $data->last_name, 'Last Name:*', ["class" => "form-control", "required" => 'required']); ?>

    <!-- Categories select -->
    <?= Form::adminSelect('roles', $data->role_list, "Roles:", $data->selected_roles, ["multiple" => "multiple", "name" => "roles[]", "class" => "select2_multiple form-control"]); ?>

@stop