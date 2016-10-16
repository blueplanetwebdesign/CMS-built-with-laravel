<!doctype html>
<html>
<head>
    <title> Admin | @yield( 'title' ) </title>
    <link rel="shortcut icon" href="{{ asset('assets/admin/img/favicon.ico') }}">
    @section('admin::head')
        @include('admin::Includes/Default/Head')
    @show
</head>
<body class="nav-md">
<div class="container body">
    <div class="main_container">
        @include('admin::Includes/Default/LeftCol')
        @include('admin::Includes/Default/TopNavigation')
        <div class="right_col" role="main">
            @yield('admin::content')
        </div>
        @include('admin::Includes/Default/Footer')
    </div>
</div>
@include('admin::Includes/Default/Compose')

@section('admin::scripts')
@include('admin::Includes/Default/Scripts')
@show

</body>
</html>