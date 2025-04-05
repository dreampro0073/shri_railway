<?php 
    $version = env('JS_VERSION'); 
    $host = env('APP_ENV'); 
    $service_ids = Session::get('service_ids');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aadhyasri Web Solutions</title>

    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    @if($host == 'local')
        <link rel="stylesheet" type="text/css" href="{{url('bootstrap3/css/bootstrap.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{url('assets/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" type="text/css" href="{{url('plugins/bootstrap-datepicker/css/datepicker3.css')}}">
        <link href="{{url('assets/css/selectize.css')}}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css?v='.$version)}}">
    @else
        <link rel="stylesheet" type="text/css" href="{{url('assets/dist/web.min.css?v='.$version)}}">
    @endif
</head>
<body  ng-app="app">
	<div id="wrapper">
        <div class="container-fluid">
            <div id="content" style="display: flex;">
                <div class="ul" style="width:250px;background-color: #ececec59;position: fixed;top: 0;left: 0;height: calc(100vh - 67px);overflow-y: scroll;padding:0;">
                    <div style="padding:16px;">
                        <span style="font-size: 18px;font-weight: bold">
                            {{Session::get('client_name')}}
                        </span>
                    </div>
                    <ul class="nav nav-pills nav-stacked">
                        @if(Auth::user()->priv !=5)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'dashboard') active @endif @endif">
                                <a href="{{url('/admin/dashboard')}}"><i class="fa fa-home"></i>Dashboard</a>
                            </li>
                            @if(in_array(1, $service_ids) || Auth::user()->priv == 1)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'sitting') active @endif @endif">
                                    <a href="{{url('/admin/sitting')}}"><i class="fa fa-sitemap"></i>Sitting</a>
                                </li>
                            @endif

                            @if(in_array(8, $service_ids))
                                <li class="@if(isset($sidebar)) @if($sidebar == 'pods') active @endif @endif">
                                    <a href="{{url('/admin/rooms/1')}}"><i class="fa fa-podcast"></i>PODs</a>
                                </li>
                                <li class="@if(isset($sidebar)) @if($sidebar == 'scabins') active @endif @endif">
                                    <a href="{{url('/admin/rooms/2')}}"><i class="fa fa-tasks"></i>Single Suit Cabin</a>
                                </li>
                                <li class="@if(isset($sidebar)) @if($sidebar == 'beds') active @endif @endif">
                                    <a href="{{url('/admin/rooms/3')}}"><i class="fa fa-bed"></i>Double Beds</a>
                                </li>
                            @endif
                            @if(in_array(8, $service_ids) && Auth::user()->priv == 2)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'all-entries') active @endif @endif">
                                    <a href="{{url('/admin/all-rooms')}}"><i class="fa fa-navicon" aria-hidden="true"></i>All Rooms</a>
                                </li>
                            @endif

                            @if(in_array(7, $service_ids) || Auth::user()->priv == 1)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'rec') active @endif @endif">
                                    <a href="{{url('/admin/recliners')}}"><i class="fa fa-sitemap"></i>Recliners</a>
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

                            @if(in_array(9, $service_ids) || Auth::user()->priv == 1)
                                
                                <li class="@if(isset($sidebar)) @if($sidebar == 'scanning') active @endif @endif">
                                <a href="{{url('/admin/scanning')}}"><i class="fa fa-qrcode" aria-hidden="true"></i>Scanning</a>
                            </li>
                            @endif



                            <!-- @if(in_array(1, $service_ids) && Auth::user()->priv == 4)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'csitting') active @endif @endif">
                                    <a href="{{url('/admin/collect-sitting')}}"><i class="fa fa-sitemap"></i>Collect Sit</a>
                                </li>
                            @endif -->

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

                                @if(Auth::user()->priv == 4 || Auth::user()->client_id == 6 || Auth::id() == 48 )
                                   <!--  <li class="@if(isset($sidebar)) @if($sidebar == 'csitting') active @endif @endif">
                                        <a href="{{url('/admin/collect-cloak')}}"><i class="fa fa-sitemap"></i>Collect Cloack</a>
                                    </li> -->
                                @endif
                            @endif                     
                            @if(in_array(3, $service_ids) || Auth::user()->priv == 1)
                                @if(Auth::user()->priv == 2 || Auth::user()->priv == 1)
                                   <!--  <li class="@if(isset($sidebar)) @if($sidebar == 'godowns') active @endif @endif">
                                        <a href="{{url('/admin/godowns')}}"><i class="fa fa-cutlery" aria-hidden="true"></i>Godowns</a>
                                    </li> -->
                                    
                                    <li class="@if(isset($sidebar)) @if($sidebar == 'cant_items') active @endif @endif">
                                        <a href="{{url('/admin/canteens/items')}}"><i class="fa fa-cutlery" aria-hidden="true"></i>Canteen Items</a>
                                    </li>
                                    
                                @endif
                                <li class="@if(isset($sidebar)) @if($sidebar == 'daily_entries') active @endif @endif">
                                    <a href="{{url('/admin/daily-entries')}}">
                                        <i class="fa fa-shopping-bag" aria-hidden="true"></i>Daily Entries
                                    </a>
                                </li>
                            
                            @endif

                            @if(Auth::user()->priv == 2 && in_array(6, $service_ids))

                                <li class="@if(isset($sidebar)) @if($sidebar == 'acc') active @endif @endif">
                                    <a class="nav-link collapsed" href="#javascript:;" data-toggle="collapse" data-target="#collapseTwo"
                                        aria-expanded="true" aria-controls="collapseTwo">
                                        <i class="fa fa-industry" aria-hidden="true"></i>
                                        <span>Accounting</span>

                                        <i class="fa fa-chevron-down ab" aria-hidden="true"></i>

                                    </a>
                                    <ul id="collapseTwo" class="collapse @if(isset($sidebar)) @if($sidebar == 'acc') in @endif @endif" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="padding-left: 30px;">
                                        <li class="@if(isset($subsidebar)) @if($subsidebar == 'income') active @endif @endif">
                                            <a class="collapse-item" href="{{url('/admin/income')}}">Income</a>
                                        </li>
                                        <li class="@if(isset($subsidebar)) @if($subsidebar == 'expenses') active @endif @endif">
                                            <a class="collapse-item" href="{{url('/admin/expenses')}}">Expense</a>
                                        </li>
                                        <li class="@if(isset($subsidebar)) @if($subsidebar == 'summary') active @endif @endif">
                                            <a class="collapse-item" href="{{url('/admin/summary')}}">Summary</a>
                                        </li>
                                       
                                    </ul>
                                </li>

                            @endif
                            <li class="@if(isset($sidebar)) @if($sidebar == 'shift') active @endif @endif">
                                <a href="{{url('/admin/shift/current')}}"><i class="fa fa-industry" aria-hidden="true"></i>Shift Status</a>
                            </li>

                            @if(Auth::user()->priv == 2 && in_array(6, $service_ids))
                            <!-- <li class="@if(isset($sidebar)) @if($sidebar == 'income') active @endif @endif">
                                <a href="{{url('/admin/income')}}"><i class="fa fa-money" aria-hidden="true"></i>Income</a>
                            </li>

                            <li class="@if(isset($sidebar)) @if($sidebar == 'expenses') active @endif @endif">
                                <a href="{{url('/admin/expenses')}}"><i class="fa fa-money"></i>Expenses</a>
                            </li>                        

                            <li class="@if(isset($sidebar)) @if($sidebar == 'summary') active @endif @endif">
                                <a href="{{url('/admin/summary')}}"><i class="fa fa-money"></i>Day Summary</a>
                            </li> -->

                            @endif
                            @if(Auth::user()->priv == 2)
                                <li class="@if(isset($sidebar)) @if($sidebar == 'users') active @endif @endif">
                                    <a href="{{url('/admin/users')}}"><i class="fa fa-users" aria-hidden="true"></i>Users</a>
                                </li>
                            @endif

                            <li class="@if(isset($sidebar)) @if($sidebar == 'change_pass') active @endif @endif">
                                <a href="{{url('/admin/reset-password')}}"><i class="fa fa-key" aria-hidden="true"></i>Reset Password</a>
                            </li>
                            

                        @endif

                        @if(Auth::user()->priv == 5 && Auth::user()->org_id == 1 && Auth::user()->is_super == 1)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'set_amount') active @endif @endif">
                                <a href="{{url('/admin/clients/set-amount')}}">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>Daily Set Hide Amount
                                </a>
                            </li>
                        @endif

                        @if(Auth::user()->priv == 5 && Auth::user()->org_id == 1)
                            <li class="@if(isset($sidebar)) @if($sidebar == 'shift_status') active @endif @endif">
                                <a href="{{url('/admin/clients/shift-status')}}">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>Daily Overall Shift Status
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="{{url('/logout')}}"><i class="fa fa-sign-out"></i>Logout</a>
                        </li>
                        
                    </ul>
                    
                </div>
                <div class="" style="padding-left:250px;width: 100%;">
                    <div style="text-align:right;padding-top:8px;padding-bottom: 8px;padding-right:24px;margin: 0 -15px;background: #fff;box-shadow:0 0 2px rgba(0,0,0,0.5);"><strong> {{Auth::user()->name}}</strong> <a href="{{url('logout')}}"><b>Logout</b></a> </div>
                    <div style="padding:0 20px;padding-bottom: 80px;"> 
                        @yield('main')
                    </div>
                </div>
             
            </div>
        </div>

        <span style="position: fixed;bottom:0;left:0;width: 100%;padding:8px;display: block;text-align: center;background: #fff;border-top: 1px solid #a6a6a67d;">
            <img src="{{url('assets/img/aadh1.png')}}" style="height:50px;width: auto;">
            <!-- <a href="mailto:aadhyasriwebsolutions@gmail.com">aadhyasriwebsolutions@gmail.com</a> -->

        </span>
		
    </div>
    
    <!-- <div ng-controller="checkoutAlertCtrl">
        
    </div> -->
    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var auto_alert_status = "{{Session::get('auto_alert_status')}}";
        var authCheck = "{{Auth::user()->is_auto_alert_access}}";
    </script>

    @if($host == 'local')
        <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
        <script type="text/javascript" src="{{url('bootstrap3/js/bootstrap.min.js')}}"></script>
        <script type="text/javascript" src="{{url('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/custom.js')}}"></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/app.js')}}" ></script>
        <script type="text/javascript" src="{{url('assets/scripts/core/services.js')}}" ></script>
        <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/controller.js?v='.$version)}}"></script>
        <!-- <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/checkout_alert.js?v='.$version)}}"></script> -->
    @else
        <script type="text/javascript" type="text/javascript" src="{{url('assets/dist/plugins.min.js?v='.$version)}}"></script>
        <script type="text/javascript" type="text/javascript" src="{{url('assets/dist/web.min.js?v='.$version)}}"></script>
        
    @endif

    @yield('footer_scripts')

    <script>
      angular.module("app").constant("CSRF_TOKEN", "{{ csrf_token() }}");
    </script>
</body>
</html>