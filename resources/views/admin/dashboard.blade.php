@extends('admin.layout')

@section('main')
<?php 
    $service_ids = Session::get('service_ids');
?>

<div class="main" ng-controller="dashboardCtrl">

    <div class="row">
        <div class="col-md-10">
            <h5 class="fw-semibold mb-3">Dashboard</h5> 
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
                    <div class="card shadow-1 radius-8 gradient-bg-end-1 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Total Sit</p>
                            <div>
                                <h4>{{$total_sitting_count}}</h4>
                                
                            </div>
                        </div>
                        
                    </div>
                </a>    
            </div>
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card shadow-1 radius-8 gradient-bg-end-2 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Booked Sit</p>
                            <div>
                                <h4>{{$sitting_count}}</h4>
                                
                            </div>
                        </div>
                        
                    </div>
                </a>    
            </div>
            
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/sitting')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-3 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Available Sit</p>
                            <div>
                                <h4>{{$avail_sit}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
                  
            </div>
            
        @endif        
        @if(in_array(2, $service_ids) || Auth::user()->priv == 1)
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/cloak-rooms')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-4 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Total Bag</p>
                            <div>
                                <h4>{{$booked_bags}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
                  
            </div>
        @endif        
        
    </div>
    <div class="mt-3"></div>
    @if(in_array(9, $service_ids) || Auth::user()->priv == 1)

        <div class="row">

            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/scanning')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-1 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Leased Item</p>
                            <div>
                                <h4>{{$leased_count}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
                  
            </div>
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/scanning')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-2 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Non Leased Item</p>
                            <div>
                                <h4>{{$non_leased_count}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
                  
            </div>
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/scanning')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-3 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Outword</p>
                            <div>
                                <h4>{{$outword_count}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
                  
            </div>
            <div class="col-md-3">
                <a class="no-dec" href="{{url('/admin/scanning')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-4 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Inword</p>
                            <div>
                                <h4>{{$inword_count}}</h4>
                                
                            </div>
                        </div>
                    </div>
                </a> 
            </div>
        </div>
    @endif             


    @if(in_array(7, $service_ids))
        <div class="row mb-3 mt-3">
            <div class="col-md-4">
                <a class="no-dec" href="{{url('/admin/recliners')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-2 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Available Recliner</p>
                            <div>
                                <h4>{{sizeof($avail_recliner)}}</h4>
                                <span>
                                    <?php echo implode(', ',$avail_recliner); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </a> 
                
            </div>

            <div class="col-md-4">
                <a class="no-dec" href="{{url('/admin/recliners')}}">
                    <div class="card shadow-2 radius-8 gradient-bg-end-2 h-100">
                        <div class="card-body p-20">
                            <p class="tag">Booked Recliner</p>
                            <div>
                                <h4>{{sizeof($booked_recliner)}}</h4>
                                <span>
                                    <?php echo implode(', ',$booked_recliner); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </a> 
                
            </div>
           
        </div>
    @endif

    @if(in_array(8, $service_ids))
    <div class="row">

        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/3')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-2 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Double Beds</p>
                        <div>
                            <h4>{{sizeof($avail_beds)}}</h4>
                            <span>
                                <?php echo implode(', ',$avail_beds); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
            
        </div>

        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/2')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-3 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Available Single Suit Cabins</p>
                        <div>
                            <h4>{{sizeof($avail_cabins)}}</h4>
                            <span>
                                <?php echo implode(', ',$avail_cabins); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
            
        </div>
        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/1')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-4 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Available PODs</p>
                        <div>
                            <h4>{{sizeof($avail_pods)}}</h4>
                            <span>
                                <?php echo implode(', ',$avail_pods); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
            
        </div> 
    </div>	
    <div class="row mt-3">
        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/3')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-1 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Booked Double Beds</p>
                        <div>
                            <h4>{{sizeof($booked_beds)}}</h4>
                            <span>
                                <?php echo implode(', ',$booked_beds); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
            
        </div> 
        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/2')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-2 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Booked Single Suit Cabins</p>
                        <div>
                            <h4>{{sizeof($booked_cabins)}}</h4>
                            <span>
                                <?php echo implode(', ',$booked_cabins); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a> 
            
        </div> 
        <div class="col-md-4">
            <a class="no-dec" href="{{url('/admin/rooms/1')}}">
                <div class="card shadow-2 radius-8 gradient-bg-end-4 h-100">
                    <div class="card-body p-20">
                        <p class="tag">Bookes PODs</p>
                        <div>
                            <h4>{{sizeof($booked_pods)}}</h4>
                            <span>
                                <?php echo implode(', ',$booked_pods); ?>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div> 
    </div>
    @endif
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>    
    <script type="text/javascript" src="{{url('assets/scripts/core/client_ctrl.js?v='.$version)}}" ></script>

    
@endsection
