<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="IncomeCtrl" ng-init="init();" >
		
        <div class="row mt-3 mb-3">
            <div class="col-md-6">
                <h2 class="page-title">Income</h2>
            </div>
            <div class="col-md-6 text-right mt-3">
                <a href="{{url('admin/income/add')}}" class="btn btn-info">Add</a>
            </div>
        </div>
        <div style="margin-bottom: 20px;padding-bottom: 20px;border-bottom:  1px solid #555;">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="text" placeholder="DD-MM-YYYY" class="form-control datepicker" ng-model="searchData.from_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>To Date</label>
                    <input type="text" placeholder="DD-MM-YYYY" class="form-control datepicker" ng-model="searchData.to_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>Branch</label>

                    <select ng-model="searchData.client_id" class="form-control" convert-to-number required>
                        <option value="">Select</option>
                        <option value="@{{item.id}}" ng-repeat="(key, item) in clients">@{{item.client_name}}</option>
                    </select>
                </div> 
                <div class="col-md-3 " style="margin-top:28px;">
                    <button type="submit" ng-click="onSearch()" class="btn btn-primary">Search</button>
                    <button type="submit" ng-click="clearFilter()" class="btn btn-warning">Clear</button>
                </div>
                <!-- <div class="col-md-2 form-group">
                    <label>From</label>
                    <select class="form-control" convert-to-number ng-model="searchData.income_type">
                        <option value="">Select</option>
                        <option ng-repeat="(key,value) in income_types" value="@{{key}}">@{{value}}</option>
                    </select>
                </div> -->
            </div>
        </div>
        <div ng-if="loading" class="alert alert-warning">
            Loading
        </div>
        <table ng-if="!loading" class="table table-condensed table-bordered table-striped" >
            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>Client</th>
                    <th>All Amount</th>
                    <th>Total Amount</th>
                    <th>Back Balance</th>
                    
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="incomes.length>0" ng-repeat="income in incomes" >
                    <td>@{{$index+1}}</td>
                    <td >@{{income.date|date:'dd-MM-yyyy'}}</td>
                    <td >@{{income.client_name}}</td>
                    <td>
                        <span>@{{income.all_total}}</span>
                    </td>
                    <td>@{{income.total_amount}}</td>
                    <td>@{{income.back_balance}}</td>
                    
                    
                    <td style="text-align: center;">
                       
                        <a href="{{url('/admin/income/edit')}}/@{{income.id}}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{url('/admin/income/print')}}/@{{income.id}}" class="btn btn-sm btn-primary">Print</a>
                        <!-- <button class="btn btn-sm btn-danger" ng-click="deleteIncome(income,$index)">Delete</i></button> -->
                    </td>
                </tr>
            </tbody>
        </table>

        <div ng-if="incomes.length == 0 && !loading" class="alert alert-warning">Data Not Available !</div>
        <div>
        <!-- Modal -->
            <div id="myModal" class="modal custom-modal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Income Details</h4>
                            <button type="btn" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>Date</strong></td>
                                        <td>@{{income.date|date:'dd-MM-yyyy'}}</td>
                                    </tr>
                                   
                                    <tr>
                                        <td><strong>Amount</strong></td>
                                        <td>@{{income.amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Amount</strong></td>
                                        <td>@{{income.amount}}</td>
                                    </tr>
                                    
                                    <tr ng-if="income.income_file != null">
                                        <td><strong>Income File</strong></td>
                                        <td>
                                            <a href="../@{{income.income_file}}" target="_blank">View File</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>
@endsection