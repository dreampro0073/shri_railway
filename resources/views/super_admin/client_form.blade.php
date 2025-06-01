@extends('admin.layout')

@section('main')

<div class="main" ng-controller="clientsCtrl" ng-init="client_id = {{$client_id}};  addClient();">

    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-2 text-gray-800">Client Details</h1>	
        </div>
        <div class="col-md-2 text-right">
            <a class="btn btn-sm btn-warning" href="{{url('/superAdmin/clients')}}">Back</a>
        </div>
    </div>
    <hr>
    <div class="row">
       <div class="col-md-6 form-group">
            <label>Organization Name</label>
            <input type="text" ng-model="client.name" class="form-control">
       </div> 
       <div class="col-md-6 form-group">
            <label>Client Name</label>
            <input type="text" ng-model="client.client_name" class="form-control">
       </div> 
       <div class="col-md-3 form-group">
            <label>Email</label>
            <input type="text" ng-model="client.email" class="form-control">
       </div> 
       <div class="col-md-2 form-group">
            <label> Mobile</label>
            <input type="text" ng-model="client.mobile" class="form-control">
       </div> 
       <div class="col-md-2 form-group">
            <label>GST No</label>
            <input type="text" ng-model="client.gst" class="form-control">
       </div> 
       <div class="col-md-5 form-group">
            <label>Address</label>
            <input type="text" ng-model="client.address" class="form-control">
       </div>
        <div class="col-md-2 form-group">
            <label>No of maximum Users</label>
            <input type="number" ng-model="client.max_users" class="form-control">
       </div> 
        <div class="col-md-2 form-group">
            <label>No of maximum logins</label>
            <input type="number" ng-model="client.max_logins" class="form-control">
       </div> 
    </div>

    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-2 text-gray-800">Services</h1>   
        </div>
        <div class="col-md-2 text-right">
            <button ng-click="addService()" class="btn btn-sm btn-warning" >Add Service</button>
        </div>
    </div>
    <hr>
    <div>
        <table class="table table-bordered ">
            <thead>
                <tr>
                    <th>Sr. no</th>
                    <th>Service</th>
                    <th>Rate List</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="service in client.services">
                    <td>@{{$index+1}}</td>
                    <td class="form-group">    
                        <select ng-model="service.services_id" class="form-control" ng-change="changeService()" required >
                            <option value="">--select--</option>
                            <option ng-repeat="(key, value) in services" ng-value="@{{key}}">@{{value}}</option>
                        </select>
                    </td>
                    <td>
                        @include('super_admin.rate_list')
                    </td>
                    <td>
                        <button ng-click="removeService($index)" class="btn-danger" ><i class="fa fa-remove"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div> 
        <button ng-click="storeClient($index)" class="btn btn-sm btn-primary"> Submit </button>
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
