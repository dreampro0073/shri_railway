@extends('admin.layout')
<?php  
    $service_ids = Session::get('service_ids');
?>

@section('main')
    <div class="main" ng-controller="clientSettingCtrl" ng-init="shiftStatus();"> 
        <div class="card shadow mb-4 p-4">    
            <table class="table table-bordered table-striped" style="width:100%;margin-top: 50px;">
                <thead>
                    
                    <tr>
                        <th>Name</th>
                        <th>UPI</th>
                        <th>Cash</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in shift_rows">
                        <td>@{{item.client_name}}</td>
                        <td>@{{item.total_shift_upi}}</td>
                        <td>@{{item.total_shift_cash}}</td>
                        <td>@{{item.total_collection}}</td>
                    </tr>
                </tbody>
            </table>  
        </div>
    </div>
@endsection