@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="allRoomEntryCtrl" ng-init="init();"> 
        @include('admin.rooms.view_modal')
        <div class="card shadow mb-4 p-4">    
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Slip Id</label>
                                    <input type="text" class="form-control" ng-model="filter.slip_id" />
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
                        <div class="col-md-4 text-end" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn  btn-primary btn-sm" >Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning btn-sm" >Clear</button>
                            
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
                                <th>Slip Id</th>
                                <td>
                                    <span>Type</span>
                                </td>
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
                            <tr ng-repeat="item in entries " ng-class="{'my_class': item.deleted == 1}">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.slip_id }}</td>
                                <td>
                                    <span ng-if="item.type == 1">Pods</span>
                                    <span ng-if="item.type == 2">Single Suit Cabin</span>
                                    <span ng-if="item.type == 3">Double Bed</span>
                                    <span style="display: block;">
                                        @{{ item.show_e_ids }}
                                    </span>
                                </td>
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
                               
                                <td >
                                   
                                    <a href="{{url('/admin/rooms/print')}}/@{{item.id}}" class="btn btn-success btn-sm" target="_blank">Print</a>

                                    <a href="javascript:;" ng-click="initSingleEntry(item.id)" class="btn btn-warning btn-sm">View</a>
                            
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="l_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div>     
        </div>
    </div>
@endsection
    
    