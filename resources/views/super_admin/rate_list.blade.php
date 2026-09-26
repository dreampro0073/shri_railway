<div class="row" ng-if="service.services_id == 1">
     <div class="col-md-6 form-group">
          <label>Adult First Rate</label>
          <input type="text" ng-model="service.rate_list.adult_rate" class="form-control">
     </div> 
     <div class="col-md-6 form-group">
          <label>Adult Second Rate</label>
          <input type="text" ng-model="service.rate_list.adult_rate_sec" class="form-control">
     </div>
     <div class="col-md-6 form-group">
          <label>Child First Rate</label>
          <input type="text" ng-model="service.rate_list.child_rate" class="form-control">
     </div> 
     <div class="col-md-6 form-group">
          <label>Child Second Rate</label>
          <input type="text" ng-model="service.rate_list.child_rate_sec" class="form-control">
     </div> 
</div>

<div class="row" ng-if="service.services_id == 2 || service.services_id == 4 || service.services_id == 5 || service.services_id == 7">
     <div class="col-md-4 form-group">
          <label>First Rate</label>
          <input type="text" ng-model="service.rate_list.first_rate" class="form-control">
     </div> 
     <div class="col-md-4 form-group">
          <label>Second Rate</label>
          <input type="text" ng-model="service.rate_list.second_rate" class="form-control">
     </div>
     <div class="col-md-4 form-group">
          <label>Rate Type</label>
          <select ng-model="service.rate_list.type" class="form-select" convert-to-number>
               <option value="1">Fixed</option>
               <option value="2">Flexible</option>
               <option value="3">Open</option>
          </select>
     </div>
</div>

<div class="row" ng-if="service.services_id == 9">
     <div class="col-md-12">
          <table class="table table-striped">
               <tbody>
                    <tr>
                         <td>Item Type</td>
                         <td>Incoming Type</td>
                         <td>Rate</td>
                    </tr>
                    <tr>
                         <td rowspan="2">Non Leased Items</td>
                         <td>Outword</td>
                         <td>
                              <input type="text" ng-model="service.rate_list.outword1_rate" class="form-control">
                         </td>
                    </tr>                
                    <tr>
                         <td>Inword</td>
                         <td>
                              <input type="text" ng-model="service.rate_list.inword1_rate" class="form-control">
                         </td>
                    </tr>               

                    <tr>
                         <td rowspan="2">Leased Items</td>
                         <td>Outword</td>
                         <td>
                              <input type="text" ng-model="service.rate_list.outword2_rate" class="form-control">
                         </td>
                    </tr>

                    <tr>
                         <td>Inword</td>
                         <td>
                              <input type="text" ng-model="service.rate_list.inword2_rate" class="form-control">
                         </td>
                    </tr>
               </tbody>
          </table>
     </div>
</div>