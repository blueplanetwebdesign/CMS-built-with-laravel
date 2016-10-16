<div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{URL::to('admin')}}" class="site_title"><i class="fa fa-cubes"></i> <span>Admin</span></a>
        </div>

        <div class="clearfix"></div>

{{--
<!-- menu profile quick info -->
<div class="profile">
    <div class="profile_pic">
        <img src="images/img.jpg" alt="..." class="img-circle profile_img">
    </div>
    <div class="profile_info">
        <span>Welcome,</span>
        <h2>John Doe</h2>
    </div>
</div>
<!-- /menu profile quick info -->
--}}

<br />

<!-- sidebar menu -->
<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
        <h3>General</h3>
        <ul class="nav side-menu">
            <li><a><i class="fa fa-file-text"></i> Content <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{URL::to('admin/content/content')}}">Content</a></li>
                    <li><a href="{{URL::to('admin/content/categories')}}">Categories</a></li>
                    <li><a href="{{URL::to('admin/content/tags')}}">Tags</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-file-image-o"></i> Media <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{URL::to('admin/media/manager')}}">Manager</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-shopping-cart "></i> Shopping <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{URL::to('admin/shopping/products')}}">Products</a></li>
                    <li><a href="{{URL::to('admin/shopping/categories')}}">Categories</a></li>
                    <li><a href="{{URL::to('admin/shopping/orders')}}">Orders</a></li>
                    <li><a href="{{URL::to('admin/shopping/settings')}}">Settings</a></li>
                </ul>
            </li>

        </ul>
    </div>
    <div class="menu_section">
        <h3>System</h3>
        <ul class="nav side-menu">
            <li><a><i class="fa fa-sort-alpha-asc"></i> Menus <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{URL::to('admin/menus/menu')}}">Menus</a></li>
                    <li><a href="{{URL::to('admin/menus/type')}}">Types</a></li>
                </ul>
            </li>
            <li><a><i class="fa fa-dropbox"></i> Modules <span class="fa fa-chevron-down"></span></a>
                <ul class="nav child_menu">
                    <li><a href="{{URL::to('admin/modules/modules')}}">Modules</a></li>
                </ul>
            </li>
            @can('access-user-manager')
                <li><a><i class="fa fa-users"></i> Users <span class="fa fa-chevron-down"></span></a>
                    <ul class="nav child_menu">
                        <li><a href="{{URL::to('admin/users/users')}}">Users</a></li>
                        <li><a href="{{URL::to('admin/users/roles')}}">Roles</a></li>
                        <li><a href="{{URL::to('admin/users/permissions')}}">Permissions</a></li>
                    </ul>
                </li>
            @endcan
        </ul>
    </div>

</div>
<!-- /sidebar menu -->

<!-- /menu footer buttons -->
<div class="sidebar-footer hidden-small">
    {{--
    <a data-toggle="tooltip" data-placement="top" title="Settings">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="FullScreen">
        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
    </a>
    <a data-toggle="tooltip" data-placement="top" title="Lock">
        <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
    </a>
    --}}
    <a href="{{URL::to('admin/logout')}}" data-toggle="tooltip" data-placement="top" title="Logout">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
    </a>
</div>
<!-- /menu footer buttons -->
</div>
</div>