@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="massageCtrl" ng-init="init();"> 
        @include('admin.massage.add')
        <div class="card shadow mb-4 p-4">
            
            <div class="filters mb-3">
                <div class="row">
                    <div class="col-md-8">
                        <form name="filterForm"  novalidate>
                            <div class="row" style="font-size: 14px">
                                <div class="col-md-4 form-group">
                                    <label class="label-control">Bill Number</label>
                                    <input type="text" class="form-control" ng-model="filter.unique_id" />
                                </div>                    
                                <div class="col-md-4 text-right" style="margin-top: 28px;" class="mb-2">
                                    <button type="button" ng-click="init()" class="btn btn-sm btn-primary" >Search</button>
                                    <button type="button" ng-click="filterClear()" class="btn btn-sm btn-warning" >Clear</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-4 text-end">
                        <button type="button" ng-click="add()" class="btn  btn-secondary btn-sm" style="width: 70px;margin-top:28px;">Add</button>
                    </div>
                </div>
            </div>
            
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead >
                            <tr>
                                <th>S.no</th>
                                <th>Bill no</th>
                                <th>Name</th>

                                <th>Pay Type</th>
                                <th>Total Amount</th>
                               
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody ng-if="m_entries.length > 0">
                            <tr ng-repeat="item in m_entries" ng-class="{'my_class': item.deleted == 1}">
                                <td>@{{ $index+1 }}</td>
                               
                                <td>@{{ item.unique_id }}</td>
                                <td>@{{ item.name }}</td>
                                <td>
                                    <span ng-if="item.pay_type == 1">Cash</span>
                                    <span ng-if="item.pay_type == 2">UPI</span>
                                </td>
                                <td>@{{ item.paid_amount }}</td>
                                
                                <td>
                                    <a href="{{url('/admin/massage/print')}}/@{{item.id}}" class="btn btn-secondary btn-sm" target="_blank">Print</a>
                                  
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="m_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div> 
           
        </div>
    </div>
@endsection
    
    