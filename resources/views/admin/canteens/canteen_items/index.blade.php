@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="canteenItemsCtrl" ng-init="init();"> 
        <!--@include("admin.canteens.canteen_items.add")-->
        <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)" style="margin-top:20px;">

            <div class="row">
                <div class="col-md-3 form-group">
                    <label>Barcode</label>
                    <input id="barcodevalue" autofocus type="text" ng-model="formData.barcodevalue" class="form-control" required />
                </div>
                <div class="col-md-3 form-group">
                    <label>Item Name</label>
                    <input type="text" ng-model="formData.item_name" class="form-control" required />
                </div>
                <div class="col-md-3 form-group">
                    <label>Item Short Name</label>
                    <input type="text" ng-model="formData.item_short_name" class="form-control" required />
                </div>
                <div class="col-md-3 form-group">
                    <label>Price</label>
                    <input type="number" ng-model="formData.price" class="form-control" required  />
                </div>
               
              
            </div>
           
           
            <div class="pt-4">
                <button type="submit" class="btn btn-primary" ng-disabled="loading">
                    <span ng-if="!loading">Submit</span>
                    <span ng-if="loading">Loading...</span>
                </button> 
            </div>  
            
       </form>
        <div class="card shadow mb-4 p-4">
            
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label>Barcode Value</label>
                                    <input id="barcodevalue_search" autofocus type="text" ng-model="filter.barcodevalue_search" class="form-control" />
                                </div>           
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.item_name" />
                                </div>           
                              
                            </div>
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn btn-primary" style="width: 70px;">Search</button>
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
                            <th>Item</th>
                            <th>Item Short Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="canteen_items.length > 0">
                        <tr ng-repeat="item in canteen_items">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.item_name }}</td>
                            <td>@{{ item.item_short_name }}</td>
                            <td>@{{ item.price }}</td>
                            <td>@{{ item.stock }} <a href="{{url('admin/canteens/items/stock/')}}/@{{item.id}}" style="margin-left: 20px;text-decoration: underline;">View Stock</a></td>
                            <td>
                                <a href="javascript:;" class="btn btn-sm btn-warning" ng-click="edit(item.id)">Edit</a>
                            </td>

                           
                        </tr>
                    </tbody>
                </table>
                <div ng-if="canteen_items.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>  
           
        </div>
    </div>
@endsection
    