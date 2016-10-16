@extends('admin::Layouts.Edit')
@section('admin::edit.content')

        <!-- Smart Wizard -->

        <!-- Title form input -->
        <?= Form::adminText('name', $data->name, 'Name:*', ["class" => "form-control", "required" => 'required']); ?>
        <?= Form::adminText('page_title', $data->page_title, 'Page Title:', ["class" => "form-control"]); ?>
        <?= Form::adminText('meta_description', $data->meta_description, 'Meta Description:', ["class" => "form-control"]); ?>
        <?= Form::adminText('slug', $data->slug, 'Slug:', ["class" => "form-control"]); ?>
        <div class="clearfix">&nbsp;</div>
        <div id="wizard" class="form_wizard wizard_horizontal">
                <ul class="wizard_steps">
                        <li>
                                <a href="#step-1">
                                        <span class="step_no">1</span>
                                        <span class="step_descr">
                                              Step 1<br />
                                              <small>Select category</small>
                                          </span>
                                </a>
                        </li>
                        <li>
                                <a href="#step-2">
                                        <span class="step_no">2</span>
                                        <span class="step_descr">
                                              Step 2<br />
                                              <small>Select layout</small>
                                          </span>
                                </a>
                        </li>
                        <li>
                                <a href="#step-3">
                                        <span class="step_no">3</span>
                                        <span class="step_descr">
                                              Step 3<br />
                                              <small>Fill out fields</small>
                                          </span>
                                </a>
                        </li>
                </ul>
                <div id="step-1"></div>
                <div id="step-2"></div>
                <div id="step-3"></div>
        </div>



@stop

@section('admin::scripts')
        @parent
        <!-- jQuery Smart Wizard -->
        <script src="{{URL::asset('assets/admin/vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js')}}"></script>

        <!-- jQuery Smart Wizard -->
        <script>
                $(document).ready(function() {
                        $('#wizard').smartWizard({
                                onFinish: function (objs, context) {
                                        alert('test');
                                        return true;
                                },
                                ajaxType: 'GET',
                                contentURLData: function(step_number){

                                        data = new Object();
                                        data['step_number'] = step_number;
                                        data['id'] = {{ (int)$data->id }};

                                        if(step_number >= 2) data['category_id'] = $('#category_id option:selected').val();
                                        if(step_number >= 3) data['layout_id'] = $('#layout_id option:selected').val();

                                        console.log(data);

                                        return { data };
                                },
                                contentURL:'/admin/content/content/steps',
                                contentCache:false,
                                selected: {{ $html->initially_selected_step }}
                        });

                        $('.buttonNext').addClass('btn btn-success');
                        $('.buttonPrevious').addClass('btn btn-primary');
                        $('.buttonFinish').addClass('btn btn-default');
                });
        </script>
        <!-- /jQuery Smart Wizard -->

@endsection