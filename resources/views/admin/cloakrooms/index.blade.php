@extends('admin.layout')

@section('main')
    <div class="main" ng-controller="cloackCtrl" ng-init="type = {{$type}};init();"> 
        @if(Auth::user()->client_id == 4)
            @include('admin.cloakrooms.add_aadhar')
        @else
            @include('admin.cloakrooms.add')
        @endif
        <div class="card shadow mb-4 p-4">    
            <div class="filters" style="margin:24px 0;">
                <div class="form-group">
                    <input autofocus type="text" id="productName" ng-model="productName" ng-keypress="handleKeyPress($event)"
                   style="padding: 10px; border-radius: 4px; border: 1px solid #ccc; margin-bottom: 10px; width: 100%;"
                   placeholder="Barcodevalue">
                </div>
                <form name="filterForm"  novalidate>
                    <div class="row" style="font-size: 14px">

                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-2 form-group">
                                    <label class="label-control">Slip ID</label>
                                    <input type="text" class="form-control" ng-model="filter.slip_id" />
                                </div>                    
                                <!-- <div class="col-md-2 form-group">
                                    <label class="label-control">Bill Number</label>
                                    <input type="text" class="form-control" ng-model="filter.unique_id" />
                                </div>  -->                   
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Name</label>
                                    <input type="text" class="form-control" ng-model="filter.name" />
                                </div>                    
                                <div class="col-md-3 form-group">
                                    <label class="label-control">Mobile</label>
                                    <input type="text" class="form-control" ng-model="filter.mobile_no" />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label class="label-control">PNR</label>
                                    <input type="text" class="form-control" ng-model="filter.pnr_uid" />
                                </div>
                               
                            </div>
                        </div>
                        <div class="col-md-3 text-right" style="margin-top: 25px;" class="mb-2">
                            <button type="button" ng-click="init()" class="btn  btn-primary" style="width: 70px;">Search</button>
                            <button type="button" ng-click="filterClear()" class="btn  btn-warning" style="width: 70px;">Clear</button>
                            @if($type == 0 && Auth::user()->priv !=4)
                            <button type="button" ng-click="add()" class="btn  btn-primary" style="width: 70px;">Add</button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                   
                </div>

                <div ng-if="type == 1" class="col-md-6 text-right">
                    <ul class="pagination">
                        <li ng-if="filter.page_no > 1" ng-click="getData(-1)"><<</li>
                        <li class="p">@{{filter.page_no}}</li>
                        <li ng-if="l_entries.length == 100" ng-click="getData(1)">>></li>    
                    </ul>
                </div>
            </div>
            <div>
                <table class="table table-bordered table-striped" >
                    <thead style="background-color: rgba(0,0,0,.075);">
                        <tr class="table-primary">
                            <th>S.no</th>
                            <th>Slip Id</th>
                            
                            <th>Name</th>
                            <th>Mobile No</th>
                            <th>Check In/Valid Upto</th>
                            <th>No Of Bag</th>
                            <th>PNR</th>
                            <th>Pay Type</th>
                            <th>Total Amount</th>
                            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                            <th>
                                Checkout Status
                            </th>
                            @endif
                            <th>Aadhar</th>
                            <th>#</th>
                        </tr>
                    </thead>
                    <tbody ng-if="l_entries.length > 0" >
                        <tr ng-repeat="item in l_entries " ng-class="{'my_class': item.deleted == 1}">
                            <td>@{{ $index+1 }}</td>
                            <td>@{{ item.slip_id }}</td>
                           
                            <td>@{{ item.name }}</td>
                            <td>@{{ item.mobile_no }}</td>
                            <td>
                                <span>@{{ item.checkin_date_show }}</span> to
                                <span>@{{ item.checkout_date_show }}</span>
                            </td>
                            <td>@{{ item.no_of_bag }}</td>
                            <td>@{{ item.pnr_uid }}</td>
                            
                            <td>
                                <span ng-if="item.pay_type == 1">Cash</span>
                                <span ng-if="item.pay_type == 2">UPI</span>
                            </td>  
                            
                            <td>@{{ item.sh_paid_amount }}</td>
                            @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                            <td>
                                <span ng-if="item.checkout_status == 0">No</span>
                                <span ng-if="item.checkout_status == 1">Yes</span>
                            </td>
                            @endif
                            <td>
                                @{{item.aadhar_no}}<br>
                                <a ng-if="item.aadhar_front" href="{{url('/')}}/@{{item.aadhar_front}}" class="btn mt-2 btn-primary btn-sm" target="_blank">Front</a>
                                <a ng-if="item.aadhar_back" href="{{url('/')}}/@{{item.aadhar_back}}" class="btn mt-2 btn-primary btn-sm" target="_blank">Back</a>
                                
                            </td>  

                            <td>
                                
                                <!-- <a ng-if="type == 0" href="javascript:;" ng-click="checkoutCloak(item.id)" class="btn btn-danger btn-sm">Checkout</a>  -->

                                @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                                   
                                
                                    <!-- <div style="margin-top:4px;"></div>
                                    <a ng-if="item.barcodevalue" href="{{url('/admin/cloak-rooms/print-unq/1')}}/@{{item.barcodevalue}}" class="btn btn-success btn-sm" target="_blank">Print Slip</a> -->
                                @endif
                                
                                <div></div>
                                <a  href="{{url('/admin/cloak-rooms/print-unq/2')}}/@{{item.barcodevalue}}" class="btn mt-2 btn-success btn-sm" target="_blank">Print</a>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div ng-if="l_entries.length == 0" class="alert alert-danger">Data Not Found!</div>
            </div>     
        </div>
    </div>
@endsection

@section('footer_scripts')
   <!--  <script src=
        "https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js">
    </script>  -->
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <script>
    const qrCodeText = "https://example.com"; // Text or URL to encode
    const qrcode = new QRCode("qrcode", {
      text: qrCodeText,
      width: 128, // Width of the QR code
      height: 128, // Height of the QR code
      colorDark: "#000000", // Dark color
      colorLight: "#ffffff", // Light color
      correctLevel: QRCode.CorrectLevel.H, // Error correction level
    });
  </script>

@endsection
    
    