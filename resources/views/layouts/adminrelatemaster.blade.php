<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('includes.admin.styles')
</head>

<body>



    <div class="page">
        <div class="page-main">
            <div class="app-content main-content">
                <div class="side-app">
                    @yield('content')

                </div>
            </div>
        </div>

    </div>


    <script src="{{asset('build/assets/plugins/bootstrap/js/bootstrap.min.js')}}?v=<?php echo time(); ?>"></script>
    <!--INTERNAL Toastr js -->
    <script src="{{asset('build/assets/plugins/toastr/toastr.min.js')}}?v=<?php echo time(); ?>"></script>

    @yield('scripts')
</body>

</html>
