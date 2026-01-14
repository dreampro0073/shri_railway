@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="dailyEntryCtrl" ng-init="init();initCanteenItems();"> 
        @include("admin.canteens.daily_entries.add")
        <div class="card shadow mb-4 p-4">      
            <div class="filters mb-3">
                <form name="filterForm"  novalidate>
                    <div class="row">
     
                        <div class="col-md-2 form-group">
                            <label class="label-control">Bill ID</label>
                            <input type="text" class="form-control" ng-model="filter.id" />
                        </div> 

                        <div class="col-md-2 form-group">
                            <label class="label-control">Name</label>
                            <input type="text" class="form-control" ng-model="filter.name" />
                        </div>                    
                        <div class="col-md-2 form-group">
                            <label class="label-control">Mobile</label>
                            <input type="text" class="form-control" ng-model="filter.mobile" />
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn btn-sm btn-primary">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn btn-sm  btn-warning">Clear</button>
                        
                        </div>
                      
                    </div>
                </form>
                <div class="dt-layout-row dt-layout-table">
                    <div class="dt-layout-cell">
                        
                        <table class="table table-bordered table-striped" >
                            <thead>
                                <tr>
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

            </div>
             
        </div>
    </div>
@endsection
    
    