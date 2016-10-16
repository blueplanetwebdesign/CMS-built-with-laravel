@extends('admin::Layouts.Edit')
@section('admin::edit.content')

    {!! Form::open(['url' => $html->base_url, 'id' => 'admin-form', 'class' => 'form-horizontal form-label-left']) !!}

    <!-- Title form input -->
    <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

    <!-- Categories select -->
    <?= Form::adminSelect('layout_id', $data->layout_list, "Layout:", $data->layout_id, ["class" => "form-control"]); ?>

    {{ Form::hidden('id', $data->id) }}
    {{ Form::hidden('task', '') }}

    {!! Form::close() !!}

@stop