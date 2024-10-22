<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="godownsHistoryCtrl" ng-init="g_stock_id={{$g_stock_id}};init();"> 
       
        <div class="card shadow mb-4 p-4">
            
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">
                        <div class="col-md-3 form-group">
                            <label>Date</label>
                            <input id="barcodevalue_search" type="text" ng-model="filter.from_date" class="form-control datepicker" />
                        </div>   
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-clicky="init()" class="btn btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div>
                <table class="table table-bordered table-striped" >
                    <thead style="background-color: rgba(0,0,0,.075);">
                        <tr class="table-primary">
                            <th>S.no</th>
                            <th>Date</th>
                            <th>Stock</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="stock_history.length > 0">
                        <tr ng-repeat="item in stock_history">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.date|date:'dd-MM-yyyy' }}</td>
                            <td>@{{ item.stock }}</td>
                            <td>
                                <a href="{{url('admin/godowns/history/')}}/@{{item.id}}" ng-click="edit(item.id)" class="btn btn-sm btn-warning">Edit</a>
                            </td>  
                        </tr>
                    </tbody>
                </table>
                <div ng-if="stock_history.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>  
           
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/godowns_ctrl.js?v='.$version)}}" ></script>
@endsection
    