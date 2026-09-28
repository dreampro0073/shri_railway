@extends('admin.layout')
<?php  
    $service_ids = Session::get('service_ids');
?>

@section('main')
    <div class="main" ng-controller="clientSettingCtrl" ng-init="init();">
        <div class="setting-loader" ng-if="loading || processing">
            <div class="setting-loader-box">
                <i class="fa fa-spinner fa-spin"></i>
                <span ng-if="loading">
                    Loading...
                </span>
                <span ng-if="processing && !loading">
                    Saving...
                </span>
            </div>
        </div>
        <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)" style="margin-top:50px;">

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Date</label>
                    <input type="date" ng-model="filter.hide_date" ng-change="init()" ng-disabled="processing" class="form-control" required />
                </div>
            </div>

            <div class="row" ng-repeat="item in clients track by item.id">
                <div class="col-md-4 col-6 form-group">
                    <label>Name</label>
                    <input type="text" disabled readonly ng-model="item.client_name" class="form-control" />
                </div>
                <div class="col-md-4 col-6 form-group">
                    <label>Amount</label>
                    <input type="number" min="0" step="1" ng-model="item.hide_amount" ng-disabled="loading || processing" class="form-control" required />
                </div>
            </div>
            <div ng-if="!loading && clients.length == 0" class="alert alert-warning">
                No clients found.
            </div>

            <div style="margin-top:15px;">
                <button type="submit" ladda="processing" ng-disabled="loading || processing || !clients.length || myForm.$invalid" class="btn btn-primary">
                    Submit
                </button>
            </div>
        </form>
    </div>
@endsection