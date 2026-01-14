@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="userCtrl" ng-init="init();"> 
        @include('admin.users.add')
        <div class="card shadow mb-4 p-4">
            
            <div class="filters mb-3">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">

                        <div class="col-md-8">
                            <div class="row">                   
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.name" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Mobile</label>
                                    <input type="text" class="form-control" ng-model="filter.mobile" />
                                </div>
                                <div class="col-md-6" style="padding-top:23px;">
                                    <button type="button" ng-click="init()" class="btn btn-sm btn-primary">Search</button>

                                    <button type="button" ng-click="filterClear()" class="btn btn-sm btn-warning">Clear</button>

                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 end-text">
                            <button ng-if="add_new_flag" type="button" ng-click="add()" class="btn btn-sm btn-primary">Add</button>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead >
                            <tr >
                                <th>S.no</th>
                                <th>Name</th>
                                <th>Mobile</th>
                                <th>Email</th>
                                <th>Statue</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody ng-if="users.length > 0">
                            <tr ng-repeat="item in users">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.name }}</td>
                                <td>@{{ item.mobile }}</td>
                                <td>@{{ item.email }}</td>
                                <td><span ng-if="item.active == 1">Active</span><span ng-if="item.active == 0">Inactive</span></td>
                                <td>
                                    <a href="javascript:;" ng-click="edit(item.id)" class="btn btn-warning btn-sm">Edit</a>
                                     <a ng-click="activeUser(item, $index)" ng-show="item.active == 0 && item.priv == 3" class="btn btn-success btn-sm">Active</a>
                                     <a ng-click="activeUser(item, $index)" ng-show="item.active != 0 && item.priv == 3" class="btn btn-danger btn-sm">Inactive</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="users.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div>  
           
        </div>
    </div>
@endsection
    
    