@extends('admin.layout')

@section('main')

<div class="main" ng-controller="clientsCtrl" ng-init="init()">

    <div class="row">
        <div class="col-md-10">
            <h1 class="h3 mb-2 text-gray-800">Clients</h1>	
        </div>
        <div class="col-md-2 text-right">
            <a class="btn btn-sm btn-info" href="{{url('/superAdmin/clients/add')}}">Add New</a>
        </div>
    </div>
    <hr>
    <div >
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sr.no</th>
                    <th>Name</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>GST</th>
                    <th>Address</th>
                    <th>Org ID</th>
                    <th>No Of Users</th>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="client in clients">
                    <td>@{{$index+1}}</td>
                    <td>@{{client.name}}</td>
                    <td>@{{client.client_name}}</td>
                    <td>@{{client.email}}</td>
                    <td>@{{client.mobile}}</td>
                    <td>@{{client.gst}}</td>
                    <td>@{{client.address}}</td>
                    <td>@{{client.org_id}}</td>
                    <td>@{{client.no_of_users}}</td>
                    <td>
                        <a class="btn btn-sm btn-warning" href="{{url('/superAdmin/clients/add/')}}/@{{client.id}}">Edit</a>
                        <button type="button" class="btn btn-sm btn-danger" ng-click="activateClient(client.id)">Deactivate</button>
                        <button type="button" class="btn btn-sm btn-primary" ng-click="activateClient(client.id)">Activate</button>
                    </td>
                </tr>
            </tbody>
        </table>        
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
