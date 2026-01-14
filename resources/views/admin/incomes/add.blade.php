<?php $version = env('JS_VERSION'); ?>
        
@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')
	<div class="row mt-3 mb-3">
        <div class="col-md-6">
            <h5 class="fw-semibold mb-3">{{($income_id == 0)?'Add Income':'Update Income'}}</h5>
        </div>
        <div class="col-md-6 text-end" style="padding-top: 23px;">
            <a href="{{url('/admin/income')}}" class="btn btn-success" >Go Back</a>
        </div>
    </div>
    <div ng-controller="IncomeCtrl" ng-init="income_id={{(isset($income_id))?$income_id:''}};edit();">
        <form name="IncomeForm" novalidate ng-submit="onSubmit(IncomeForm.$valid)">
            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Date</label>

                    <input type="text" ng-change="changeDate()" placeholder="DD-MM-YYYY" class="datepicker form-control" ng-model="formData.date" required>
                    
                </div> 
                <div class="col-md-4 form-group">
                    <label>Client</label>
                    <select ng-model="formData.client_id" ng-change="changeDate()" class="form-control" required convert-to-number>
                        <option value="">Select</option>
                        <option value="@{{key}}" ng-repeat="(key,value) in clients">@{{value}}</option>
                    </select>
                    
                </div> 
                <div class="form-group col-md-4">
                    <label>Today Total Amount</label>
                    
                    <input type="text" readonly ng-model="formData.total_amount" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Previous Balance</label>
                    
                    <input type="text" ng-model="formData.back_balance" class="form-control" ng-change="calAllSum()">
                </div>
                <div class="form-group col-md-3">
                    <label>Cash Amount</label>
                    
                    <input type="text" readonly  ng-model="formData.cash_amount" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>UPI Amount</label>
                    
                    <input type="text" readonly  ng-model="formData.upi_amount" class="form-control">
                </div>
                <div class="form-group col-md-3">
                    <label>Day Total</label>
                    
                    <input type="text" readonly  ng-model="formData.all_total" class="form-control">
                </div>
            </div>
            <hr>

            <table ng-if="!loading" class="table table-striped table-hover table-bordered">
                <thead>
                    <tr>
                        <th>Source</th>
                        <th>Cash Amount</th>
                        <th>UPI Amount</th>
                        <th>Total Amount</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="(index,c_service) in formData.c_services" style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
                        <th>
                            @{{c_service.source}}
                        </th>
                        <td>
                            <div class="form-group">
                                <input min="0" type="text" ng-readonly="c_service.service_id != 6" ng-model="c_service.cash_amount" ng-change="calAllSumOther($index)" class="form-control">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input min="0" type="text" ng-readonly="c_service.service_id != 6" ng-model="c_service.upi_amount" ng-change="calAllSumOther($index)" class="form-control">
                            </div>
                        </td>
                        <th>
                            <div class="form-group">
                                <input type="text" readonly  min="0" ng-model="c_service.total_amount" class="form-control">
                            </div>
                        </th>
                        <td>
                            <div class="form-group">
                                <textarea ng-model="c_service.remarks" class="form-control"></textarea>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <div ng-if="!loading" style="margin-top: 15px;">
                <button type="submit" ladda="processing" class="btn btn-primary">Submit</button>
            </div>
            
        </form>

        <div ng-if="loading" class="alert alert-danger">Loading...</div>
    </div>
@endsection

@section('footer_scripts')
   
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>

    
@endsection