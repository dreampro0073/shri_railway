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
            <div class="col-md-3" style="margin-bottom:20px;">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card p-3 shadow mb-4" style="background:#d3d3d396;; padding: 10px;">
                        <p style="font-size: 30px;">Total Sit</p>
                        <div>
                            <h4>{{$total_sitting_count}}</h4>
                            
                        </div>
                    </div>
                </a>    
            </div>
            <div class="col-md-3" style="margin-bottom:20px;">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card p-3 shadow mb-4" style="background:#d3d3d396;; padding: 10px;">
                        <p style="font-size: 30px;">Booked Sit</p>
                        <div>
                            <h4>{{$sitting_count}}</h4>
                            
                        </div>
                    </div>
                </a>    
            </div>
            <div class="col-md-3" style="margin-bottom:20px;">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card p-3 shadow mb-4" style="background:#d3d3d396;; padding: 10px;">
                        <p style="font-size: 30px;">Available Sit</p>
                        <div>
                            <h4>{{$avail_sit}}</h4>
                            
                        </div>
                    </div>
                </a>    
            </div>
            <div class="col-md-3" style="margin-bottom:20px;">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card p-3 shadow mb-4" style="background:#d3d3d396;; padding: 10px;">
                        <p style="font-size: 30px;">Total Sit</p>
                        <div>
                            <h4>{{sizeof($total_sitting_count)}}</h4>
                            <h3>
                                <span style="font-size:26px;color: green;">{{$sitting_count}}</span>/<span style="font-size:28px;color:black;">{{$total_sitting_count}}</span>
                            </h3>
                        </div>
                    </div>
                </a>    
            </div>
        @endif        
        @if(in_array(2, $service_ids) || Auth::user()->priv == 1)
            <div class="col-md-3">
                    <div class="box card">
                    <h4>{{$booked_bags}}</h4>
                    <h5>
                        Total Bags
                    </h5>
                </div>
            </div>
        @endif        
        @if(in_array(3, $service_ids) || Auth::user()->priv == 1)
            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                <div class="col-md-3" style="margin-bottom:20px;">
                    <a class="no-dec" href="{{url('/admin/canteens/items')}}">
                        <div class="card p-3 shadow mb-4" style="background:#d3d3d396;;padding: 10px;">
                            <p style="font-size: 30px;">Canteen Items</p>
                            <i>
                                Canteen Items
                            </i>
                        </div>
                    </a>    
                </div>
            @endif
            <div class="col-md-3" style="margin-bottom:20px;">
                <a class="no-dec" href="{{url('/admin/daily-entries')}}">
                    <div class="card p-3 shadow mb-4" style="background:#d3d3d396;;padding: 10px;">
                        <p style="font-size: 30px;">Daily Entries</p>
                        <i>
                            Daily Entries
                        </i>
                    </div>
                </a>    
            </div>

        @endif

    </div>

    @if(in_array(7, $service_ids))
    <div class="row">
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($avail_recliner)}}</h4>

                <h5>
                    Available Recliner
                </h5>
                <span>
                    <?php echo implode(', ',$avail_recliner); ?>
                </span>

            </div>
        </div>
        
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($booked_recliner)}}</h4>

                <h5>
                    Booked Recliner
                </h5>
                <span>
                    <?php echo implode(', ',$booked_recliner); ?>
                </span>

            </div>
        </div>
       
       
    </div>  
    
    @endif

    @if(in_array(8, $service_ids))
    <div class="row">
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($avail_beds)}}</h4>

                <h5>
                    Double Beds
                </h5>
                <span>
                    <?php echo implode(', ',$avail_beds); ?>
                </span>

            </div>
        </div>
        
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($avail_cabins)}}</h4>

                <h5>
                    Available Single Suit Cabins
                </h5>
                <span>
                    <?php echo implode(', ',$avail_cabins); ?>
                </span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($avail_pods)}}</h4>

                <h5>
                    Available PODs
                </h5>
                <span>
                    <?php echo implode(', ',$avail_pods); ?>
                </span>
            </div>
        </div>
       
       
    </div>	
    <div class="row" style="margin-top:20px;">
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($booked_beds)}}</h4>

                <h5>
                    Booked Double Beds
                </h5>
                <span>
                    <?php echo implode(', ',$booked_beds); ?>
                </span>

            </div>
        </div>
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($booked_cabins)}}</h4>

                <h5>
                    Booked Single Suit Cabins
                </h5>
                <span>
                    <?php echo implode(', ',$booked_cabins); ?>
                </span>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="box card">
                <h4>{{sizeof($booked_pods)}}</h4>

                <h5>
                    Bookes PODs
                </h5>
                <span>
                    <?php echo implode(', ',$booked_pods); ?>
                </span>
            </div>
        </div>
    </div>
    @endif

    
   
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>    
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection
