<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout') 

@section('header_scripts')
 
@endsection

@section('main')

	<div ng-controller="SummaryCtrl" ng-init="init();" >
		
        <div class="row mt-3 mb-3">
            <div class="col-md-6">
                <h2 class="page-title">Summary</h2>
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
            </div>
        </div>
        <div ng-if="loading" class="alert alert-warning">
            Loading
        </div>
        
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/exin_ctrl.js?v='.$version)}}" ></script>
@endsection