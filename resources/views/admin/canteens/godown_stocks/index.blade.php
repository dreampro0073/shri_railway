<?php $version = env('JS_VERSION'); ?>

@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="godownsCtrl" ng-init="init();"> 
        <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)" style="margin-top:20px;">

            <div class="row">
                <div class="col-md-4 form-group">
                    <label>Barcode</label>
                    <selectize placeholder='Select a Item' config="selectConfig" options="canteen_items" ng-model="formData.barcodevalue" required></selectize>
                </div>
                <div class="col-md-3 form-group">
                    <label>Date</label>
                    <input type="text" ng-model="formData.date" class="form-control datepicker" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>Stock</label>
                    <input type="number" ng-model="formData.stock" class="form-control" required  />
                </div>
                <div class="col-md-2 form-group" style="margin-top:23px;">
                   
                    <button type="submit" class="btn btn-primary" ng-disabled="processing">
                        <span ng-if="!processing">Submit</span>
                        <span ng-if="processing">Loading...</span>
                    </button> 
                </div>
            </div>
        </form>
        <hr>
        <div class="card shadow mb-4 p-4">
            
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Barcode Search</label>
                                    <input id="barcodevalue_search" autofocus type="text" ng-model="filter.barcodevalue" class="form-control" />
                                </div>           
                                <!-- <div class="col-md-3 form-group">
                                    <label class="label-control">Item Name Search</label>
                                    <input type="text" class="form-control" ng-model="filter.item_name" />
                                </div>  -->          
                              
                            </div>
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-clicky="init()" class="btn btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                            <button type="button" ng-click="add()" class="btn btn-primary" style="width: 70px;">Add</button>
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
                            <th>Barcodevalue/Item</th>
                            <th>Last Date</th>
                            
                            <th>Stock</th>
                            
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="g_entries.length > 0">
                        <tr ng-repeat="item in g_entries">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.barcodevalue }} - @{{ item.item_name }} </td>
                            
                            <td>@{{ item.date|date:'dd-MM-yyyy' }}</td>
                            <td>@{{ item.stock }}</td>
                            <td>
                                <a href="{{url('admin/godowns/history/')}}/@{{item.id}}" class="btn btn-sm btn-warning">History</a>
                            </td>
                           
                        </tr>
                    </tbody>
                </table>
                <div ng-if="g_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>  
           
        </div>
    </div>
@endsection

@section('footer_scripts')
    <script type="text/javascript" src="{{url('assets/scripts/core/godowns_ctrl.js?v='.$version)}}" ></script>
@endsection
    