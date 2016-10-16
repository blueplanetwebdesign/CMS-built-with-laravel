@extends('admin::Layouts.Default')

@section('title')
    {{ $html->title }}
@stop

@section('admin::head')
    <!-- Select2 -->
    <link href="{{URL::asset('assets/admin/vendors/select2/dist/css/select2.min.css')}}" rel="stylesheet">
    @parent

@endsection

@section('admin::content')
    <div class="x_panel">

        @include('admin::Includes/Default/Partials/ErrorMessages')
        @include('admin::Includes/Default/Partials/FlashMessage')

        <div class="form-group">
            <div class="clearfix"></div>
            <div class="page-title"><h3>{{ $html->title }}</h3></div>

            <div class="">
                @foreach ($toolbar_slots as $toolbar_slot)
                    <?= $toolbar_slot ?>
                @endforeach
            </div>
        </div>
        <!-- page content -->
        {!! Form::open(['url' => $html->base_url, 'id' => 'admin-form', 'class' => 'form-horizontal form-label-left']) !!}
            @yield('admin::edit.content')
        {{ Form::hidden('id', $data->id) }}
        {{ Form::hidden('task', '') }}

        {!! Form::close() !!}
        <!-- /page content -->
    </div>
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

    <!-- tinymce-wysiwyg -->
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script>
        var editor_config = {
            path_absolute : "/",
            selector: "textarea.editor",
            plugins: [
                "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code fullscreen",
                "insertdatetime media nonbreaking save table contextmenu directionality",
                "emoticons template paste textcolor colorpicker textpattern"
            ],
            toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
            relative_urls: false,
            file_browser_callback : function(field_name, url, type, win) {
                var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

                var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;

                if (type == 'image') {
                    cmsURL = cmsURL + "&type=Images";
                } else {
                    cmsURL = cmsURL + "&type=Files";
                }

                tinyMCE.activeEditor.windowManager.open({
                    file : cmsURL,
                    title : 'Filemanager',
                    width : x * 0.8,
                    height : y * 0.8,
                    resizable : "yes",
                    close_previous : "no"
                });
            }
        };

        tinymce.init(editor_config);
    </script>
    <!-- /tinymce-wysiwyg -->

    <!-- validator -->
    <script src="{{URL::asset('assets/admin/vendors/validator/validator.min.js')}}"></script>

    <script>

        $('div.flash-message').not('.alert-important').delay(3000).slideUp(300);

        function submitToolbarButton(task){

            jQuery(document).ready(function($){

                if(task == 'close') return location.href = $('#admin-form').attr('action');

                if (!validator.checkAll($('#admin-form'))) return false;

                $('#admin-form input[name="task"]').val(task);
                $('#admin-form').submit();

            });
        }

    </script>

@endsection
