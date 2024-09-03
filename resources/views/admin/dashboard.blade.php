@extends('admin.layout')

@section('main')
<?php 
    $service_ids = Session::get('service_ids');
?>

<div class="main" ng-controller="dashboardCtrl">

    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>	
        </div>
        @if(in_array(1, $service_ids) && Auth::user()->is_auto_alert_access == 1)
            <div class="col-md-2" style="margin-top:10px;">
                
                <button ng-if="auto_alert_status == 1" class="btn btn-danger btn-sm" ng-click="changeAlert()">Auto Alert Off</button>
           
                <button ng-if="auto_alert_status == 0" class="btn btn-primary btn-sm" ng-click="changeAlert()">Auto Alert On</button>
               
            </div>
        @endif
    </div>
    <div class="row">
        @if(in_array(1, $service_ids) || Auth::user()->priv == 1)
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card p-3 shadow mb-4" style="background:#8AFF33; padding: 10px;">
                        <p style="font-size: 30px;">Sitting</p>
                        <i>
                            Sitting
                        </i>
                    </div>
                </a>    
            </div>
        @endif        
        @if(in_array(2, $service_ids) || Auth::user()->priv == 1)
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/cloak-rooms')}}">
                    <div class="card p-3 shadow mb-4" style="background:#6833FF;padding: 10px;">
                        <p style="font-size: 30px;">Cloakrooms</p>
                        <i>
                            Cloakrooms
                        </i>
                    </div>
                </a>    
            </div>
            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                <div class="col-md-3">
                    <a class="no-dec" href="{{url('/admin/cloak-rooms/all')}}">
                        <div class="card p-3 shadow mb-4" style="background:#BE33FF;padding: 10px;">
                            <p style="font-size: 30px;">Cloakrooms All</p>
                            <i>
                                Cloakrooms All
                            </i>
                        </div>
                    </a>    
                </div>
            @endif
        @endif        
        @if(in_array(3, $service_ids) || Auth::user()->priv == 1)
            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                <div class="col-md-3">
                    <a class="no-dec" href="{{url('/admin/canteens/items')}}">
                        <div class="card p-3 shadow mb-4" style="background:#FF33C1;padding: 10px;">
                            <p style="font-size: 30px;">Canteen Items</p>
                            <i>
                                Canteen Items
                            </i>
                        </div>
                    </a>    
                </div>
            @endif
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/daily-entries')}}">
                    <div class="card p-3 shadow mb-4" style="background:#ffff33;padding: 10px;">
                        <p style="font-size: 30px;">Daily Entries</p>
                        <i>
                            Daily Entries
                        </i>
                    </div>
                </a>    
            </div>

        @endif
        <div class="col-md-3">
            <a class="no-dec" href="{{url('/admin/shift/current')}}">
                <div class="card p-3 shadow mb-4" style="background:#0467B9;padding: 10px;">
                    <p style="font-size: 30px;">Shift Status</p>
                    <i>
                        Shift Status
                    </i>
                </div>
            </a>    
        </div>
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>    
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection
