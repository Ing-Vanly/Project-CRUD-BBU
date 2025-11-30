<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ $appBusinessSetting && $appBusinessSetting->logo ? asset($appBusinessSetting->logo) : asset('AdminLTE/dist/img/user2-160x160.jpg') }}"
                    class="img-circle elevation-2" alt="{{ __('Business logo') }}">
            </div>
            <div class="info">
                @if ($appBusinessSetting && $appBusinessSetting->name)
                    <a href="{{ route('dashboard') }}" class="d-block">{{ $appBusinessSetting->name }}</a>
                @endif
            </div>
        </div>
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="{{ __('Search') }}"
                    aria-label="{{ __('Search') }}">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @can('dashboard.view')
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>{{ __('Dashboard') }}</p>
                        </a>
                    </li>
                @endcan
                @canany(['user.view', 'role.view'])
                    <li
                        class="nav-item {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('role.*') || request()->routeIs('user.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                {{ __('Manage Users') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('user.view')
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}"
                                        class="nav-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                                        <i class="fas fa-table nav-icon"></i>
                                        <p>{{ __('Table Users') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('role.view')
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}"
                                        class="nav-link {{ request()->routeIs('role.*') ? 'active' : '' }}">
                                        <i class="fas fa-user-shield nav-icon"></i>
                                        <p>{{ __('Roles') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['category.view', 'unit.view', 'brand.view', 'product.view', 'order.view'])
                    <li
                        class="nav-item {{ request()->routeIs('category.*') || request()->routeIs('unit.*') || request()->routeIs('brand.*') || request()->routeIs('product.*') || request()->routeIs('order.*') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->routeIs('category.*') || request()->routeIs('unit.*') || request()->routeIs('brand.*') || request()->routeIs('product.*') || request()->routeIs('order.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-boxes"></i>
                            <p>
                                {{ __('Manage Products') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('category.view')
                                <li class="nav-item">
                                    <a href="{{ route('category.index') }}"
                                        class="nav-link {{ request()->routeIs('category.*') ? 'active' : '' }}">
                                        <i class="fas fa-tags nav-icon"></i>
                                        <p>{{ __('Category') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('unit.view')
                                <li class="nav-item">
                                    <a href="{{ route('unit.index') }}"
                                        class="nav-link {{ request()->routeIs('unit.*') ? 'active' : '' }}">
                                        <i class="fas fa-balance-scale nav-icon"></i>
                                        <p>{{ __('Unit') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('brand.view')
                                <li class="nav-item">
                                    <a href="{{ route('brand.index') }}"
                                        class="nav-link {{ request()->routeIs('brand.*') ? 'active' : '' }}">
                                        <i class="fas fa-certificate nav-icon"></i>
                                        <p>{{ __('Brand') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('product.view')
                                <li class="nav-item">
                                    <a href="{{ route('product.index') }}"
                                        class="nav-link {{ request()->routeIs('product.*') ? 'active' : '' }}">
                                        <i class="fas fa-cube nav-icon"></i>
                                        <p>{{ __('Product') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('order.view')
                                <li class="nav-item">
                                    <a href="{{ route('order.index') }}"
                                        class="nav-link {{ request()->routeIs('order.*') ? 'active' : '' }}">
                                        <i class="fas fa-clipboard-list nav-icon"></i>
                                        <p>{{ __('Orders') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @can('post.view')
                    <li class="nav-item {{ request()->routeIs('post.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('post.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>
                                {{ __('Manage Posts') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('post.index') }}"
                                    class="nav-link {{ request()->routeIs('post.index') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>{{ __('All Posts') }}</p>
                                </a>
                            </li>
                            @can('post.create')
                                <li class="nav-item">
                                    <a href="{{ route('post.create') }}"
                                        class="nav-link {{ request()->routeIs('post.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus-circle nav-icon"></i>
                                        <p>{{ __('Create Post') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('author.view')
                    <li class="nav-item {{ request()->routeIs('author.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('author.*') ? 'active' : '' }}">
                            <i class="fas fa-user-tie nav-icon"></i>
                            <p>
                                {{ __('Manage Authors') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('author.create')
                                <li class="nav-item">
                                    <a href="{{ route('author.create') }}"
                                        class="nav-link {{ request()->routeIs('author.create') || request()->routeIs('author.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus-circle nav-icon"></i>
                                        <p>{{ __('Add Author') }}</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="{{ route('author.index') }}"
                                    class="nav-link {{ request()->routeIs('author.index') || request()->routeIs('author.edit') || request()->routeIs('author.show') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>{{ __('Author List') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('business_location.view')
                    {{-- <li class="nav-item {{ request()->routeIs('business-location.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('business-location.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-store"></i>
                            <p>
                                {{ __('Business Locations') }}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('business-location.index') }}"
                                    class="nav-link {{ request()->routeIs('business-location.index') || request()->routeIs('business-location.edit') || request()->routeIs('business-location.show') ? 'active' : '' }}">
                                    <i class="fas fa-list nav-icon"></i>
                                    <p>{{ __('All Locations') }}</p>
                                </a>
                            </li>
                            @can('business_location.create')
                                <li class="nav-item">
                                    <a href="{{ route('business-location.create') }}"
                                        class="nav-link {{ request()->routeIs('business-location.create') ? 'active' : '' }}">
                                        <i class="fas fa-plus-circle nav-icon"></i>
                                        <p>{{ __('Add Location') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li> --}}
                @endcan
                @can('business_setting.view')
                    <li class="nav-item">
                        <a href="{{ route('business-setting.edit') }}"
                            class="nav-link {{ request()->routeIs('business-setting.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>{{ __('Business Settings') }}</p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>
