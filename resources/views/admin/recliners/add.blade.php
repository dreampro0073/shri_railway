<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Recliner Add</h5>
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                        <button type="button" class="close" ng-click="hideModal();" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                
            </div>
            <div class="modal-body">
                <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)">

                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>Name</label>
                            <input type="text" ng-model="formData.name" class="form-control" required />
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Mobile No.</label>
                            <input type="number" ng-model="formData.mobile_no" class="form-control" required />
                        </div>
                        <div class="col-md-4 form-group">
                            <label>PNR/UID</label>
                            <input type="text" ng-model="formData.pnr_uid" class="form-control" ng-disabled="formData.id > 0" />
                        </div>   
                    </div>
                   
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Hour Occ</label>
                            <select ng-model="formData.hours_occ" class="form-select" ng-change="changeAmount()" ng-disabled="checkout_process" required >
                                <option value="">--select--</option>
                                <option ng-disabled="entry_id > 0 && old_hr > item.value" ng-repeat="item in hours" ng-value=@{{item.value}}>@{{ item.label}}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pay Type</label><br>
                                <label><input tabindex="-1" type="radio" ng-model="formData.pay_type" ng-value="1" required>&nbsp;Cash</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" ng-model="formData.pay_type" ng-value="2" required>&nbsp;UPI</label>
                            </div>
                        </div>

                       
                        <div class="col-md-4 form-group" ng-if="formData.id > 0">
                            <label>Check In</label>
                           
                            <input type="text" class="form-control" ng-model="formData.check_in" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 form-group" ng-if="entry_id == 0">
                            
                            <label>Available Recliners</label>
                            <br>
                            <span ng-repeat="item in avail_recliners">
                               <label> <input type="checkbox" ng-click="insRec(item.id)">&nbsp;@{{item.sl_no}}</label> &nbsp;&nbsp;
                            </span>
                            
                        </div>
                        <div class="col-md-12 form-group" ng-if="entry_id != 0">
                            <label>Recliners</label>
                            <input type="text" ng-model="formData.show_e_ids" class="form-control" required readonly />

                        </div>
                    </div>
                    <div class="row">
                        
                        

                        <div class="col-md-4 form-group" ng-if="formData.id > 0">
                            <label>Checkout</label>
                            <input type="text" class="form-control" ng-model="formData.check_out" readonly>
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Valid Upto</label>
                            <input type="text" class="form-control" ng-model="formData.show_valid_up" readonly>
                        </div>

                        <div class="col-md-3 form-group">
                            <label ng-if="entry_id !=0">Total Amount</label>
                            <label ng-if="entry_id ==0">Paid Amount</label>
                            <input type="number" ng-model="formData.total_amount" class="form-control" readonly />
                        </div>    

                        <div ng-if="entry_id !=0" class="col-md-3 form-group">
                            <label>Paid Amount</label>
                            <input type="number" ng-model="formData.paid_amount" class="form-control" readonly />
                        </div>                        
                        <div ng-if="entry_id !=0" class="col-md-3 form-group">
                            <label style="color: red;">Balance Amount</label>
                            <input type="number" ng-model="formData.balance_amount" class="form-control" style="color: red;" readonly />
                        </div>
                        <div class="col-md-12 form-group">
                            <label>Remarks</label>
                            <textarea ng-model="formData.remarks" class="form-control"></textarea>
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
