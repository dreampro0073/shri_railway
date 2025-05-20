<?php 
    $client_ids = Session::get('client_ids');
?>
@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="restCtrl" ng-init="init();"> 
        <div class="card shadow mb-4 p-4">  
            @include("admin.rest_entries.add")
        </div>
        <div class="card shadow mb-4 p-4">    
            <!-- <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row">
                         <div class="col-md-2 form-group">
                            <label class="label-control">Slip ID</label>
                            <input type="text" class="form-control" ng-model="filter.slip_id" />
                        </div>   
                          
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                        </div>
                    </div>
                </form>
            </div> -->
           
            <hr>
            <div>
                <table class="table table-bordered table-striped" >
                    <thead style="background-color: rgba(0,0,0,.075);">
                        <tr class="table-primary">
                            <th>S.no</th>
                            <th>No of Hour</th>
                            <th>Date/Time</th>
                            <th>Pay Type</th>
                            <th>Paid Amount</th>
                            
	                        <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="entries.length > 0">
                        <tr ng-repeat="item in entries">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.no_of_hours }}</td>
                            <td>@{{ item.show_date_time }}</td>

                            <td>
                               @{{ item.show_pay_type }}
                            </td>
                         
                            <td>@{{ item.paid_amount }}</td>
                            
                            <td>
                                #
                               <!--  @if(Auth::user()->priv == 2)
                                    <a target="_blank" ng- href="{{url('admin/scanning/print/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print</a>
                                    <a target="_blank" ng- href="{{url('admin/scanning/print-qr/')}}/@{{item.barcodevalue}}" class="btn btn-sm btn-warning">Print QR</a>
                                @endif -->
                               
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>            
        </div>
    </div>
@endsection
    