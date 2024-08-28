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
                @if(Auth::user()->priv != 2)

                    <div class="col-md-6 text-right" style="padding-top: 25px;">
                        <a href="{{url('/admin/shift/print/1')}}" class="btn btn-sm btn-warning"  target="_blank">
                            Print
                        </a>
                    </div>
                @endif
            </div>
            <hr>
            @if(Auth::user()->priv == 2)
            <div class="row">
                <div class="col-md-3 form-group">
                    <input type="text" class="datepicker form-control" ng-model="filter.input_date">
                    
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
                    @if(in_array(1, $service_ids))
                    <tr>
                       <td>Sitting</td> 
                        <td>@{{sitting_data.last_hour_upi_total}}</td>
                        <td>@{{sitting_data.last_hour_cash_total}}</td>
                        <td>@{{sitting_data.last_hour_total}}</td>
                        <td>@{{sitting_data.total_shift_upi}}</td>
                        <td>@{{sitting_data.total_shift_cash}}</td>
                        <td>@{{sitting_data.total_collection}}</td>
                    </tr>
                    @endif                    
                    @if(in_array(2, $service_ids))
                    <tr>
                        <td>Cloakroom</td> 
                        <td>@{{cloak_data.last_hour_upi_total}}</td>
                        <td>@{{cloak_data.last_hour_cash_total}}</td>
                        <td>@{{cloak_data.last_hour_total}}</td>
                        <td>@{{cloak_data.total_shift_upi}}</td>
                        <td>@{{cloak_data.total_shift_cash}}</td>
                        <td>@{{cloak_data.total_collection}}</td>
                    </tr>
                    @endif                    
                    @if(in_array(3, $service_ids))
                    <tr>
                        <td>Canteen</td> 
                        <td>@{{canteen_data.last_hour_upi_total}}</td>
                        <td>@{{canteen_data.last_hour_cash_total}}</td>
                        <td>@{{canteen_data.last_hour_total}}</td>
                        <td>@{{canteen_data.total_shift_upi}}</td>
                        <td>@{{canteen_data.total_shift_cash}}</td>
                        <td>@{{canteen_data.total_collection}}</td>
                    </tr>
                    @endif
                    @if(in_array(4, $service_ids))
                    <tr>
                        <td>Massage</td> 
                        <td>@{{massage_data.last_hour_upi_total}}</td>
                        <td>@{{massage_data.last_hour_cash_total}}</td>
                        <td>@{{massage_data.last_hour_total}}</td>
                        <td>@{{massage_data.total_shift_upi}}</td>
                        <td>@{{massage_data.total_shift_cash}}</td>
                        <td>@{{massage_data.total_collection}}</td>
                    </tr>
                    @endif

                     @if(in_array(5, $service_ids))
                    <tr>
                        <td>Locker</td> 
                        <td>@{{locker_data.last_hour_upi_total}}</td>
                        <td>@{{locker_data.last_hour_cash_total}}</td>
                        <td>@{{locker_data.last_hour_total}}</td>
                        <td>@{{locker_data.total_shift_upi}}</td>
                        <td>@{{locker_data.total_shift_cash}}</td>
                        <td>@{{locker_data.total_collection}}</td>
                    </tr>
                    @endif
                    
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
    
    