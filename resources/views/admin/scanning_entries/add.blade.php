<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Sitting Add</h5>
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
                            <label>No of Item</label>
                            <input type="text" required ng-model="formData.no_of_item" class="form-control" ng-disabled="formData.id > 0" ng-change="calAmount()" />
                        </div> 
                        <div ng-if="entry_id !=0" class="col-md-4 form-group">
                            <label>Paid Amount</label>
                            <input type="number" ng-model="formData.paid_amount" class="form-control" readonly />
                        </div>     
                    </div>
                   
                   
                    <div class="row">
                        
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Pay Type</label><br>
                                <label><input tabindex="-1" type="radio" ng-model="formData.pay_type" ng-value="1" required>&nbsp;Cash</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <label><input type="radio" ng-model="formData.pay_type" ng-value="2" required>&nbsp;UPI</label>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Item Type</label><br>
                                <label>
                                    <input ng-click="calAmount()" tabindex="-1" type="radio" ng-model="formData.item_type_id" ng-value="1" required>&nbsp;Non Leased Items
                                </label>&nbsp;&nbsp;&nbsp;
                                <label>
                                    <input ng-click="calAmount()" type="radio" ng-model="formData.item_type_id" ng-value="2" required>&nbsp;Leased Items
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Incoming Type</label><br>
                                <label><input ng-click="calAmount()" tabindex="-1" type="radio" ng-model="formData.incoming_type_id" ng-value="1" required>&nbsp;Outword</label>&nbsp;&nbsp;&nbsp;
                                <label><input ng-click="calAmount()" type="radio" ng-model="formData.incoming_type_id" ng-value="2" required>&nbsp;Leased Inword</label>
                            </div>
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
