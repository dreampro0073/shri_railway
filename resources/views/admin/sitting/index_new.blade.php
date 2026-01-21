<?php 
    $client_ids = Session::get('client_ids');
?>
@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="sittingCtrl" ng-init="init();"> 
        @include('admin.sitting.add')
        @include('admin.sitting.checkout')
        <div class="card shadow mb-4 p-4">

            <div class="filters" style="margin:24px 0;">
                <div class="form-group">
                    <input autofocus type="text" id="productName" ng-model="productName" ng-keypress="handleKeyPress($event)"
                   style="padding: 10px; border-radius: 4px; border: 1px solid #ccc; margin-bottom: 10px; width: 100%;"
                   placeholder="Barcodevalue">
                </div>
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">
                        <div class="col-md-3 form-group">
                            <label class="label-control">Slip ID</label>
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
                        
                        <div class="col-md-2 form-group">
                            <label class="label-control">From Date</label>
                            <input type="text" class="form-control datepicker" ng-model="filter.from_date" />
                        </div>                                
                        <div class="col-md-2 form-group">
                            <label class="label-control">To Date</label>
                            <input type="text" class="form-control datepicker" ng-model="filter.to_date" />
                        </div>                                
                        @if(Auth::user()->priv == 2)
                            <div class="col-md-3 form-group">
                                <label class="label-control">Added By</label>
                                <select class="form-control" ng-model="filter.added_by">
                                    <option value="">All</option>
                                    <option value="@{{user.id}}" ng-repeat="user in users">@{{user.name}}</option>
                                </select>
                            </div>
                        @endif

                        <div class="col-md-5 text-right mb-2" style="margin-top: 32px;">
                            <button type="button" ng-click="init()" class="btn btn-primary-600 border border-primary-600 text-md btn-sm radius-8" >Search</button>
                            <button type="button" ng-click="filterClear()" class="btn btn-secondary-600 border border-primary-600 text-md btn-sm radius-8" >Clear</button>
                            @if(Auth::user()->priv !=4)
                            <button type="button" ng-click="add()" class="btn btn-warning-600 border border-warning-600 text-md btn-sm radius-8" >Add</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Slip ID</th>   
                                <th>Name</th>
                                <th>Mobile No</th>
                                <th>PNR</th>
                                <th>Date</th>
                                <th>Validity</th>
                                <th>Paid Amount</th>
                                <th>Pay Type</th>
                              
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody ng-if="entries.length > 0">
                            <tr ng-repeat="item in entries" ng-class="item.check_class">

                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.slip_id }}</td>
                                
                                <td>@{{ item.name }}</td>
                                <td>@{{ item.mobile_no }}</td>

                                <td>@{{ item.pnr_uid }}</td>
                                <td>@{{ item.show_date }}</td>
                                <td>@{{ item.show_time }} (@{{item.hours_occ}} Hr)</td>
                                <td>@{{ item.paid_amount }}</td>
                                <td>
                                    <span ng-if="item.pay_type == 1">Cash </span>
                                    <span ng-if="item.pay_type == 2">UPI </span>
                                    @if(in_array(Auth::user()->client_id,$client_ids))
                                    <!-- <span ng-if="item.added_by == {{ Auth::id() }}">
                                        <a onclick="return confirm('Are you sure?')" ng-if="item.checkout_status != 1" href="{{url('/admin/sitting/change-pay-type')}}/@{{item.id}}" style="font-size: 15px;"><i class="fa fa-edit"> </i></a>
                                    </span> -->
                                    @endif
                                </td>
                               <!--  @if(Auth::user()->priv == 1)

                                <td>
                                    <div ng-if="item.deleted == 1">
                                        <span >@{{item.username}},</span>
                                        <span >@{{item.delete_time}}</span>
                                    </div>
                                </td>
                                @endif -->
                                
                                <td>
                                    @if(Auth::user()->client_id != 1 && (Auth::user()->priv == 2 || Auth::user()->priv == 1))
                                       <a href="javascript:;" ng-if="item.checkout_status != 1 " ng-click="newEditCheckout(item.id)" class="btn btn-danger-600 border border-danger-600 text-md btn-sm radius-8">Checkout</a>
                                    @endif 

                                    @if(Auth::user()->priv == 2)
                                       <a onclick="return confirm('Are you sure?')" href="{{url('/admin/sitting/checkout-without-penalty')}}/@{{item.id}}" ng-if="item.checkout_status != 1 && item.check_class == 't-danger'" class="btn btn-warning-600 border border-warning-600 text-md btn-sm radius-8 mb-2">Checkout WP</a>
                                    @endif
                                    <a ng-if="item.checkout_status != 1" href="javascript:;" ng-click="edit(item.id)" class="btn btn-primary-600 border border-primary-600 text-md btn-sm radius-8">Edit</a>
                                    
                                    <a ng-if="item.checkout_status != 1" href="{{url('/admin/sitting/print-unq/2/')}}/@{{item.barcodevalue}}"class="btn btn-seconday-600 border border-seconday-600 text-md btn-sm radius-8" target="_blank">Print</a>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>  
           
        </div>
    </div>
@endsection
    
    