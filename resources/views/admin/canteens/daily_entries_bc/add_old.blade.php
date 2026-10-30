<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">Add Item</h5>
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
                            <label>Mobile</label>
                            <input type="number" ng-model="formData.mobile" class="form-control" />
                        </div>
                        <div class="col-md-4 form-group">
                            <label>Pay Type</label><br>
                            <label><input type="radio" ng-model="formData.pay_type" value="1">&nbsp;Cash</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" ng-model="formData.pay_type" value="2">&nbsp;UPI</label>
                        </div>   
                    </div>

                    <div style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
                        <div class="row">
                            <div class="col-md-7 form-group">
                                <label>Select Item</label>
                                <!-- <selectize placeholder='Select a item' config="selectConfig" options="canteen_items" ng-model="product.canteen_item_id" required ng-change="handleChange()"></selectize> -->
                                <input autofocus type="text" id="productName" ng-model="productName" class="form-control" ng-keypress="handleKeyPress($event)" />
                            </div>
                            <div class="col-md-3 form-group">
                                <label>No of Item</label>
                                <input type="number" min="1" ng-model="product.quantity" class="form-control" />
                                
                            </div>
                            <div class="col-md-2 form-group" style="padding-top: 27px;">
                                
                                <button type="button" class="btn btn-sm btn-primary" ng-click="onAddProdcut()">
                                    Add
                                </button>
                                
                            </div>
                        </div>
                    </div>

                    <div style="margin-bottom: 10px;padding-bottom: 10px;border-bottom: 1px solid #f6f6f6;">
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
                               <td>#</td>
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
                                   <!-- <a class="btn btn-sm btn-warning" href="javascript:;" ng-click="editItem($index)">Edit Item</a> -->
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
                       </table>
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
