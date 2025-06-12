<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>

        <meta charset="utf-8" />
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta content="" name="description" />
        <meta content="" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

         @include('components.styles')

    </head>

    <body id="body" class="">
        
        @include('layouts.sidebar')

        @include('layouts.topbar')

        <div class="page-wrapper">

            <!-- Page Content-->
            <div class="page-content-tab">

                <div class="container-fluid">
                    <!-- Page-Title -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="page-title-box">
                                <div class="float-end">
                                    <ol class="breadcrumb">
                                        @yield('breadcrumb')
                                    </ol>
                                </div>
                                <h4 class="page-title">@yield('title')</h4>
                            </div><!--end page-title-box-->
                        </div><!--end col-->
                    </div>
                    <!-- end page title end breadcrumb -->

                    @yield('content')

                </div><!-- container -->

                
            </div>
            <!-- end page content -->
        </div>
        <!-- end page-wrapper -->

        @if (session('success'))
            @include('components.toast', ["color" => "success", "icon" => "circle-check", "message" => session('success')])
        @endif

        @if (session('error'))
            @include('components.toast', ["color" => "danger", "icon" => "circle-x", "message" => session('error')])
        @endif

        @include('layouts.footer')

        @include('components.scripts')

    </body>
    <!--end body-->
</html>