@extends('admin.layout')

@section('main')

<div class="main" ng-controller="superDashboardCtrl">

    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-2 text-gray-800">Dashboard</h1>	
        </div>
    </div>
    <div class="row">
        <div class="col-md-3" style="margin-bottom:20px;">
            <a class="no-dec" href="{{url('/superAdmin/clients')}}">
                <div class="box card hi-auto" style="background:#d3d3d396;; padding: 10px;">
                    <p class="tag">Total Clients</p>
                    <div>
                        <h4>{{$total_clients}}</h4>
                    </div>
                </div>
            </a>    
        </div>
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
