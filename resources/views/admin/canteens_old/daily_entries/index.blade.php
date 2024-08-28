@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="dailyEntryCtrl" ng-init="init();initCanteenItems();"> 
        @include("admin.canteens.daily_entries.add")
        <div class="card shadow mb-4 p-4">      
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">

                        <div class="col-md-9">
                            <div class="row">
         
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Bill ID</label>
                                    <input type="text" class="form-control" ng-model="filter.id" />
                                </div> 

                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.name" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Mobile</label>
                                    <input type="text" class="form-control" ng-model="filter.mobile" />
                                </div>
                              
                            </div>
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                        
                        </div>
                    </div>
                </form>
                <div>
                    
                    <table class="table table-bordered table-striped" >
                        <thead style="background-color: rgba(0,0,0,.075);">
                            <tr class="table-primary">
                                <th>S.no</th>
                                <th>Bill ID</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Paid Amount</th>
                                <th>Time</th>
                                
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody ng-if="daily_entries.length > 0">
                            <tr ng-repeat="item in daily_entries">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.id }}</td>
                                <td>@{{ item.name }}</td>
                                <td>@{{ item.mobile }}</td>
                                <td>@{{ item.total_amount }}</td>
                                <td>@{{ item.time }}</td>
                               
                                <td>
                                    <a href="{{url('admin/daily-entries/print/')}}/@{{item.id}}" class="btn btn-sm btn-warning" target="_blank" ng-click="#">Print</a>
                                </td>

                               
                            </tr>

                        </tbody>
                    </table>
                    <div ng-if="daily_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>  
            </div>
            <hr>
             
        </div>
    </div>
@endsection
    
    