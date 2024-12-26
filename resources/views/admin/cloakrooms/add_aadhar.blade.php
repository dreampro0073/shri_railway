<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Cloakroom</h5>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Id No.</label>
                                <!-- <input type="number" pattern="\d{12}" title="Aadhar number must be exactly 12 digits" ng-model="formData.aadhar_no" class="form-control" ng-disabled="aadhar_fetch" required /> -->
                                <input type="text" ng-model="formData.aadhar_no" class="form-control" ng-disabled="aadhar_fetch" required />
                            </div>

                            <button  ng-if="!aadhar_fetch" type="button" ng-click="fetchAadhar()" class="btn btn-primary">Fetch</button>

                            <div class="row" ng-if="aadhar_flag">
                                <div class="col-md-6 form-group">
                                    <label>Fornt ID</label>
                                    <br>
                                    <button type="button" ng-show="formData.aadhar_front == '' || formData.aadhar_front == null " class="btn btn-primary btn-sm" ngf-select="uploadFile($file,'aadhar_front',formData)">Select File</button>

                                    <div ng-show="formData.aadhar_front != '' && formData.aadhar_front != null ">
                                        <a ng-href="@{{formData.aadhar_front_url}}" target="_blank">
                                            <img ng-src="@{{formData.aadhar_front_url}}" style="width: 150px;">
                                        </a>
                                        <a  ng-if="newAadharFlag" class="btn mt-3 btn-sm btn-danger" type="button" ng-click="removeFile(formData,'aadhar_front')" >Delete</a>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Back Id</label>
                                    <br>
                                    <button type="button" ng-show="formData.aadhar_back == '' || formData.aadhar_back == null " class="btn btn-primary btn-sm" ngf-select="uploadFile($file,'aadhar_back',formData)">Select File</button>

                                    <div ng-show="formData.aadhar_back != '' && formData.aadhar_back != null ">
                                        <a ng-href="@{{formData.aadhar_back_url}}" target="_blank">
                                            <img ng-src="@{{formData.aadhar_back_url}}" style="width: 150px;">
                                        </a>
                                        <a  ng-if="newAadharFlag" class="btn mt-3 btn-sm btn-danger" type="button" ng-click="removeFile(formData,'aadhar_back')" >Delete</a>
                                    </div>
                                </div>         
                            </div>
                        </div>
                        <div class="col-md-6" ng-if="aadhar_details.upload_status == 0" style="text-align: center;"> 
                            <label>Upload By Other Devices</label>
                            <br>
                            <img src="https://quickchart.io/chart?cht=qr&chs=150x150&chl={{url('/aadhar/upload-by-mobile')}}/@{{aadhar_details.id}}" style="width:200px;height: 200px;">
                            <br>
                            <br>
                            <button type="button" ng-click="fetchAadhar()" class="btn btn-primary">Done</button>
                        </div>

                    </div>
                   
                    
                    <div>
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
                                <label>No Of Bag</label>
                                <input type="number" min="1" ng-model="formData.no_of_bag" class="form-control" required ng-keyup="changeAmount()" />
                            </div>    

                        </div>
                        <div class="row">
                            <div class="col-md-3 form-group" ng-if="formData.id > 0">
                                <label>Check In</label>
                               
                                <input type="text" class="form-control" ng-model="formData.check_in" readonly>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>PNR/UID</label>
                                <input type="number" ng-model="formData.pnr_uid" class="form-control" />
                            </div>                        
                            <div class="col-md-3 form-group">
                                <label>Time Duration</label>
                                <select ng-model="formData.no_of_day" class="form-control" ng-change="changeAmount()" required >
                                    <option value="">--select--</option>
                                    <option ng-repeat="item in days" value="@{{item.value}}">@{{ item.label}}</option>
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Pay Type</label>
                                <select ng-model="formData.pay_type" class="form-control" required  convert-to-number>
                                    <option value="">--select--</option>
                                    <option ng-repeat="item in pay_types" value="@{{item.value}}">@{{ item.label}}</option>
                                </select>
                            </div>
                            
                            
                            <div class="col-md-3 form-group">
                                <label>Paid Amount</label>
                                <input type="number" ng-model="formData.paid_amount" class="form-control" readonly />
                            </div>                      
                            <div ng-if="entry_id !=0" class="col-md-3 form-group">
                                <label>Balance Amount</label>
                                <input type="number" ng-model="formData.balance_amount" class="form-control" readonly />
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
                    </div> 
                    
                </form>
            </div>
           
        </div>
    </div>
