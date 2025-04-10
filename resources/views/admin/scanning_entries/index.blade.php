@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="scanningCtrl" ng-init="init();"> 
        @include("admin.scanning_entries.add")
        <div class="card shadow mb-4 p-4">
            
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="label-control">Name</label>
                            <input type="text" class="form-control" ng-model="filter.name" />
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
                            <th>Name</th>
                            <th>Item Type</th>
                            <th>No. of Item</th>
                            <th>Paid Amount</th>
                            <th>Payment</th>
                            <th>Inword/Outword</th>
	                        <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="entries.length > 0">
                        <tr ng-repeat="item in entries">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.name }}</td>
                            <td>@{{ item.item_type_name }}</td>
                            <td>@{{ item.no_of_item }}</td>
                            <td>@{{ item.paid_amount }}</td>
                            <td>@{{ item.show_pay_type }}</td>
                            <td>@{{ item.incoming_type }}</td>
                            <td>
                                @if(Auth::user()->priv == 2)
                                    <a target="_blank" ng- href="{{url('admin/scanning/print/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print</a>
                                    <a target="_blank" ng- href="{{url('admin/scanning/print-qr/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print QR</a>
                                @endif
                                @if(Auth::user()->priv == 3)
                                    <a target="_blank" ng-if="item.print_count < 2" ng- href="{{url('admin/scanning/print/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print</a>
                                    <a target="_blank" ng-if="item.qr_print_count < 2" ng- href="{{url('admin/scanning/print-qr/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print QR</a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>            
        </div>
    </div>
@endsection
    