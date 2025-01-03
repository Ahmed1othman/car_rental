<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Afandina Admin Panel | @yield('title')</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">
        {{--    <link rel="stylesheet" href="{{asset('admin/plugins/fontawesome-free/css/all.min.css')}}">--}}
        <!-- Ionicoinns -->
{{--        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">--}}
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{asset('admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{asset('admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{asset('admin/plugins/jqvmap/jqvmap.min.css')}}">
        <!-- Theme style -->
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('admin/plugins/select2/css/select2.min.css')}}">
        <link rel="stylesheet"  href="{{asset('admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
        <link rel="stylesheet" href="{{asset('admin/dist/css/adminlte.min.css')}}">

        <link rel="stylesheet" href="{{asset('admin/dist/css/custom.css')}}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{asset('admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{asset('admin/plugins/daterangepicker/daterangepicker.css')}}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{asset('admin/plugins/summernote/summernote-bs4.min.css')}}">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@latest/dist/tagify.css">

        <style>
            .cke_notification {
                display: none;
            }
        </style>

        @stack('styles')
    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            @include('includes.admin.preloader')
            @include('includes.admin.navbar')
            @include('includes.admin.sidebar')
            <div>
                @yield('content')
            </div>
        </div>


        @include('includes.admin.footer')

        <script src="{{asset('admin/plugins/jquery/jquery.min.js')}}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{asset('admin/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <!-- Bootstrap 4 -->
        <script src="{{asset('admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <!-- ChartJS -->
        <script src="{{asset('admin/plugins/chart.js/Chart.min.js')}}"></script>
        <!-- Sparkline -->
        <script src="{{asset('admin/plugins/sparklines/sparkline.js')}}"></script>
        <!-- JQVMap -->
        <script src="{{asset('admin/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
        <script src="{{asset('admin/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
        <!-- jQuery Knob Chart -->
        <script src="{{asset('admin/plugins/jquery-knob/jquery.knob.min.js')}}"></script>
        <!-- daterangepicker -->
        <script src="{{asset('admin/plugins/moment/moment.min.js')}}"></script>
        <script src="{{asset('admin/plugins/daterangepicker/daterangepicker.js')}}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{asset('admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <!-- Summernote -->
        <script src="{{asset('admin/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{asset('admin/plugins/jquery-validation/additional-methods.min.js')}}"></script>

        <script src="{{asset('admin/plugins/summernote/summernote-bs4.min.js')}}"></script>
        <!-- overlayScrollbars -->
        <script src="{{asset('admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
        <!-- AdminLTE App -->
        <script src="{{asset('admin/dist/js/adminlte.js')}}"></script>
        <!-- AdminLTE for demo purposes -->
        {{--<script src="{{asset('admin/dist/js/demo.js')}}"></script>--}}
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        <script src="{{asset('admin/dist/js/pages/dashboard.js')}}"></script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@latest/dist/tagify.min.js"></script>
{{--        <script src="https://cdn.tiny.cloud/1/5c135bzlciyk91vi7xrlsu8kg30bj232e5y5i5rw53oaztrj/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>--}}


{{--        <script src="https://cdn.ckeditor.com/ckeditor5/38.1.0/classic/ckeditor.js"></script>--}}

        <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
        <script>
            CKEDITOR.editorConfig = function( config ) {
                config.language = 'es';
                config.uiColor = '#F7B42C';
                config.height = 200;
                config.toolbarCanCollapse = true;
            };
            var editor = CKEDITOR.replaceAll( 'teny-editor' );
        </script>


        <script src="{{asset('admin/plugins/select2/js/select2.full.min.js')}}"></script>
        <script src="{{asset('admin/dist/js/custom.js')}}"></script>
        @stack('scripts')
    </body>

</html>
