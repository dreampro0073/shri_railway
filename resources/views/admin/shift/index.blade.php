@extends('admin.layout')
<?php  
    $service_ids = Session::get('service_ids');
?>

@section('main')
    <div class="main" ng-controller="shiftCtrl" ng-init="init();"> 
        <div class="card shadow mb-4 p-4">    
            <div class="row">
                <div class="col-md-6">
                    <h2 class="">Total Shift Collection (<?php echo date("d-m-Y"); ?>)</h2>
                </div>
                 <div class="col-md-6 text-right" style="padding-top: 25px;">
                    <a href="{{url('/admin/shift/print/1?input_date=')}}@{{filter.input_date}}{{'&client_id='}}@{{filter.client_id}}{{'&user_id='}}@{{filter.user_id}}" class="btn btn-sm btn-warning"  target="_blank"> Print </a>
                </div>
            </div>
            <hr>
            @if(Auth::user()->priv == 2)
            <div class="row">
                <!-- <div class="col-md-3 form-group" >
                    <select ng-model="filter.client_id" class="form-control" ng-change="changeFilter()"  convert-to-number>
                        <option value="">Select</option>
                        <option value="@{{key}}" ng-repeat="(key, value) in clients">@{{value}}</option>
                    </select>
                </div>  -->

                <div class="col-md-3 form-group">
                    <input type="text" placeholder="DD-MM-YYYY" class="datepicker form-control" ng-model="filter.input_date">
                </div>               
                <div class="col-md-3 form-group" >
                    <select ng-model="filter.user_id" class="form-control" convert-to-number>
                        <option value="">Select</option>
                        <option value="@{{user.id}}" ng-repeat="user in users">@{{user.name}}</option>
                    </select>
                    
                </div>
                <div class="col-md-3">
                    <button ng-click="serach()" class="btn btn-primary">
                        Search
                    </button>
                    <button ng-click="clear()" class="btn btn-warning">
                        Clear
                    </button>
                </div>
            </div>
            <hr>
            <div class="row" ng-if="filter.user_id > 0">
                <div class="col-md-4">Change Cash to UPI </div>
                <div class="col-md-2">: @{{change_data.change_cash_to_UPI}}</div>
                <div class="col-md-4">Change UPI to Cash </div>
                <div class="col-md-2">: @{{change_data.change_UPI_to_cash}}</div>
            <hr>
            </div>
            @endif
            <table class="table table-bordered table-striped" style="width:100%;">
                <thead>
                    <tr>
                        <th rowspan="2"></th>
                        <th colspan="3">Last Hour</th>
                        <th colspan="3">Shift Collection</th>
                    </tr>
                    <tr>
                        <th>UPI</th>
                        <th>Cash</th>
                        <th>Total</th>
                        <th>UPI</th>
                        <th>Cash</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in data_rows">
                        <td>@{{item.label}}</td> 
                        <td>@{{item.last_hour_upi_total}}</td>
                        <td>@{{item.last_hour_cash_total}}</td>
                        <td>@{{item.last_hour_total}}</td>
                        <td>@{{item.total_shift_upi}}</td>
                        <td>@{{item.total_shift_cash}}</td>
                        <td>@{{item.total_collection}}</td>
                    </tr>                   
                    <tr>
                        <td><b>Grand Total</b></td> 
                        <td><b>@{{last_hour_upi_total}}</b></td>
                        <td><b>@{{last_hour_cash_total}}</b></td>
                        <td><b>@{{last_hour_total}}</b></td>
                        <td><b>@{{total_shift_upi}}</b></td>
                        <td><b>@{{total_shift_cash}}</b></td>
                        <td><b>@{{total_collection}}</b></td>
                    </tr>
                
                </tbody>
            </table>  
            
        </div>
    </div>
@endsection
    
    