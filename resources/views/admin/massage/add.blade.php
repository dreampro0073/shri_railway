<div class="modal fade" id="massageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add</h5>
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
                            <input type="text" class="form-control" ng-model="formData.name" required>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <label>Pay Type</label>
                            <select ng-model="formData.pay_type" class="form-control" required >
                                <option value="">--select--</option>
                                <option ng-repeat="item in pay_types" ng-value="@{{item.value}}">@{{ item.label}}</option>
                            </select>
                            
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Time Period</label>
                            <select ng-model="formData.time_period" class="form-control" ng-change="changeTime()" required convert-to-number>
                                <option value="">--select--</option>
                                <option value="10">10 Minutes</option>
                                <option value="20">20 Minutes</option>
                            </select>
                        </div>
                        
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label>No of Person</label>
                            <select min="0" ng-model="formData.no_of_person" class="form-control" ng-change="changeTime()" required convert-to-number>
                                <?php for ($i=1; $i <= 10; $i++) { ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                <?php } ?>
                                
                            </select>
                        </div>
                        
                        <div class="col-md-4 form-group">
                            <label>Paid Amount</label>
                            <input type="number" ng-model="formData.paid_amount" class="form-control" readonly />
                        </div> 
                         <div class="col-md-4 form-group">
                            <label>Remarks</label>
                            <input type="text"  ng-model="formData.remarks" class="form-control">
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
