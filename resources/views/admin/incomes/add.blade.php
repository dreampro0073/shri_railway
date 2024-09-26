<?php $version = env('JS_VERSION'); ?>
        
@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')
	<div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h2 class="page-title">{{($income_id == 0)?'Add Income':'Update Income'}}</h2>
        </div>
        <div class="col-md-6 text-right" style="padding-top: 23px;">
            <a href="{{url('/admin/income')}}" class="btn btn-success" >Go Back</a>
        </div>
    </div>
    <div ng-controller="IncomeCtrl" ng-init="income_id={{(isset($income_id))?$income_id:''}};edit();">
        <form name="IncomeForm" novalidate ng-submit="onSubmit(IncomeForm.$valid)">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Date</label>

                    <input type="text" ng-change="changeDate()" placeholder="DD-MM-YYYY" class="datepicker form-control" ng-model="formData.date" required>
                    
                </div> 
                <div class="col-md-3 form-group">
                    <label>Client</label>
                    <select ng-model="formData.client_id" ng-change="changeDate()" class="form-control" required convert-to-number>
                        <option value="">Select</option>
                        <option value="@{{key}}" ng-repeat="(key,value) in clients">@{{value}}</option>
                    </select>
                    
                </div> 
                <div class="form-group col-md-2">
                    <label>Today Total Amount</label>
                    
                    <input type="text" readonly ng-model="formData.total_amount" class="form-control">
                </div>
                <div class="form-group col-md-2">
                    <label>Back Balance</label>
                    
                    <input type="text" ng-model="formData.back_balance" class="form-control" ng-change="calAllSum()">
                </div>
                 <div class="form-group col-md-2">
                    <label>All Total</label>
                    
                    <input type="text" readonly  ng-model="formData.all_total" class="form-control">
                </div>
            </div>
            <hr>
            <div ng-repeat="c_service in formData.c_services" style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">

                <div class="row">
                     
                    <div class="col-md-3 form-group">
                        <label>Source</label>
                        <input type="text" readonly ng-model="c_service.source" class="form-control">
                    </div>
                    
                    <div class="col-md-2 form-group">
                        <label>Cash Amount</label>
                        <input type="text" ng-readonly="c_service.service_id != 7" ng-model="c_service.cash_amount" ng-change="calAllSum()" class="form-control">
                        
                    </div>
                    <div class="col-md-2 form-group">
                        <label>UPI Amount</label>
                        <input type="text" ng-readonly="c_service.service_id != 7" ng-model="c_service.upi_amount" ng-change="calAllSum()" class="form-control">
                        
                    </div>
                    <div class="col-md-2 form-group">
                        <label>Total Amount</label>
                        <input type="text" readonly ng-model="c_service.total_amount" class="form-control">
                        
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Remarks</label>
                        <input type="text" ng-model="c_service.remarks" class="form-control">
                    </div>
                
                </div>
            </div>
            
            <div style="margin-top: 15px;">
                <!-- <a ng-if="income_id==0" href="javascript:;" ng-click="addMore()" class="btn btn-warning">Add More</a> -->
                <button type="submit" ladda="processing" class="btn btn-primary">Submit</button>
            </div>
            
        </form>


    </div>
@endsection

@section('footer_scripts')
   
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>

    
@endsection