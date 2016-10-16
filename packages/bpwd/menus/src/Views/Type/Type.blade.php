@extends('admin::Layouts.Edit')
@section('admin::edit.content')

    {!! Form::open(['url' => $html->base_url, 'id' => 'admin-form', 'class' => 'form-horizontal form-label-left']) !!}

    <!-- Title form input -->
    <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

    {{ Form::hidden('id', $data->id) }}
    {{ Form::hidden('task', '') }}

    {!! Form::close() !!}

@stop