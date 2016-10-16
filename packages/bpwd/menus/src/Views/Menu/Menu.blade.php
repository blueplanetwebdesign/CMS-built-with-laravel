@extends('admin::Layouts.Edit')
@section('admin::edit.content')

        <!-- Title form input -->
        <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>

        <!-- Alias form input -->
        <?= Form::adminText('slug', $data->slug, 'Slug:', ["class" => "form-control"]); ?>

        <!-- Categories select -->
        <?= Form::adminSelect('type_id', $data->type_list, "Type:", $data->type_id); ?>

        <!-- Categories select -->
        <?= Form::adminSelect('parent_id', $data->parent_list, "Parent:", $data->parent_id); ?>

        <!-- Categories select -->
        @if (count($data->ordering_list) > 0 && $data->ordering_list)
                <?= Form::adminSelect('ordering', $data->ordering_list, "Order:", $data->id); ?>
        @else
            <label class="col-md-1 col-sm-3 col-xs-12" for="type_id">Ordering:</label>
            <div class="col-md-3 col-sm-3 col-xs-12">Ordering will be available after saving.</div>
        @endif


@stop