</div>


<div class="modal fade" id="checkoutCloakModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Checkout Locker</h5>
                    </div>
                    <div class="col-md-6" style="text-align:right;">
                        <button type="button" class="close" ng-click="hideModal();" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                
            </div>
            <div class="modal-body">
                <form name="myForm" novalidate="novalidate" ng-submit="onCheckOut(myForm.$valid)">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Name</label>
                            <input type="text" ng-model="formData.name" class="form-control" readonly />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Mobile No.</label>
                            <input type="number" ng-model="formData.mobile_no" class="form-control" readonly />

                        </div>
                         <div class="col-md-3 form-group">
                            <label>PNR/UID</label>
                            <input type="number" ng-model="formData.pnr_uid" class="form-control" readonly />
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Train No.</label>
                            <input type="number" ng-model="formData.train_no" class="form-control" readonly />
                        </div>
                    
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label>Check In Date</label>
                           
                            <input type="text" class="form-control" ng-model="formData.checkin_date" readonly />
                        </div>
                         <div class="col-md-3 form-group">
                            <label>Valid Upto</label>
                           
                            <input type="text" class="form-control" ng-model="formData.checkout_date" readonly />
                        </div>
                       
                        
                        
                        <div class="col-md-3 form-group">
                            <label>No Of Days</label>
                            <input type="text"  ng-model="formData.no_of_day" class="form-control" readonly>
                           
                        </div>
                        <div class="col-md-3 form-group" style="color:red;">
                            <label>Late Days</label>
                            <input type="text" ng-model="formData.day" class="form-control" style="color:red;" readonly>
                        </div>                       
                        <div class="col-md-3 form-group">
                            <label>Total Days</label>
                            <input type="text" class="form-control" ng-model="formData.final_days" readonly>
                        </div>
                        <div class="col-md-3 form-group" ng-if="entry_id != 0">

                            <label>No Of Bag</label>
                            <input type="text" ng-model="formData.no_of_bag" class="form-control"  readonly />

                        </div>
                    </div>
                  
                    <div class="row">  
                        
                        
                        <div class="col-md-3 form-group">
                            <label>Pay Type</label>
                            <select ng-model="formData.pay_type" class="form-control"   convert-to-number>
                                <option value="">--select--</option>
                                <option ng-repeat="item in pay_types" value="@{{item.value}}">@{{ item.label}}</option>
                            </select>
                        </div>
                        <div class="col-md-3 form-group">
                            <label>Total Amount</label>
                            <input type="number" ng-model="formData.total_balance" class="form-control" readonly />
                        </div> 

                        <div class="col-md-3 form-group" >
                            <label>Paid Amount</label>
                            <input type="number" ng-model="formData.paid_amount" class="form-control" readonly />
                        </div> 
                        <div class="col-md-3 form-group" style="color:red;">
                            <label>Balance Amount</label>
                            <input type="number" ng-model="formData.balance" style="color:red;" class="form-control" readonly />
                        </div>                        
                        
                        
                        <div class="col-md-12 form-group">
                            <label>Remarks</label>
                            <textarea ng-model="formData.remarks" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="submit" class="btn btn-primary" ng-disabled="loading">
                            <span ng-if="!loading">Collect</span>
                            <span ng-if="loading">Loading...</span>
                        </button> 
                    </div>  
                    
               </form>
            </div>
           
        </div>
    </div>
</div>

