<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="index3.html" class="nav-link">Home</a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li>
            <div class="margin">
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
                        Super Admin
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" role="menu">
                        <a href="" class="dropdown-item">
                            <i class="fas fa-user mr-2"></i> {{ __('My Profile') }}
                        </a>
                        <a href="" class="dropdown-item">
                            <i class="fas fa-sliders-h mr-2"></i> {{ __('System Settings') }}
                        </a>
                        <a id="header-logout" href="" onclick="event.preventDefault()" data-toggle="modal"
                            data-target="#modal-logout" class="dropdown-item">
                            <i class="fa fa-power-off mr-2"></i> {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</nav>
