<!doctype html>
<html>
<head>
    @section('admin::Auth/Head')
        @include('admin::Includes/Default/Head')
    @show
</head>
<body class="login">
<div class="container body">
    <div class="main_container">
        @yield('admin::content')
    </div>
</div>

</body>
</html>