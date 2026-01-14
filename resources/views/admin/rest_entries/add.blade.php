<div class="mt-3"></div>
<div class="" style="padding:8px;border: 1px solid #e6e6e6;margin-top: 5px;">
    <form name="myForm" novalidate="novalidate" ng-submit="onSubmit(myForm.$valid)">
        <div class="row">
            <div class="col-md-3 form-group">
                <label>No of Hours</label>
                <input ng-change="calAmount()" type="number" min="1" ng-model="formData.no_of_hours" class="form-control" required />
            </div>
            <div class="col-md-3 form-group">
                <label>No of People</label>
                <input ng-change="calAmount()" type="number" min="1" ng-model="formData.no_of_people" class="form-control" required />
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Pay Type</label><br>
                    <label><input tabindex="-1" type="radio" ng-model="formData.pay_type" ng-value="1" required>&nbsp;Cash</label>&nbsp;&nbsp;
                    <label><input type="radio" ng-model="formData.pay_type" ng-value="2" required>&nbsp;UPI</label>
                </div>
            </div>
            <div class="col-md-2 form-group">
                <label>Paid Amount</label>
                <input type="text" ng-model="formData.paid_amount" class="form-control" readonly />
            </div>     
        </div>
        <div class="pt-4">
            <button type="submit" class="btn btn-primary" ng-disabled="processing">
                <span ng-if="!processing">Submit</span>
                <span ng-if="processing">Loading...</span>
            </button> 
        </div>  

    </form>
</div>
