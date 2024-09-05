@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="cloackCtrl" ng-init="type = {{$type}};init();"> 
        <div class="filters" style="margin:24px 0;">
            <div class="row">
                <div class="col-md-3 form-group">
                    <label>From Date</label>
                    <input type="text" class="datepicker form-control" ng-model="filter.from_date">
                    
                </div>
                <div class="col-md-3 form-group" >
                    <label>To Date</label>

                    <input type="text" class="datepicker1 form-control" ng-model="filter.to_date">      
                </div>
                <div class="col-md-3" style="margin-top:27px;">
                    <button ng-click="export()" class="btn btn-primary">
                        Search
                    </button>
                    
                </div>
            </div>
            <hr>
        </div>
    </div>
@endsection
    
    