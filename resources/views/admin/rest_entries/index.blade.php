<?php 
    $client_ids = Session::get('client_ids');
?>
@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="restCtrl" ng-init="init();"> 
        <div class="card shadow mb-4 p-4">  
            @include("admin.rest_entries.add")
        </div>
        <div class="card shadow mb-4 p-4 mt-3">    
        
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">
                    <table class="table table-bordered table-striped" >
                        <thead >
                            <tr >
                                <th>S.no</th>
                                <th>No of Hour</th>
                                <th>No of People</th>
                                <th>Check In</th>
                                <th>Valid Till</th>
                                <th>Pay Type</th>
                                <th>Paid Amount</th>
                                
                            </tr>
                        </thead>
                        <tbody ng-if="entries.length > 0">
                            <tr ng-repeat="item in entries">
                                <td>@{{ $index+1 }}</td>
                                <td>@{{ item.no_of_hours }}</td>
                                <td>@{{ item.no_of_people }}</td>
                                <td>@{{ item.show_date_time }}</td>
                                <td>@{{ item.show_valid }}</td>

                                <td>
                                   @{{ item.show_pay_type }}
                                </td>
                             
                                <td>@{{ item.paid_amount }}</td>
                                
                            </tr>
                        </tbody>
                    </table>
                    <div ng-if="entries.length == 0" class="alert alert-danger">Data Not Found!</div>
                </div>
            </div>            
        </div>
    </div>
@endsection
    