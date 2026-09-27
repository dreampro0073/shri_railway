<style>
    .room-select-title {
        display: block;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .room-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .room-item {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 2px 10px;
        margin: 0;
        border: 1px solid #e2e5e8;
        border-radius: 6px;
        background: #f8f9fa;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .room-item:hover {
        border-color: #b8d8c0;
        background: #f2faf4;
    }

    .room-check {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .custom-radio {
        width: 15px;
        height: 15px;
        border: 1.5px solid #adb5bd;
        border-radius: 50%;
        background: #fff;
        position: relative;
        flex-shrink: 0;
    }

    .room-name {
        font-size: 13px;
        font-weight: 500;
        color: #495057;
    }

    .room-check:checked + .custom-radio {
        border-color: #28a745;
        background: #28a745;
    }

    .room-check:checked + .custom-radio::after {
        content: "";
        position: absolute;
        width: 5px;
        height: 5px;
        background: #fff;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .room-check:checked ~ .room-name {
        color: #198754;
        font-weight: 600;
    }

    .room-item:has(.room-check:checked) {
        border-color: #a8d5b0;
        background: #edf8ef;
    }
</style>

<!-- =========================
     ADD / EDIT MODAL
========================= -->

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

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

                <form name="myForm1" novalidate="novalidate"
                    ng-submit="onSubmit(myForm1.$valid)">

                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label>Name</label>
                            <input type="text" ng-model="formData.name" class="form-control" required />
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Mobile No.</label>
                            <input type="number" ng-model="formData.mobile_no" class="form-control" required />
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3 form-group" ng-if="formData.id > 0">
                            <label>Check In</label>
                            <input type="text" class="form-control" ng-model="formData.check_in" readonly>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>PNR/UID</label>
                            <input type="text" ng-model="formData.pnr_uid" class="form-control" />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Hours</label>

                            <select ng-model="formData.hours_occ" class="form-select"
                                ng-change="changeAmount()" required convert-to-number>

                                <option value="">--select--</option>

                                <option ng-disabled="entry_id > 0 && old_hr > item.value"
                                    ng-repeat="item in hours" value="@{{item.value}}">
                                    @{{ item.label}}
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Pay Type</label>

                            <select ng-model="formData.pay_type" class="form-select"
                                required convert-to-number>

                                <option value="">--select--</option>

                                <option ng-repeat="item in pay_types" value="@{{item.value}}">
                                    @{{ item.label}}
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3 form-group" ng-if="formData.online_booking == 1 ">
                            <label>No of Room</label>

                            <input type="text" ng-model="formData.no_of_rooms"
                                class="form-control" readonly disabled>
                        </div>

                        <!-- AVAILABLE PODS -->

                        <div class="col-md-12 form-group" ng-if="type == 1">
                            <label class="room-select-title">Available PODS</label>

                            <div class="room-list">

                                <label class="room-item" ng-repeat="item in avail_pods">
                                    <input type="checkbox" ng-click="insPods(item.id)" class="room-check">
                                    <span class="custom-radio"></span>
                                    <span class="room-name">P-@{{item.e_no}}</span>
                                </label>

                            </div>
                        </div>

                        <!-- EXISTING POD IDs -->

                        <div class="col-md-3 form-group" ng-if="entry_id != 0 && type == 1">
                            <label>PODS</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />
                        </div>

                        <!-- AVAILABLE SINGLE CABINS -->

                        <div class="col-md-12 form-group" ng-if="entry_id == 0 && type == 2">
                            <label class="room-select-title">Available Single Suit Cabins</label>

                            <div class="room-list">

                                <label class="room-item" ng-repeat="item in avail_cabins">
                                    <input type="checkbox" ng-click="insCabins(item.id)" class="room-check">
                                    <span class="custom-radio"></span>
                                    <span class="room-name">C - @{{item.e_no}}</span>
                                </label>

                            </div>
                        </div>

                        <!-- EXISTING CABIN IDs -->

                        <div class="col-md-3 form-group" ng-if="entry_id != 0 && type == 2">
                            <label>Single Suit Cabins</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />
                        </div>

                        <!-- AVAILABLE DOUBLE BEDS -->

                        <div class="col-md-12 form-group" ng-if="entry_id == 0 && type == 3">
                            <label class="room-select-title">Available Double Beds</label>

                            <div class="room-list">

                                <label class="room-item" ng-repeat="item in avail_beds">
                                    <input type="checkbox" ng-click="insBeds(item.id)" class="room-check">
                                    <span class="custom-radio"></span>
                                    <span class="room-name">B-@{{item.e_no}}</span>
                                </label>

                            </div>
                        </div>

                        <!-- EXISTING BED IDs -->

                        <div class="col-md-3 form-group" ng-if="entry_id != 0 && type == 3">
                            <label>Double Beds</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />
                        </div>

                        <!-- AMOUNT -->

                        <div class="col-md-3 form-group">
                            <label>Total Amount</label>

                            <input type="number" ng-model="formData.total_amount"
                                class="form-control" readonly />
                        </div>

                        <!-- DISCOUNT -->

                        <div class="col-md-3 form-group"
                            ng-if="!otp_verified && entry_id == 0">

                            <label>Are you want to give discount</label>
                            <br>

                            <label>
                                <input ng-click="sendOtp()" type="radio"
                                    ng-model="formData.is_discount" ng-value="1">
                                Yes
                            </label>

                            <label style="margin-left:15px;">
                                <input type="radio" ng-click="resetOtp()"
                                    ng-model="formData.is_discount" ng-value="0">
                                No
                            </label>

                        </div>

                        <!-- VERIFY OTP -->

                        <div class="col-md-3 form-group"
                            ng-if="formData.is_discount == 1 && !otp_verified">

                            <label>Verify OTP</label>
                            <p>@{{otp}}</p>

                            <input class="form-control" type="number"
                                ng-model="formData.otp" ng-keyup="verifyOtp()">

                        </div>

                        <!-- DISCOUNT AMOUNT -->

                        <div ng-if="otp_verified || entry_id > 0" class="col-md-3 form-group">
                            <label>Discount Amount</label>

                            <input type="text" ng-model="formData.discount_amount"
                                ng-keyup="disAmount()" class="form-control"
                                ng-readonly="entry_id > 0" />
                        </div>

                        <!-- PAID -->

                        <div class="col-md-3 form-group">
                            <label>Paid Amount</label>

                            <input type="number" ng-model="formData.paid_amount"
                                class="form-control" readonly />
                        </div>

                        <!-- BALANCE -->

                        <div ng-if="entry_id !=0" class="col-md-3 form-group">
                            <label>Balance Amount</label>

                            <input type="number" ng-model="formData.balance_amount"
                                class="form-control" readonly />
                        </div>

                        <!-- REMARKS -->

                        <div class="col-md-12 form-group">
                            <label>Remarks</label>

                            <textarea ng-model="formData.remarks"
                                class="form-control"></textarea>
                        </div>

                    </div>

                    <div class="pt-4">
                        <button type="submit"
                            class="btn btn-primary-600 align-items-center justify-content-center gap-6 d-inline-flex"
                            ng-disabled="loading">

                            <span>
                                <span class="d-flex text-md">
                                    <i class="ri-add-large-line"></i>
                                </span>
                            </span>

                            Submit
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<!-- =========================
     CHECKOUT MODAL
========================= -->

<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

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
                        <button type="button" class="close"
                            ng-click="hideModal();" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>
                    </div>

                </div>

            </div>

            <div class="modal-body">

                <form name="myForm" novalidate="novalidate"
                    ng-submit="onCheckOut(myForm.$valid)">

                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label>Name</label>

                            <input type="text" ng-model="formData.name"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Mobile No.</label>

                            <input type="number" ng-model="formData.mobile_no"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>PNR/UID</label>

                            <input type="number" ng-model="formData.pnr_uid"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Hours Occ</label>

                            <input type="text" ng-model="formData.hours_occ"
                                class="form-control" readonly>
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label>Check In</label>

                            <input type="text" class="form-control"
                                ng-model="formData.check_in" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Check Out</label>

                            <input type="text" class="form-control"
                                ng-model="formData.check_out" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Hours Late</label>

                            <input type="text" ng-model="formData.hour"
                                class="form-control" readonly>
                        </div>

                        <div class="col-md-3 form-group" ng-if="entry_id != 0">

                            <label>
                                <span ng-if="type == 1">Pods</span>
                                <span ng-if="type == 2">Sigle Suit Babin</span>
                                <span ng-if="type == 3">Double Bed</span>
                            </label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" readonly />
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3 form-group">
                            <label>Pay Type</label>

                            <select ng-model="formData.pay_type"
                                class="form-control" convert-to-number>

                                <option value="">--select--</option>

                                <option ng-repeat="item in pay_types"
                                    value="@{{item.value}}">
                                    @{{ item.label}}
                                </option>

                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Total Amount</label>

                            <input type="number" ng-model="formData.total_balance"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Paid Amount</label>

                            <input type="number" ng-model="formData.paid_amount"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Balance Amount</label>

                            <input type="number" ng-model="formData.balance"
                                class="form-control" readonly />
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Collect Amount</label>

                            <input type="number" ng-model="formData.collect_amount"
                                class="form-control" min="0" />
                        </div>

                        <div class="col-md-8 form-group">
                            <label>Remarks</label>

                            <input ng-model="formData.remarks"
                                class="form-control" />
                        </div>

                    </div>

                    <div class="pt-4">

                        <button type="submit" class="btn btn-primary-600"
                            ng-disabled="loading">

                            <span ng-if="!loading">Collect</span>
                            <span ng-if="loading">Loading...</span>

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>


<!-- =========================
     CHECKIN MODAL
========================= -->

<div class="modal fade" id="checkinModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">

                <div class="row">

                    <div class="col-md-6">
                        <h5 class="modal-title" id="exampleModalLongTitle">

                            <span ng-if="formData.type == 1">Pods</span>
                            <span ng-if="formData.type == 2">Sigle Suit Babin</span>
                            <span ng-if="formData.type == 3">Double Bed</span>

                        </h5>
                    </div>

                    <div class="col-md-6" style="text-align:right;">
                        <button type="button" class="close"
                            ng-click="hideModal();" aria-label="Close">

                            <span aria-hidden="true">&times;</span>

                        </button>
                    </div>

                </div>

            </div>

            <div class="modal-body">

                <form name="myForm1" novalidate="novalidate"
                    ng-submit="onSubmit(myForm1.$valid)">

                    <div class="row">

                        <div class="col-md-4 form-group">
                            <label>Name</label>

                            <input type="text" ng-model="formData.name"
                                class="form-control" required />
                        </div>

                        <div class="col-md-4 form-group">
                            <label>Mobile No.</label>

                            <input type="number" ng-model="formData.mobile_no"
                                class="form-control" required />
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-md-3 form-group" ng-if="formData.id > 0">

                            <label>Check In</label>

                            <input type="text" class="form-control"
                                ng-model="formData.check_in" readonly>

                        </div>

                        <div class="col-md-3 form-group">
                            <label>PNR/UID</label>

                            <input type="text" ng-model="formData.pnr_uid"
                                class="form-control" />
                        </div>

                        <div class="col-md-3 form-group">

                            <label>Hours</label>

                            <select ng-model="formData.hours_occ"
                                class="form-select"
                                ng-change="changeAmount()"
                                required convert-to-number>

                                <option value="">--select--</option>

                                <option ng-disabled="entry_id > 0 && old_hr > item.value"
                                    ng-repeat="item in hours"
                                    value="@{{item.value}}">
                                    @{{ item.label}}
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3 form-group">

                            <label>Pay Type</label>

                            <select ng-model="formData.pay_type"
                                class="form-select"
                                required convert-to-number>

                                <option value="">--select--</option>

                                <option ng-repeat="item in pay_types"
                                    value="@{{item.value}}">
                                    @{{ item.label}}
                                </option>

                            </select>

                        </div>

                        <div class="col-md-3 form-group"
                            ng-if="formData.online_booking == 1 ">

                            <label>No of Room</label>

                            <input type="text" ng-model="formData.no_of_rooms"
                                class="form-control" readonly disabled>

                        </div>

                        <!-- EXISTING POD -->

                        <div class="col-md-3 form-group"
                            ng-if="entry_id != 0 && formData.type == 1">

                            <label>PODS</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />

                        </div>

                        <!-- AVAILABLE CABINS -->

                        <div class="col-md-12 form-group"
                            ng-if="entry_id == 0 && formData.type == 2">

                            <label class="room-select-title">
                                Available Single Suit Cabins
                            </label>

                            <div class="room-list">

                                <label class="room-item" ng-repeat="item in avail_cabins">
                                    <input type="checkbox" ng-click="insCabins(item.id)" class="room-check">
                                    <span class="custom-radio"></span>
                                    <span class="room-name">P-@{{item.e_no}}</span>
                                </label>

                            </div>

                        </div>

                        <div class="col-md-3 form-group"
                            ng-if="entry_id != 0 && formData.type == 2">

                            <label>Single Suit Cabins</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />

                        </div>

                        <!-- AVAILABLE DOUBLE BEDS -->

                        <div class="col-md-12 form-group"
                            ng-if="entry_id == 0 && formData.type == 3">

                            <label class="room-select-title">
                                Available Double Beds
                            </label>

                            <div class="room-list">

                                <label class="room-item" ng-repeat="item in avail_beds">
                                    <input type="checkbox" ng-click="insBeds(item.id)" class="room-check">
                                    <span class="custom-radio"></span>
                                    <span class="room-name">C-@{{item.e_no}}</span>
                                </label>

                            </div>

                        </div>

                        <div class="col-md-3 form-group"
                            ng-if="entry_id != 0 && formData.type == 3">

                            <label>Double Beds</label>

                            <input type="text" ng-model="formData.show_e_ids"
                                class="form-control" required readonly />

                        </div>

                        <!-- TOTAL AMOUNT -->

                        <div class="col-md-3 form-group">

                            <label>Total Amount</label>

                            <input type="number" ng-model="formData.total_amount"
                                class="form-control" readonly />

                        </div>

                        <!-- DISCOUNT -->

                        <div class="col-md-3 form-group" ng-if="!otp_verified">

                            <label>Are you want to give discount</label>
                            <br>

                            <label>
                                <input ng-click="sendOtp()" type="radio"
                                    ng-model="formData.is_discount" ng-value="1">
                                Yes
                            </label>

                            <label style="margin-left:15px;">
                                <input type="radio" ng-click="resetOtp()"
                                    ng-model="formData.is_discount" ng-value="0">
                                No
                            </label>

                        </div>

                        <!-- OTP -->

                        <div class="col-md-3 form-group"
                            ng-if="formData.is_discount == 1 && !otp_verified">

                            <label>Verify OTP</label>

                            <p>@{{otp}}</p>

                            <input class="form-control" type="number"
                                ng-model="formData.otp" ng-keyup="verifyOtp()">

                        </div>

                        <!-- DISCOUNT AMOUNT -->

                        <div ng-if="otp_verified" class="col-md-3 form-group">

                            <label>Discount Amount</label>

                            <input type="text" ng-model="formData.discount_amount"
                                ng-keyup="disAmount()" class="form-control" />

                        </div>

                        <!-- PAID -->

                        <div class="col-md-3 form-group">

                            <label>Paid Amount</label>

                            <input type="number" ng-model="formData.paid_amount"
                                class="form-control" readonly />

                        </div>

                        <!-- BALANCE -->

                        <div ng-if="entry_id !=0" class="col-md-3 form-group">

                            <label>Balance Amount</label>

                            <input type="number" ng-model="formData.balance_amount"
                                class="form-control" readonly />

                        </div>

                        <!-- REMARKS -->

                        <div class="col-md-12 form-group">

                            <label>Remarks</label>

                            <textarea ng-model="formData.remarks"
                                class="form-control"></textarea>

                        </div>

                    </div>

                    <div class="pt-4">

                        <button type="submit"
                            class="btn btn-primary-600 align-items-center justify-content-center gap-6 d-inline-flex"
                            ng-disabled="loading">

                            <span>
                                <span class="d-flex text-md">
                                    <i class="ri-add-large-line"></i>
                                </span>
                            </span>

                            Submit

                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</div>