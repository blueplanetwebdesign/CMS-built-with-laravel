@extends('admin::Layouts.Default')

@section('title')
    {{ $html->title }}
@stop

@section('admin::head')
    @parent
    <!-- iCheck -->
    <link href="{{URL::asset('assets/admin/vendors/iCheck/skins/flat/green.css')}}" rel="stylesheet">
    <!-- Select2 -->
    <link href="{{URL::asset('assets/admin/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    <!-- Custom list view -->
    <link href="{{URL::asset('assets/admin/build/css/list-view.css')}}" rel="stylesheet">
@endsection

@section('admin::content')
<!-- page content -->
    {!! Form::open(['url' => $html->base_url.'/list-update', 'id' => 'admin-form', 'class' => 'form-horizontal form-label-left']) !!}
    <div class="page-title">
        <div class="title_left">
            <h3>{{ $html->title }}</h3>
        </div>

        <div class="title_right">
            <div class="col-md-7 col-sm-7 col-xs-12 form-group pull-right">
                <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                    <?= Form::adminListLimit($list['limit']); ?>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3 pull-right">
                    <?= Form::adminFilterClearButton(); ?>
                </div>
                <div class="col-md-5 col-sm-5 col-xs-5 pull-right top_search">
                    <?= Form::adminFilterSearch($html->filter_search); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>
    <div class="form-group">
        <div class="pull-left">
            @foreach ($toolbar_slots as $toolbar_slot)
                <?= $toolbar_slot ?>
            @endforeach
        </div>
        <div class="pull-right">
            @if(count($filters) > 0)
            <button class="btn btn-default list-tools-btn-filter">
                Search Tools
                <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
            </button>
            @endif
        </div>
    </div>

    <div class="clearfix"></div>
    @if(count($filters) > 0)
    <div id="filter-options-dropdown-container" class="pull-right col-md-7 col-sm-7 col-xs-12 form-group" @if($html->display_filter_options_dropdown_container == false) style="display: none" @endif>
        <div>
            @foreach ($filters as $filter)
                <?= Form::adminFilterSelect($filter['name'], $filter['options'], $filter['label'], $filter['selected'], $filter['hide_label']) ?>
            @endforeach
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                @include('admin::Includes/Default/Partials/FlashMessage')
                <div class="x_content">
                    @yield('admin::list.content')
                </div>
            </div>
        </div>
    </div>
    {{ Form::hidden('list[order]', $list['order']) }}
    {{ Form::hidden('list[direction]', $list['direction']) }}
    {{ Form::hidden('task', 'list-update') }}
    {{ Form::hidden('action-plain', $html->base_url) }}

    {!! with(new Bpwd\Admin\Helpers\Pagination($data))->render(); !!}
    {!! Form::close() !!}
<!-- /page content -->
@stop

@section('admin::scripts')
    @parent

    <!-- Select2 -->
    <script src="{{URL::asset('assets/admin/vendors/select2/dist/js/select2.full.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $(".select2_single").select2({
                placeholder: "Select a state",
                allowClear: true
            });
            $(".select2_group").select2({});
            $(".select2_multiple").select2({
                maximumSelectionLength: 4,
                placeholder: "With Max Selection limit 4",
                allowClear: true
            });
        });
    </script>
    <!-- /Select2 -->

    <!-- iCheck -->
    <script src="{{URL::asset('assets/admin/vendors/iCheck/icheck.min.js')}}"></script>
    <!-- /iCheck -->

    <script>
        $('div.flash-message').not('.alert-important').delay(3000).slideUp(300);
        function submitToolbarButton(task, view = null){

            jQuery(document).ready(function($){

                switch(String(task)) {
                    case 'new':
                        return window.location = view+"/create";
                        break;
                    case 'rebuild':

                        break;
                    default:
                        number_of_checked_rows = $('#admin-form input[name="id[]"]:checked').length;

                        if(number_of_checked_rows == 0){
                            return alert('Please first make a selection from the list.');
                        }
                        else if(number_of_checked_rows > 1 && task == 'edit'){
                            return alert('Please select one row only from the list.');
                        }
                }

                if(task == 'edit'){
                    id = '/'+$('#admin-form input[name="id[]"]:checked').val();
                    return window.location = view+id+"/edit";
                }

                //alert($('#admin-form input[name="action-plain"]').val()+'/'+task);

                $('#admin-form').attr('action', '/'+$('#admin-form input[name="action-plain"]').val()+'/'+task);
                $('#admin-form').submit();
            });
        }

        function listItemTask(task, view, id){
            return window.location = view+"/"+id+"/"+task;
        }

        jQuery(document).ready(function($){

            //$("#admin-form").submit(function() {
                //e.preventDefault();
            //    $(this).attr('action', $(this).attr('action')+'/'+$(this).find('input[name="task"]').val());
                //$(this).submit();
            //});

            $('.list-tools-column-order').click(function(e){
                e.preventDefault();

                $('input[name="list[order]"]').val($(this).data( "order" ));
                $('input[name="list[direction]"]').val($(this).data( "direction" ));
                $('#admin-form').submit();
            });

            $('.list-tools-btn-filter').click(function(e){
                e.preventDefault();
                $('#filter-options-dropdown-container').slideToggle();

            });

            $('.list-tools-btn-clear').click(function(e){
                e.preventDefault();
                $('#filter-options-dropdown-container select').each(function() {
                    $(this).find('option:first').attr('selected', 'selected');
                });
                $('input[name="filter[search]"]').val('');

                $('#admin-form').submit();
            });

        });



    </script>
@endsection