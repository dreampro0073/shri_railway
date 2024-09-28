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
            <div class="col-md-6">
                <h2 class="page-title">Income</h2>
            </div>            
            <div class="col-md-6">
                <h2 class="page-title text-right">Total Income : @{{income.total_income}} </h2>
            </div>
        </div>
        <table ng-if="!loading" class="table table-condensed table-bordered table-striped" >

            <thead>
                <tr>
                    <th>Sn</th>
                    <th>Date</th>
                    <th>Branch</th>
                    <th>Total Cash</th>
                    <th>Total UPI</th>
                    <th>Total Amount</th>
                    <th>Back Balance</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-if="incomes.length>0" ng-repeat="income in incomes" >
                    <td>@{{$index+1}}</td>
                    <td >@{{income.date|date:'dd-MM-yyyy'}}</td>
                    <td >@{{income.client_name}}</td>
                    <td>@{{income.total_cash}}</td>
                    <td>@{{income.total_upi}}</td>
                    <td>@{{income.total_amount}}</td>
                    <td>@{{income.back_balance}}</td>
                </tr>
            </tbody>
        </table>
        <div ng-if="incomes.length == 0 && !loading" class="alert alert-warning">Data Not Available !</div>
        <span ng-if="!loading">
            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <h2 class="page-title">Expense </h2>
                </div>
                <div class="col-md-6">
                    <h2 class="page-title text-right">Total Expense : @{{total_expenses}} </h2>
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
                    <tr ng-if="expenses.length>0" ng-repeat="expense in expenses" ng-class="{'bg-green1': expense.check_status == 1 }" >
                        <td>@{{$index+1}}</td>
                        <td >@{{expense.date|date:'dd-MM-yyyy'}}</td>
                        <td>@{{expense.client_name}}</td>
                        <td>@{{expense.total_amount}}</td>
                        <td style="font-size: 11px">@{{expense.remarks}}</td>
                    </tr>
                </tbody>
            </table>
            <div ng-if="expenses.length == 0 && !loading" class="alert alert-warning">Data Not Available !</div>

            <div class="row mt-3 mb-3">
                <div class="col-md-6">
                    <h2 class="page-title">Summary</h2>
                </div>
            </div>

            <table class="table table-striped table-bordered">
                <tr>
                    <th>Total Incomes</th>
                    <th>Total Expenses</th>
                    <th>Balance</th>
                </tr>            
                <tr>
                    <th>@{{total_incomes}}</th>
                    <th>@{{total_expenses}}</th>
                    <th>@{{total_incomes - total_expenses}}</th>
                </tr>
            </table>
        </span>
        
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>
@endsection