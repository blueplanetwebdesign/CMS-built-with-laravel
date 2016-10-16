@extends('admin::Layouts.Default')

@section('title')
  Media Manager
@stop

@section('admin::head')
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="{{ asset('assets/admin/vendors/unisharp/css/cropper.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/admin/vendors/unisharp/css/lfm.css') }}">
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.css">
  @parent
@endsection

@section('admin::content')
  <div class="page-title">
    <div class="title_left">
      <h3>Media Manager</h3>
    </div>
  </div>
  <div class="clearfix"></div>

  <div class="form-group">
    <div class="pull-left">
      <button id="to-previous" class="btn btn-default" aria-label="Left Align" type="button">
        <span class="fa fa-arrow-left" aria-hidden="true"></span>
        Back
      </button>
      <span>|&nbsp;</span>
      <button id="add-folder" class="btn btn-default" aria-label="Left Align" type="button">
        <span class="fa fa-plus" aria-hidden="true"></span>
        New Folder
      </button>
      <button id="upload-btn" class="btn btn-default" aria-label="Left Align" type="button">
        <span class="fa fa-upload" aria-hidden="true"></span>
        Upload
      </button>
      <span>|&nbsp;</span>
      <button id="thumbnail-display" class="btn btn-default" aria-label="Left Align" type="button">
        <span class="fa fa-picture-o" aria-hidden="true"></span>
        Thumbnails
      </button>
      <button id="list-display" class="btn btn-default" aria-label="Left Align" type="button">
        <span class="fa fa-list" aria-hidden="true"></span>
        List
      </button>
    </div>
  </div>
  <div class="clearfix"></div>



          <div class="row fill">
            <div class="x_panel fill">
              <div class="col-md-2 col-lg-2 col-sm-2 col-xs-2 left-nav fill" id="lfm-leftcol">
                <div id="tree1">
                </div>
              </div>
              <div class="col-md-10 col-lg-10 col-sm-10 col-xs-10 right-nav" id="right-nav">
                <div class="row">
                  <div class="col-md-12">
                    @if($extension_not_found)
                      <div class="alert alert-warning"><i class="glyphicon glyphicon-exclamation-sign"></i> Please install gd or imagick extension to crop, resize, and make thumbnails of images.</div>
                    @endif

                  </div>
                </div>

                @if (isset($errors) && $errors->any())
                  <div class="row">
                    <div class="col-md-12">
                      <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <ul>
                          @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        </ul>
                      </div>
                    </div>
                  </div>
                @endif

                <div id="content" class="fill" style="padding-bottom: 200px">

                </div>
              </div>
            </div>
          </div>


  <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aia-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Upload File</h4>
        </div>
        <div class="modal-body">
          <form action="{{ route('media.manager.upload') }}" role='form' id='uploadForm' name='uploadForm' method='post' enctype='multipart/form-data'>
            <div class="form-group" id="attachment">
              <label for='upload' class='control-label'>Choose File</label>
              <div class="controls">
                <div class="input-group" style="width: 100%">
                  <input type="file" id="upload" name="upload">
                </div>
              </div>
            </div>
            <input type='hidden' name='working_dir' id='working_dir' value='{{$working_dir}}'>
            <input type='hidden' name='show_list' id='show_list' value='0'>
            <input type='hidden' name='type' id='type' value='{{$file_type}}'>
            <input type='hidden' name='_token' value='{{csrf_token()}}'>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="upload-btn">Upload File</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="fileViewModal" tabindex="-1" role="dialog" aria-labelledby="fileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="fileLabel">View File</h4>
        </div>
        <div class="modal-body" id="fileview_body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
@stop

@section('admin::scripts')
  @parent
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.4.0/jquery-migrate.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.3.0/bootbox.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
  <script src="{{ asset('assets/admin/vendors/unisharp/js/cropper.min.js') }}"></script>
  <script src="{{ asset('assets/admin/vendors/unisharp/js/jquery.form.min.js') }}"></script>
  @include('media::script')

@endsection

