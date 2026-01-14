<div class="card shadow mb-4 p-4">
    <form name="myForm">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Select Item</label>
                    <input autofocus type="text" id="productname" ng-model="productname" class="form-control" ng-keypress="handleKeyPress($event)" />
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Name</label>
                            <input tabindex="-1" type="text" ng-model="formData.name" class="form-control" required />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Mobile</label>
                            <input  tabindex="-1" type="number" ng-model="formData.mobile" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Pay Type</label><br>
                            <label><input tabindex="-1" type="radio" ng-model="formData.pay_type" value="1">&nbsp;Cash</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" ng-model="formData.pay_type" value="2">&nbsp;UPI</label>
                        </div>
                    </div>
                </div>
                
                
                   
            </div>
            <div class="col-md-6">
                <label class="label-control">Items</label>
                <table class="table table-bordered table-striped">
                    <tr>
                        <td>
                           Sn
                        </td>
                        <td>
                           Item Name
                        </td>
                        <td>
                           Pirice
                        </td>
                        <td>
                           Quantity
                        </td>
                      
                        <td>
                           Amount
                        </td>
                        <td>
                            #
                        </td>
                        
                    </tr>
                    <tr ng-repeat="item in products track by $index">
                        <td>
                           @{{$index+1}}
                        </td>
                        <td>
                           @{{item.item_name}}
                        </td>
                        <td>
                           @{{item.price}}
                        </td>
                        <td>
                           @{{item.quantity}}
                        </td>
                        
                        <td>
                           @{{item.paid_amount}}
                        </td>
                        <td>
                            <a href="javascript:;" ng-click="removeItem($index)" class="btn btn-sm btn-danger">
                                <i class="fa fa-close" ></i>
                            </a>
                        </td>
                        
                    </tr>
                    <tr ng-if="products.length > 0">
                        <td colspan="3">
                           <b>Total</b>
                        </td>
                        <td colspan="1">
                           <b>@{{formData.total_item}}</b>
                        </td>

                        <td colspan="2">
                           <b>@{{formData.total_amount}}</b>
                        </td>
                        
                    </tr>
                    <tr ng-if="products.length == 0">
                        <td colspan="6">
                           <div class="alert alert-danger">No Item Add in the List</div>
                        </td>
                       
                    </tr>
               </table>
            </div>
        </div>
        
        <div class="pt-4">
            <button type="button" ng-click="onSubmit()" class="btn btn-primary" ng-disabled="loading">
                <span ng-if="!loading">Submit</span>
                <span ng-if="loading">Loading...</span>
            </button> 
        </div>  
        
    </form>
</div>

<div style="margin-bottom:16px;"></div>