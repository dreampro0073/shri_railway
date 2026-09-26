@extends('admin.layout')

@section('main')

<div class="main" ng-controller="clientsCtrl" ng-init="init()">
    <div class="card shadow mb-4 p-4">  
        <div class="row">
            
            <div class="col-md-3 text-right">
                <a class="btn btn-sm btn-info" href="{{url('/superAdmin/clients/dashboard')}}">Dashboard</a>
            </div>
        </div>
        <hr>
        <div class="dt-layout-row dt-layout-table">
            <div class="dt-layout-cell">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sr.no</th>
                            <th>Name</th>
                            <th>Client Name</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="client in clients">
                            <td>@{{$index+1}}</td>
                            <td>@{{client.client_name}}</td>
                            <td>@{{client.name}}</td>
                            <td>@{{client.email}}</td>
                            <td>@{{client.password_check}}</td>
                        </tr>
                    </tbody>
                </table>        
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer_scripts')
    <?php $version = "0.0.3"; ?>     
@endsection
