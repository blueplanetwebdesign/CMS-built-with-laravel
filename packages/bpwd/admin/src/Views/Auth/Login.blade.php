@extends('admin::Layouts.Auth')

@section('admin::content')

    <div class="login_wrapper">
            <section class="login_content">
                <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/login') }}">
                    {{ csrf_field() }}
                    <h1>Login Form</h1>
                    <div>
                        <input type="email" class="form-control" placeholder="Email" name="email" value="{{ old('email') }}">

                        @if ($errors->has('email'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div>
                        <input type="password" class="form-control" placeholder="Password" name="password">

                        @if ($errors->has('password'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-btn fa-sign-in"></i>Login
                        </button>
                        <a class="reset_pass" href="{{URL::to('admin/password/reset')}}">Lost your password?</a>
                    </div>

                    <div class="clearfix"></div>

                    <div class="separator">
                        <p class="change_link">New to site?
                            <a href="#signup" class="to_register"> Create Account </a>
                        </p>

                        <div class="clearfix"></div>
                        <br />

                        <div>
                            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
                            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 3 template. Privacy and Terms</p>
                        </div>
                    </div>
                </form>
            </section>
    </div>
@endsection
