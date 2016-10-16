@extends('admin::Layouts.List')
@section('admin::list.content')
    <table class="table table-striped jambo_table bulk_action">
        <thead>
        <tr class="headings">
            <th width="1%"><input type="checkbox" id="check-all" class="flat"></th>
            <th class="column-title column-order"><?= Form::adminGridSort('Name', 'name', $list['direction'], $list['order']); ?></th>
            <th class="column-title column-order"><?= Form::adminGridSort('Slug', 'slug', $list['direction'], $list['order']); ?></th>
            <th class="column-title column-order" width="1%" style="min-width:90px"><?= Form::adminGridSort('Published', 'published', $list['direction'], $list['order']); ?></th>
            <th class="column-title column-order last" width="1%" style="min-width:45px"><?= Form::adminGridSort('ID', 'id', $list['direction'], $list['order']); ?></th>
            <th class="bulk-actions" colspan="7">
                <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
            </th>
        </tr>
        </thead>

        <tbody>

        @foreach ($data as $row)
            <tr class="even pointer">
                <td class="a-center "><?= Form::adminCheckbox('id[]', $row->id); ?></td>
                <td class=" "><a href="{{ URL::to($html->base_url.'/'.$row->id.'/edit') }}">{{ $row->name }}</a></td>
                <td class=" ">{{ $row->slug }}</td>
                <td class="center"><?= Form::adminPublishButton($row->published, 'products', $row->id); ?></td>
                <td class=" ">{{ $row->id }}</td>
            </tr>
        @endforeach

        </tbody>
    </table>
@stop