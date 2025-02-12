<style>
    span{
        display: block;
    }
</style>

<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">
                            <span ng-if="type == 1">Pods</span>
                            <span ng-if="type == 2">Sigle Suit Babin</span>
                            <span ng-if="type == 3">Double Bed</span>
                        </h5>
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                        <button type="button" class="close" ng-click="hideModal();" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                
            </div>
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label>Name</label>
                        <span>@{{l_entry.name}}</span>
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Mobile No.</label>
                        <span>@{{l_entry.mobile_no}}</span>

                    </div>
                    <div class="col-md-3 form-group">
                        <label>PNR/UID</label>
                        <span>@{{l_entry.pnr_uid}}</span>

                    </div>
                    <div class="col-md-3 form-group">
                        <label>Hours Occ</label>
                        <span>@{{l_entry.hours_occ+l_entry.late_hr}}</span>
                        
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Check In</label>
                        <span>@{{l_entry.add_date}}</span>
                        
                    </div>
                    <div class="col-md-3 form-group">
                        <label>Check Out</label>   
                        <span>@{{l_entry.checkout_date}}</span>
                        
                    </div>
                    <div class="col-md-3 form-group" ng-if="entry_id != 0">
                        <label>
                            <span ng-if="type == 1">Pods</span>
                            <span ng-if="type == 2">Sigle Suit Babin</span>
                            <span ng-if="type == 3">Double Bed</span>
                        </label>
                        <span>@{{l_entry.show_e_ids}}</span>


                    </div>
                    <div class="col-md-3 form-group">
                        <label>Pay Type</label>
                        <span ng-if="l_entry.pay_type == 1">Cash</span>
                        <span ng-if="l_entry.pay_type == 2">UPI</span>
                    </div>
                    <div class="col-md-3 form-group" >
                        <label>Paid Amount</label>
                        <span>@{{l_entry.sh_paid_amount}}</span>
                    </div>
                    <div class="col-md-3 form-group" >
                        <label>Remarks</label>
                        <span>@{{l_entry.remarks}}</span>
                    </div>
                    
                </div>
                <table ng-if="l_entry.e_entries.length > 0" class="table">
                    <thead>
                        <tr>
                            <th>Sn</th>
                            <th>Paid Amount</th>
                            <th>Type</th>
                            <th>#</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-if="item.paid_amount > 0" ng-repeat="(key,item) in l_entry.e_entries">
                            <td>@{{$key+1}}</td>
                            <td>@{{item.paid_amount}}</td>
                            <td>
                                <span ng-if="item.is_checkout == 0">Edit</span>
                                <span ng-if="item.is_checkout == 1">Checkout</span>
                            </td>
                            <td>
                                <a ng-if="item.is_checkout == 1" class="btn btn-sm btn-danger" href="javascript:;" ng-click="deleteEntry(l_entry.id,item.id)">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
           
        </div>
    </div>
</div>



