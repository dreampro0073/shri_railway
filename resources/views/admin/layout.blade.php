<?php 
    $version = env('JS_VERSION'); 
    $host = env('APP_ENV'); 
    $service_ids = Session::get('service_ids');

    $suff = ($host == 'prod') ? '.js' : '.js'; 
?>

<!DOCTYPE html>
<html>
<head>
   <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Aadhyasri Web Solutions</title>

    

    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/remixicon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/bootstrap.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/apexcharts.css')}}"> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/flatpickr.min.css')}}"> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/calendar.css')}}"> -->

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/style.css')}}">

    <link rel="stylesheet" type="text/css" href="{{url('plugins/bootstrap-datepicker/css/datepicker3.css')}}">
    <link href="{{url('assets/css/selectize.css')}}" rel="stylesheet" type="text/css"/>

     <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css?v='.$version)}}">
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-WEC6NN0XE0"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-WEC6NN0XE0');
    </script> -->
</head>
<!-- <body  ng-app="app" oncontextmenu="return false;"> -->
<body  ng-app="app">


    <aside class="sidebar">
       
        @include('admin.sidebar')
    </aside>
  
    <main class="dashboard-main">
        <div class="navbar-header text-end">
            <div class="row">
                <div class="col-6">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <button type="button" class="sidebar-mobile-toggle" aria-label="Sidebar Mobile Toggler Button" style="margin-top:8px;">
                          <i class="ri-menu-line"></i>
                        </button>
                        
                    </div>
                </div>
                <div class="col-6 text-end">
                    <div class="dropdown profile-dropdown d-inline-block">
                        <button type="button" class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12 "  data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <span class="d-flex align-items-start gap-10">
                                <span class="profile-dropdown__contents">
                                    <span class="h6 mb-0 text-md d-block text-primary-light">{{Auth::user()->name}}</span>
                                 
                                </span>
                            </span>
                            <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
                                <i class="ri-arrow-right-s-line"></i>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                            <li>
                                <a href="{{url('/admin/reset-password')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-settings-3-line"></i>
                                Reset Password
                            </a>
                         </li>
                         <li>
                            <a href="{{url('logout')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-shut-down-line"></i>
                                Log Out
                            </a>
                         </li>
                      </ul>
                   </div>
                </div>
            </div>
           
           
           
        </div>

        <div class="dashboard-main-body">
            @yield('main')
        </div>
    </main>

	
    

    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var auto_alert_status = "{{Session::get('auto_alert_status')}}";
        var authCheck = "{{Auth::user()->is_auto_alert_access}}";
        var api_key = "{{Auth::user()->api_token}}";
    </script>

    <!-- <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('bootstrap3/js/bootstrap.min.js')}}"></script> -->

    <script type="text/javascript" src="{{url('assets1/js/lib/jquery-3.7.1.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets1/js/lib/bootstrap.bundle.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/apexcharts.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/iconify-icon.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/dataTables.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/jquery-ui.min.js')}}"></script> -->
    <script type="text/javascript" src="{{url('assets1/js/app.js')}}"></script>

    <script type="text/javascript" src="{{url('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/custom'.$suff.'?v='.$version)}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/app'.$suff.'?v='.$version)}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/services'.$suff.'?v='.$version)}}" ></script>
    <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/controller'.$suff.'?v='.$version)}}"></script>
    <!-- <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/gcanteen_ctrl'.$suff.'?v='.$version)}}"></script> -->
      
   

    @yield('footer_scripts')

    <script>
      angular.module("app").constant("CSRF_TOKEN", "{{ csrf_token() }}");
    </script>
</body>
</html>