@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="entryRoomCtrl" ng-init="type = {{$type}};init();"> 
        @include('admin.rooms.add')
        <div class="card shadow mb-4 p-4">    
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px;">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Bill Number</label>
                                    <input type="text" class="form-control" ng-model="filter.unique_id" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.name" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Mobile</label>
                                    <input type="text" class="form-control" ng-model="filter.mobile_no" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label class="label-control">PNR</label>
                                    <input type="text" class="form-control" ng-model="filter.pnr_uid" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn  btn-primary btn-sm" >Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning btn-sm" >Clear</button>
                            <button type="button" ng-click="add()" class="btn  btn-primary btn-sm" >Add</button>
                        </div>
                    </div>
                </form>
            </div>
          
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead >
                            <tr>
                                <th>S.no</th>
                                <th>Bill no</th>
                                <th>
                                    <span ng-if="type == 1">Pod</span>
                                    <span ng-if="type == 2">Cabin</span>
                                    <span ng-if="type == 3">Bed</span>
                                </th>
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>Check In/Check Out</th>
                                <th>PNR</th>
                                <th>Pay Type/Hr</th>
                                <th>Paid Amount</th>
                                <th>Discount Amount</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody ng-if="entries.length > 0" >
                            <tr ng-repeat="item in entries " ng-class="item.check_class">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.unique_id }}</td>
                                <td>@{{ item.show_e_ids }}</td>
                                <td>@{{ item.name }}</td>
                                <td>@{{ item.mobile_no }}</td>
                                <td>@{{ item.check_in }}/@{{ item.checkout_date }}</td>
                                
                                <td>@{{ item.pnr_uid }}</td>
                                
                                <td>
                                    <span ng-if="item.pay_type == 1">Cash</span>
                                    <span ng-if="item.pay_type == 2">UPI</span>
                                    <span>/@{{item.hours_occ+item.late_hr}}Hr</span>
                                </td>  
                                
                                <td>@{{ item.sh_paid_amount}}</td>
                                <td>@{{item.discount_amount }}</td>
                                
                                <td>
                                    <a ng-if="item.status == 1" href="javascript:;" ng-click="checkoutLoker(item.id)" class="btn btn-danger btn-sm">Checkout</a>
                                    @if(Auth::user()->priv == 1)
                                    
                                    @endif

                                    <a ng-if="item.status == 1" href="javascript:;" ng-click="edit(item.id)" class="btn btn-warning btn-sm">Edit</a>


                                    @if(Auth::user()->priv == 2)
                                       <a onclick="return confirm('Are you sure?')" href="{{url('/admin/rooms/checkout-without-penalty')}}/@{{item.id}}" ng-if="item.checkout_status != 1 && item.status == 1" ng-class="item.check_class == 't-danger' " class="btn btn-warning btn-sm">Checkout WP</a>
                                    @endif

                                    <a ng-click="markCheckin(item.id)" ng-if="item.online_booking == 1 && item.status == 0" href="javascript:;" class="btn btn-sm btn-primary">
                                        Mark CheckIn
                                    </a>

                                    <a href="{{url('/admin/rooms/print')}}/@{{item.id}}" class="btn btn-success btn-sm" target="_blank">Print</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div>     
        </div>
    </div>
@endsection
    
    