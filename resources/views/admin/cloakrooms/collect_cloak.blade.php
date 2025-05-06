@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="cloackPenltyCollectCtrl" ng-init="init();"> 
        <div class="card shadow mb-4 p-4">    
            <div class="filters" style="margin:24px 0;">
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label class="label-control">Slip ID</label>
                                    <input type="text" class="form-control" ng-model="filter.id" />
                                </div>                    
                                <div class="col-md-2 form-group">
                                    <label class="label-control">Bill Number</label>
                                    <input type="text" class="form-control" ng-model="filter.unique_id" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.name" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Mobile</label>
                                    <input type="text" class="form-control" ng-model="filter.mobile_no" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn  btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div>
                <h3>Today Cloakroom (@{{c_sum}})</h3>
                <table class="table table-bordered table-striped" >
                    <thead style="background-color: rgba(0,0,0,.075);">
                        <tr class="table-primary">
                            <th>S.no</th>
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Check In/Valid Upto</th>
                            <th>Bag</th>
                            <th>Pay</th>
                            <th>Amount</th>
                            <th>
                                CS
                            </th>
                            <th>
                                #
                            </th>
                        
                        </tr>
                    </thead>
                    <tbody ng-if="l_entries.length > 0" >
                        <tr ng-repeat="item in l_entries " ng-class="{'my_class': item.deleted == 1}">
                            <td>@{{ item.id }}</td>
                            <td>@{{ item.name }}</td>
                            <td>@{{ item.mobile_no }}</td>
                            <td>
                                <span>@{{ item.checkin_date_show }}</span> to
                                <span>@{{ item.checkout_date_show }}</span>
                            </td>
                            <td>@{{ item.no_of_bag }}</td>                            
                            <td>
                                <span ng-if="item.pay_type == 1">Cash</span>
                                <span ng-if="item.pay_type == 2">UPI</span>
                            </td>      
                            <td>@{{ item.paid_amount }}</td>
                            <td>
                                <span ng-if="item.checkout_status == 0">No</span>
                                <span ng-if="item.checkout_status == 1">Yes</span>
                            </td>
                            <td>
                                
                                <button type="button" class="btn btn-sm" ng-click="collectCloak(item);">
                                    Collect
                                </button>
                            </td>
                
                        </tr>
                    </tbody>
                </table>
                <div ng-if="l_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div> 
            <hr>
            <div>
                <h3>Today Penality (@{{penalty_sum}})</h3>
                <table class="table table-bordered table-striped" >
                    <thead style="background-color: rgba(0,0,0,.075);">
                        <tr class="table-primary">
                            <th>Cloakroom Id</th>
                            <th>Amount</th>
                            <th>
                                #
                            </th>
                        </tr>
                    </thead>
                    <tbody ng-if="penlty_list.length > 0" >
                        <tr ng-repeat="item in penlty_list">
                            <td>@{{ item.cloakroom_id }}</td>
                            <td>@{{ item.paid_amount }}</td>
                            <td>
                                <button type="button" class="btn btn-sm" ng-click="onPSubmit(item);">
                                    Collect
                                </button>
                            </td>
                
                        </tr>
                    </tbody>
                </table>
                <div ng-if="penlty_list.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>     
        </div>
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="modal-title" id="exampleModalLongTitle">Collect Amount</h5>
                            </div>
                            <div class="col-md-6" style="text-align:right;">
                                <button type="button" class="close" ng-click="hideModal();" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-body">
                        <form name="myForm1" novalidate="novalidate" ng-submit="onSubmit(myForm1.$valid)">

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Total Bag</label>
                                    <input type="number" disabled ng-model="formData.total_bag" class="form-control" required />
                                </div>
                                
                                <div class="col-md-4 form-group">
                                    <label>Bag To Be Update</label>
                                    <input type="number" min="1" ng-model="formData.no_of_bag" class="form-control" required />
                                </div>    

                            </div>
                            
                            <div class="pt-4">
                                <button type="submit" class="btn btn-primary" ng-disabled="loading">
                                    <span ng-if="!loading">Submit</span>
                                    <span ng-if="loading">Loading...</span>
                                </button> 
                            </div>  
                            
                       </form>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
@endsection