<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="ExpenseCtrl" ng-init="init();" >
        <div class="row mt-3 mb-3">
            <div class="col-md-6">
                <h2 class="page-title">Expenses</h2>
            </div>
            <div class="col-md-6 text-right mt-3">
                <a href="{{url('admin/expenses/add')}}" class="btn btn-info">Add</a>
            </div>
        </div>
		
        <div style="margin-bottom: 20px;padding-bottom: 20px;border-bottom:  1px solid #555;">
            <div class="row">

                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="text" placeholder="MM/DD/YYYY" class="form-control datepicker" ng-model="searchData.from_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>To Date</label>
                    <input type="text" placeholder="MM/DD/YYYY" class="form-control datepicker" ng-model="searchData.to_date">
                </div>
                <div class="col-md-3 form-group">
                    <label>Branch</label>

                    <select ng-model="searchData.client_id" class="form-control" convert-to-number required>
                        <option value="">Select</option>
                        <option ng-value="@{{item.id}}" ng-repeat="(key, item) in clients">@{{item.client_name}}</option>
                    </select>
                </div>
                <!-- <div class="col-md-3 form-group">
                    <label>Expense Account</label>
                    <select class="form-control" convert-to-number ng-model="searchData.expense_account">
                        <option value="">Select</option>
                        <option ng-repeat="(key,value) in expense_accounts" value="@{{key}}">@{{value}}</option>
                    </select>
                </div> -->
                
                
            </div>
            <div class="row">
                <div class="col-md-3 " style="padding-top:10px;">
                    <button type="submit" ng-click="onSearch()" class="btn btn-primary">Search</button>
                    <button type="submit" ng-click="clearFilter()" class="btn btn-warning">Clear</button>
                </div>
            </div>
        </div>
        <div ng-if="loading" class="alert alert-warning">
            Loading
        </div>
        
        <table ng-if="!loading" class="table table-condensed table-bordered" >
            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Total Amount</th>
                    <th>Remarks</th>

                    
                    <!-- <th>Account</th> -->
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="expenses.length>0" ng-repeat="expense in expenses" ng-class="{'bg-green1': expense.check_status == 1 }" >
                    <td>@{{$index+1}}</td>
                    <td >@{{expense.date|date:'dd-MM-yyyy'}}</td>
                    <td>@{{expense.client_name}}</td>
                    <td>@{{expense.total_amount}}</td>
                    <td style="font-size: 11px">@{{expense.remark}}</td>

                 
                    <td style="text-align: center;">
                        
                        <a href="{{url('/admin/expenses/edit')}}/@{{expense.id}}" class="btn btn-sm btn-warning">Edit</a>
                        <a href="{{url('/admin/expenses/print')}}/@{{expense.id}}" class="btn btn-sm btn-primary">Print</a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div ng-if="expenses.length == 0 && !loading" class="alert alert-warning">Data Not Available !</div>
        <div>
        <!-- Modal -->
            <div id="myModal" class="modal custom-modal" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">Expense Details</h4>
                            <button type="btn" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <td><strong>Date</strong></td>
                                        <td>@{{expense.date|date:'dd-MM-yyyy'}}</td>
                                    </tr>
                                    
                                    <tr>
                                        <td><strong>Amount</strong></td>
                                        <td>@{{expense.amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>GST</strong></td>
                                        <td>
                                            @{{expense.gst}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total Amount</strong></td>
                                        <td>@{{expense.total_amount}}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Account Type</strong></td>
                                        <td>
                                            <span ng-if="expense.expense_account == 1 ">Company Account</span>
                                            <span ng-if="expense.expense_account == 2 ">Cash Account</span>
                                            <span ng-if="expense.expense_account == 3 ">Card Account</span>
                                        </td>
                                    </tr>
                                    <tr ng-if="expense.expense_file != null">
                                        <td><strong>Expense File</strong></td>
                                        <td>
                                            <a href="../@{{expense.expense_file}}" target="_blank">View File</a>
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