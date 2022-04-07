<div class="sidebar sidebar-dark sidebar-main sidebar-fixed sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        {{ config('app.name', 'Env Friends') }}
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">

                    <div class="media-body">
                        <div class="media-title font-weight-semibold"><span>Welcome</span>, {{auth()->user()->name}}
                        </div>

                    </div>


                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->

                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link ">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @hasanyrole('admin')
                <li class="nav-item ">
                    <a href="{{route('advertisements.index')}}"
                       class="nav-link  {{request()->routeIs('advertisements.*')?'active' :'' }}">
                        <i class="icon-megaphone"></i>
                        <span>Manage Advertisements</span>
                    </a>
                </li>
                @endhasanyrole
                <li class="nav-item ">
                    <a href="{{route('categories.index')}}"
                       class="nav-link  {{request()->routeIs('categories.*')?'active' :'' }}">
                        <i class="icon-stack3"></i>
                        <span>Manage Categories</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('products.index')}}"
                       class="nav-link  {{request()->routeIs('products.*')?'active' :'' }}">
                        <i class="icon-dropbox"></i>
                        <span>Manage Products</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('orders.index')}}"
                       class="nav-link  {{request()->routeIs('orders.*')?'active' :'' }}">
                        <i class="icon-cart5"></i>
                        <span>Manage Orders</span>
                    </a>
                </li>
                @hasanyrole('admin')
                <li class="nav-item ">
                    <a href="{{route('settings.edit',0)}}"
                       class="nav-link  {{request()->routeIs('edit.*')?'active' :'' }}">
                        <i class="icon-coins"></i>
                        <span>Edit order fees</span>
                    </a>
                </li>
                @endhasanyrole
                @hasanyrole('admin')
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Settings</div>
                    <i class="icon-menu" title="Admin"></i>
                </li>
                <li class="nav-item ">
                    <a href="{{route('users.index')}}"
                       class="nav-link  {{request()->routeIs('users.*')?'active' :'' }}">
                        <i class="icon-user"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('about_us.create')}}"
                       class="nav-link  {{request()->routeIs('about_us.*')?'active' :'' }}">
                        <i class="icon-info22"></i>
                        <span>About us</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('contact_us.index')}}"
                       class="nav-link  {{request()->routeIs('contact_us.*')?'active' :'' }}">
                        <i class="icon-envelop"></i>
                        <span>Contact us</span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('telescope_view')}}"
                       class="nav-link  {{request()->routeIs('telescope_view*')?'active' :'' }}">
                        <i class="icon-eye"></i>
                        <span>Telescope</span>
                    </a>
                </li>



                <li class="nav-item d-none">
                    <a href="{{route('users_groups.index')}}"
                       class="nav-link  {{request()->routeIs('users_groups.*')?'active' :'' }}">
                        <i class="icon-users4"></i>
                        <span>Manage Groups</span>
                    </a>
                </li>
                <li class="nav-item d-none">
                    <a href="{{route('users_groups_permissions.index')}}"
                       class="nav-link  {{request()->routeIs('users_groups_permissions.*')?'active' :'' }}">
                        <i class="icon-user-tie"></i>
                        <span>Manage Permissions</span>
                    </a>
                </li>

                @endhasanyrole


            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
