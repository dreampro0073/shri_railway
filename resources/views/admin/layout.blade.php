<?php $version = env('JS_VERSION'); 
    $service_ids = Session::get('service_ids');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shri Web Technology</title>

    <link rel="stylesheet" type="text/css" href="{{url('bootstrap3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets/font-awesome/css/font-awesome.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css"> -->

    <link rel="stylesheet" type="text/css" href="{{url('plugins/bootstrap-datepicker/css/datepicker3.css')}}">

    <link href="{{url('assets/css/selectize.css')}}" rel="stylesheet" type="text/css"/>
    
    <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css?v='.$version)}}">
</head>
<body  ng-app="app">
	<div id="wrapper">
        <div class="container-fluid">
            <div id="content" style="display: flex;">
                <div class="ul" style="width:250px;background-color: #ececec59;position: fixed;top: 0;left: 0;height: 100vh;overflow-y: scroll;padding:0;">
                    <div style="padding:16px;">
                        <span style="font-size: 18px;font-weight: bold">
                            {{Session::get('client_name')}}
                        </span>
                    </div>
                    <ul class="nav nav-pills nav-stacked">
                        <li class="@if(isset($sidebar)) @if($sidebar == 'dashboard') active @endif @endif">
                            <a href="{{url('/admin/dashboard')}}"><i class="fa fa-home"></i>Dashboard</a>
                        </li>
                        @if(in_array(1, $service_ids) || Auth::user()->priv == 1)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'sitting') active @endif @endif">
                                <a href="{{url('/admin/sitting')}}"><i class="fa fa-sitemap"></i>Sitting</a>
                            </li>
                        @endif

                        @if(in_array(4, $service_ids) || Auth::user()->priv == 1)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'massage') active @endif @endif">
                                <a href="{{url('/admin/massage')}}"><i class="fa fa-medkit" aria-hidden="true"></i>Massage</a>
                            </li>

                        @endif
                        @if(in_array(5, $service_ids) || Auth::user()->priv == 1)
                            
                            <li class="@if(isset($sidebar)) @if($sidebar == 'locker') active @endif @endif">
                            <a href="{{url('/admin/locker')}}"><i class="fa fa-lock"></i>Locker</a>
                        </li>
                        @endif



                        @if(in_array(1, $service_ids) && Auth::user()->priv == 4)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'csitting') active @endif @endif">
                                <a href="{{url('/admin/collect-sitting')}}"><i class="fa fa-sitemap"></i>Collect Sit</a>
                            </li>
                        @endif

                        @if(in_array(2, $service_ids) || Auth::user()->priv == 1)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'cloakrooms') active @endif @endif">
                                <a href="{{url('/admin/cloak-rooms')}}"><i class="fa fa-briefcase" aria-hidden="true"></i>Cloakrooms</a>
                            </li>
                            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'all-cloakrooms') active @endif @endif">
                                <a href="{{url('/admin/cloak-rooms/all')}}"><i class="fa fa-briefcase" aria-hidden="true"></i>Cloakrooms All</a>
                            </li>
                            @endif
                            <li class="@if(isset($sidebar)) @if($sidebar == 'export') active @endif @endif">
                                <a href="{{url('/admin/cloak-rooms/export')}}"><i class="fa fa-medkit" aria-hidden="true"></i>Export Cloakroom</a>
                            </li>
                        @endif                     
                        @if(in_array(3, $service_ids) || Auth::user()->priv == 1)
                            @if(Auth::user()->priv == 2 || Auth::user()->priv == 1)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'cant_items') active @endif @endif">
                                    <a href="{{url('/admin/canteens/items')}}"><i class="fa fa-cutlery" aria-hidden="true"></i>Canteen Items</a>
                                </li>
                            @endif
                            <li class="@if(isset($sidebar)) @if($sidebar == 'daily_entries') active @endif @endif">
                                <a href="{{url('/admin/daily-entries')}}"><i class="fa fa-shopping-bag" aria-hidden="true"></i>Daily Entries</a>
                            </li>
                        
                        @endif

                        <li class="@if(isset($sidebar)) @if($sidebar == 'shift') active @endif @endif">
                            <a href="{{url('/admin/shift/current')}}"><i class="fa fa-industry" aria-hidden="true"></i>Shift Status</a>
                        </li>

                        @if(Auth::user()->priv == 2)
                        <li class="@if(isset($sidebar)) @if($sidebar == 'income') active @endif @endif">
                            <a href="{{url('/admin/income')}}"><i class="fa fa-medkit" aria-hidden="true"></i>Income</a>
                        </li>

                        <li class="@if(isset($sidebar)) @if($sidebar == 'expenses') active @endif @endif">
                            <a href="{{url('/admin/expenses')}}"><i class="fa fa-sitemap"></i>Expenses</a>
                        </li>

                        @endif
                        @if(Auth::user()->priv == 2)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'users') active @endif @endif">
                                <a href="{{url('/admin/users')}}"><i class="fa fa-users" aria-hidden="true"></i>Users</a>
                            </li>
                        @endif

                        <li class="@if(isset($sidebar)) @if($sidebar == 'change_pass') active @endif @endif">
                            <a href="{{url('/admin/reset-password')}}"><i class="fa fa-key" aria-hidden="true"></i>Reset Password</a>
                        </li>
                        <li>
                            <a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i>Logout</a>
                        </li>
                    </ul>
                    
                </div>
                <div class="" style="padding-left:250px;width: 100%;">
                    <div style="text-align:right;padding-top:8px;padding-bottom: 8px;padding-right:24px;margin: 0 -15px;background: #fff;box-shadow:0 0 2px rgba(0,0,0,0.5);"><strong> {{Auth::user()->name}}</strong></div>
                    <div style="padding:0 20px;"> 
                        @yield('main')
                    </div>
                </div>
             
            </div>
        </div>
		
    </div>
    
    <div ng-controller="checkoutAlertCtrl">
        
    </div>
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var auto_alert_status = "{{Session::get('auto_alert_status')}}";
        var authCheck = "{{Auth::user()->is_auto_alert_access}}";
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('bootstrap3/js/bootstrap.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{url('date/bootstrapp-time.min.js')}}"></script> -->
     <!-- <script src="https://unpkg.com/gijgo@1.9.14/js/gijgo.min.js" type="text/javascript"></script> -->
    <script type="text/javascript" src="{{url('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>

    <script>
        // $('.datepicker').datepicker({
        //     uiLibrary: 'bootstrap4',
        //     // format: 'dd/mm/YYYY',
        // });
        // $('.datepicker1').datepicker({
        //     uiLibrary: 'bootstrap4',
        //     // format: 'dd/mm/YYYY',
        // });
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
    
    <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/custom.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/app.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/services.js')}}" ></script>
    <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/controller.js?v='.$version)}}"></script>
    <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/checkout_alert.js?v='.$version)}}"></script>
    @yield('footer_scripts')

    <script>
      angular.module("app").constant("CSRF_TOKEN", "{{ csrf_token() }}");
    </script>
</body>
</html>