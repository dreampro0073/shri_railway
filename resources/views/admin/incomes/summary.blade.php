<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="SummaryCtrl" ng-init="init();" >
        <div class="mb-3 mt-3" style="border-bottom:  1px solid #555;padding: 20px 0;">
            <div class="row">
                <div class="col-md-2 form-group">
                    <label>Date</label>
                    <input type="text" placeholder="DD-MM-YYYY" class="form-control datepicker" ng-model="searchData.date">
                </div>
                <div class="col-md-3 form-group">
                    <label>Branch</label>

                    <select ng-model="searchData.client_id" class="form-control" convert-to-number required>
                        <option value="">Select</option>
                        <option value="@{{key}}" ng-repeat="(key, value) in clients">@{{value}}</option>
                    </select>
                </div> 
                <div class="col-md-5 " style="margin-top:28px;">
                    <button type="submit" ng-click="onSearch()" class="btn btn-primary">Search</button>
                    <button type="submit" ng-click="clearFilter()" class="btn btn-warning">Clear</button>
                    <button type="submit" ng-click="export()" class="btn btn-danger">Export</button>
                    
                </div>
            </div>
        </div>
        <div ng-if="loading" class="alert alert-warning">
            Loading
        </div>

        <div ng-if="!loading" class="row mt-3 mb-3">
            <div class="col-md-4">
                <h5 class="fw-semibold">Summary</h5>
            </div>
        </div>
        <div class="text-right">
            <small style="color: red">*Note: Expenses and previous balance are subtracted from onlys "Income Cash Amount"!*</small>
        </div>

        <table ng-if="!loading" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th rowspan="2">Previous Balance</th>
                <th rowspan="2">Total Expense</th>
                <th colspan="3">Income</th>
                <th colspan="3">Balance</th>
            </tr>
            <tr>
                <td>Cash Amount</td>
                <td>UPI Amount</td>
                <td>Total Amount</td>
                <td>Cash</td>
                <td>UPI</td>
                <td>Total</td>
            </tr>
                <tr>
                    <th>@{{income.back_balance}}</th>
                    <th>@{{total_expenses}}</th>
                    <th>@{{income.cash_amount}}</th>
                    <th>@{{income.upi_amount}}</th>
                    <th>@{{income.total_amount}}</th>
                    <th style="color: red;">@{{income.cash_amount - income.back_balance - total_expenses}}</th>
                    <th style="color: red;">@{{income.upi_amount}}</th>
                    <th style="color: red;"><b>@{{income.upi_amount + income.cash_amount - income.back_balance - total_expenses}}</b></th>
                </tr>
            </thead>
        </table>
        <hr>

        <div ng-if="!loading" class="row mt-3 mb-3">
            <div class="col-md-6">
                <h5 class="fw-semibold">Income</h5>
            </div>  
            <div class="col-md-6">
                <h5 class="fw-semibold mb-3">Total Income : @{{income.total_amount}}</h5>
            </div>          
        </div>

        <table ng-if="!loading" class="table table-condensed table-bordered table-striped" >

            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Source</th>
                    <th>Total Cash</th>
                    <th>Total UPI</th>
                    <th>Total Amount</th>
                    <th>Remarks</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="income.multiple_income.length > 0" ng-repeat="item in income.multiple_income" >
                    <td>@{{$index+1}}</td>
                    <td >@{{item.source}}</td>
                    <td>@{{item.cash_amount}}</td>
                    <td>@{{item.upi_amount}}</td>
                    <td>@{{item.total_amount}}</td>
                    <td>@{{item.remarks}}</td>
                </tr>
            </tbody>
        </table>
        <hr>
        <div ng-if="!income.multiple_income && !loading" class="alert alert-warning">Data Not Available !</div>
        <div ng-if="!loading">
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <h5 class="fw-semibold">Expense </h5>
                </div>
                <div class="col-md-6">
                    <h5 class="fw-semibold">Total Expense : @{{total_expenses}}</h5>
                </div>
            </div>

            <table class="table table-condensed table-bordered" >
                <thead>
                    <tr>
                        <th>Sn</th>
                        <th>Date</th>
                        <th>Branch</th>
                        <th>Total Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-if="expenses.length > 0" ng-repeat-start="expense in expenses" ng-class="{'bg-green1': expense.check_status == 1}">
                        <td>@{{$index+1}}</td>
                        <td>@{{expense.date | date:'dd-MM-yyyy'}}</td>
                        <td>@{{expense.client_name}}</td>
                        <td>@{{expense.total_amount}}</td>
                        <td style="font-size: 11px">@{{expense.remarks}}</td>
                    </tr>
                    <tr ng-if="expense.multiple_expense.length > 0">
                        <td colspan="5">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Give To</th>
                                        <th>Remarks</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr ng-repeat="item in expense.multiple_expense">
                                        <td>@{{item.expense_type}}</td>
                                        <td>@{{item.remarks}}</td>
                                        <td>
                                            <span ng-if="item.expense_account == 1">Cash</span>
                                            <span ng-if="item.expense_account == 2">UPI</span>
                                        </td>
                                        <td>@{{item.total_amount}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr ng-repeat-end></tr>
                </tbody>

            </table>
            <div ng-if="expenses.length == 0 && !loading" class="alert alert-warning">Data Not Available !</div>
        </div>
        
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>
@endsection