@extends('admin::Layouts.Edit')
@section('admin::edit.content')

        <!-- Name form input -->
<?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

        <!-- Name form input -->
<?= Form::adminText('slug', $data->slug, 'Slug:', ["class" => "form-control"]); ?>

@stop