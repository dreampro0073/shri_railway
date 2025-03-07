@extends('admin.layout')
<?php  
    $service_ids = Session::get('service_ids');
?>

@section('main')
    <div class="main" ng-controller="clientSettingCtrl" ng-init="init();"> 
        <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)">

            <div class="row" ng-repeat="item in clients">
                <div class="col-md-4 form-group">
                    <label>Name</label>
                    <input type="text" disabled readonly ng-model="item.client_name" class="form-control" required />
                </div>
               <div class="col-md-4 form-group">
                    <label>Amount</label>
                    <input type="text"  ng-model="item.hide_amount" class="form-control" required />
               </div>
            </div>

            <div ng-if="!loading" style="margin-top: 15px;">
                <button type="submit" ladda="processing" class="btn btn-primary">Submit</button>
            </div>
       </form>
    </div>
@endsection