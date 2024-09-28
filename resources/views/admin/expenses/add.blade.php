<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')
	<div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h2 class="page-title">{{($expense_id == 0)?'Add Expense':'Update Expense'}}</h2>
        </div>
        <div class="col-md-6 text-right mt-3">
            <a href="{{url('/admin/expenses')}}" class="btn btn-success" >Go Back</a>
        </div>
    </div>
    <div ng-controller="ExpenseCtrl" ng-init="expense_id={{(isset($expense_id))?$expense_id:''}};edit();">
        <form name="ExpenseForm" ng-submit="onSubmit(ExpenseForm.$valid)" novalidate="novalidate">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Date</label>
                    <input type="text" placeholder="MM-DD-YYYY" class="form-control datepicker" ng-model="formData.date" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>Client</label>

                    <select ng-model="formData.client_id" class="form-control" required convert-to-number>
                        <option value="">Select</option>
                        <option value="@{{key}}" ng-repeat="(key,value) in clients">@{{value}}</option>
                    </select>
                       
                </div>
                 <div class="form-group col-md-2">
                    <label>Day Amount</label>
                    
                    <input type="text" readonly ng-model="formData.total_amount" class="form-control">
                </div>
            </div>
            <hr>
            <div ng-repeat="single_expense in formData.multiple_expense track by $index" style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
                
                <div class="row">
                    

                    <div class="col-md-3 form-group">
                        <label>Amount</label>
                        <input type="number" class="form-control" ng-model="single_expense.amount" ng-change="calAllSum()">
                    </div>
                    <div class="col-md-3 form-group">
                        <label>GST</label>
                        <input type="number" class="form-control" ng-model="single_expense.gst" ng-change="calAllSum()"> 
                    </div>
                   
                    <div class="col-md-3 form-group">
                        <label>Total Amount</label>
                        <span style="height:42px;background: #eee;" class="form-control">@{{single_expense.total_amount=single_expense.amount+single_expense.gst}}</span>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Expense Account</label>
                        <select class="form-control" convert-to-number ng-model="single_expense.expense_account">
                            <option value="">Select</option>
                            <option ng-repeat="(key,value) in expense_accounts" value="@{{key}}">@{{value}}</option>
                        </select>
                    </div>
                   
                </div>
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Expense Type</label>
                        <input type="text" class="form-control" ng-model="single_expense.expense_type">
                    </div>
                    <div class="col-md-9 form-group">
                        <label>Remarks</label>
                        <input type="text" class="form-control" ng-model="single_expense.remarks">
                    </div>
                </div>
                
            </div>
            <hr>
            <div style="margin-top: 10px;">
                @if($expense_id ==0)
                    <a href="javascript:;" class="btn btn btn-success" ng-click="duplicate()">Duplicate</a>
                @endif
                <button type="submit" class="btn btn-primary" ladda="processing">Submit</button>   
            </div>
            <div style="margin-top: 15px;">
                
            </div>
        </form>

    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>
@endsection