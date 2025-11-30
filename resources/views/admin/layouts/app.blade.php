<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php($businessName = $appBusinessSetting->name ?? config('app.name', 'Admin Dashboard'))
    <title>{{ $businessName }}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/jqvmap/jqvmap.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    {{-- Bootstrat 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    @if ($appBusinessSetting && $appBusinessSetting->favicon)
        <link rel="icon" type="image/png" href="{{ asset($appBusinessSetting->favicon) }}">
    @endif

    @stack('css')
    <style>
        /* Main menu active styling */
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
        .sidebar-dark-primary .nav-sidebar>.nav-item.menu-open>.nav-link {
            background-color: transparent !important;
            color: #ffffff !important;
            border-left: 3px solid #ffffff !important;
            padding-left: 22px !important;
        }

        /* Submenu active styling */
        .sidebar-dark-primary .nav-sidebar .nav-treeview>.nav-item>.nav-link.active {
            background-color: transparent !important;
            color: #ffffff !important;
            padding-left: 28px !important;
        }

        /* Hover effects */
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link:hover,
        .sidebar-dark-primary .nav-sidebar .nav-treeview>.nav-item>.nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
        }

        /* Icon styling for active items */
        .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active .nav-icon,
        .sidebar-dark-primary .nav-sidebar .nav-treeview>.nav-item>.nav-link.active .nav-icon {
            /* color: #000000 !important; */
        }

        /* Remove default active background */
        .sidebar-dark-primary .nav-sidebar .nav-link.active {
            background-color: transparent !important;
        }

        .notifications-icon .badge {
            min-width: 20px;
            height: 20px;
            border-radius: 999px;
            font-size: 0.65rem;
            line-height: 20px;
            padding: 0 4px;
            transform: translate(45%, -45%);
        }

        .notifications-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f3f4f6;
            justify-content: center;
        }

        .navbar-profile-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #2563eb;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
        }

        .navbar-user-dropdown .btn {
            border-radius: 999px;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            padding-right: 0.75rem;
            padding-left: 0.35rem;
        }

        .navbar-user-dropdown .dropdown-menu {
            min-width: 260px;
        }

        .locale-dropdown .dropdown-item.active {
            font-weight: 600;
            background-color: #f3f4f6;
        }

        .locale-dropdown .dropdown-menu form {
            width: 100%;
        }

        .locale-flag {
            width: 20px;
            height: 14px;
            object-fit: cover;
            border-radius: 2px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        @include('admin.layouts.navbar')
        @include('admin.layouts.sidebar')
        <div class="content-wrapper">
            @yield('contents')
        </div>
        @include('admin.layouts.footer')
    </div>

    <!-- jQuery (required by Toastr and SweetAlert2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('AdminLTE/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('AdminLTE/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('AdminLTE/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    <script src="{{ asset('AdminLTE/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('AdminLTE/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('AdminLTE/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE/dist/js/adminlte.js') }}"></script>

    {{-- Toastr & SweetAlert2 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    {{-- Script for toastr --}}
    <script>
        $(document).ready(function() {
            // Confirmation SweetAlert setup (optional, if you need it)
            const Confirmation = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            });

            // Check if there is a 'success' session variable and show the toastr notification
            @if (Session::has('success'))
                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                };

                const toastMessage = @json(Session::has('msg') ? __(Session::get('msg')) : '');
                @if (Session::get('success') == 1)
                    toastr.success(toastMessage);
                @else
                    toastr.error(toastMessage);
                @endif
            @endif
        });
    </script>
    @stack('js')
</body>

</html>
