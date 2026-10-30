
<style>
.canteen-billing .form-control {
    width: 100%;
    min-width: 0;
}
.canteen-billing .form-group {
    margin-bottom: 15px;
}
.canteen-billing .form-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 7px;
}
.canteen-billing .billing-section {
    padding: 16px;
    background: #f8fafb;
    border: 1px solid #e5e9ed;
    border-radius: 8px;
    height: 100%;
}
.canteen-billing .billing-title {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}
.canteen-billing .item-search-wrap {
    position: relative;
    width: 100%;
}
.canteen-billing .item-search-input {
    padding-right: 35px;
}
.canteen-billing .item-search-icon {
    position: absolute;
    top: 10px;
    right: 12px;
    pointer-events: none;
    color: #777;
}
.canteen-billing .item-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    z-index: 1050;
    max-height: 240px;
    overflow-y: auto;
    background: #fff;
    border: 1px solid #d9dfe5;
    border-radius: 0 0 6px 6px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.12);
}
.canteen-billing .item-dropdown-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    cursor: pointer;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}
.canteen-billing .item-dropdown-option:hover {
    background: #eef7f5;
}
.canteen-billing .item-dropdown-option:last-child {
    border-bottom: none;
}
.canteen-billing .item-dropdown-empty {
    padding: 12px;
    font-size: 13px;
    color: #777;
}
.canteen-billing .item-dropdown-price {
    white-space: nowrap;
    font-weight: 600;
    color: #24877e;
}
.canteen-billing .manual-add-btn {
    width: 100%;
    margin-top: 8px;
}
.canteen-billing .payment-options {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 20px;
    padding-top: 8px;
}
.canteen-billing .payment-options label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin: 0;
    cursor: pointer;
}
.canteen-billing .items-table th,
.canteen-billing .items-table td {
    font-size: 13px;
    vertical-align: middle;
}
.canteen-billing .items-table th {
    white-space: nowrap;
}
.canteen-billing .items-table .item-name {
    min-width: 120px;
}
.canteen-billing .total-row {
    background: #f2f8f6;
    font-weight: 600;
}
.canteen-billing .submit-area {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}
.canteen-billing .grand-total {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 10px;
}
@media(max-width:767px) {
    .canteen-billing .billing-section {
        height: auto;
        margin-bottom: 15px;
    }
}
.canteen-billing .payment-options {
    display: flex;
    align-items: center;
    gap: 10px;
    padding-top: 0;
    width: 100%;
}

.canteen-billing .payment-card {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex: 1;
    height: 40px;
    min-width: 0;
    padding: 6px 12px;
    margin: 0;
    border: 1.5px solid #e2e6ee;
    border-radius: 8px;
    background: #fff;
    color: #555;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.25s ease;
    box-sizing: border-box;
}

.canteen-billing .payment-card input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
}

.canteen-billing .payment-card > i:first-of-type {
    font-size: 18px;
    line-height: 1;
}

.canteen-billing .payment-card:hover {
    border-color: #3F51B5;
    background: #f5f6ff;
    color: #3F51B5;
}

.canteen-billing .payment-card.selected {
    border-color: #3F51B5;
    background: #eef0ff;
    color: #3F51B5;
    box-shadow: 0 2px 6px rgba(63, 81, 181, 0.10);
}

.canteen-billing .payment-check {
    position: absolute;
    top: 3px;
    right: 5px;
    font-size: 13px;
    color: #3F51B5;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.canteen-billing .payment-card.selected .payment-check {
    opacity: 1;
}

.canteen-billing .payment-card:focus-within {
    outline: 2px solid #3F51B5;
    outline-offset: 2px;
}
</style>

<div class="card shadow mb-4 p-4 canteen-billing">
    <form name="myForm" novalidate>

        <!-- BARCODE AND MANUAL ITEM -->
        <div class="row mb-3">

            <!-- BARCODE SCANNER -->
            <div class="col-md-6 mb-3 d-none">
                <div class="billing-section">

                    <div class="billing-title">
                        <i class="ri-barcode-line"></i>
                        Scan Barcode
                    </div>

                    <div class="form-group">
                        <label class="form-label">Barcode</label>

                        <input autofocus type="text"
                            id="productname"
                            ng-model="productname"
                            ng-keypress="handleKeyPress($event)"
                            class="form-control"
                            placeholder="Scan item barcode"
                            autocomplete="off" />
                    </div>

                    <small class="text-muted">
                        Scan barcode to add item automatically.
                    </small>
                </div>
            </div>

            <!-- MANUAL ITEM SELECTION -->
            <div class="col-md-12 mb-3">
                <div class="billing-section">

                    <div class="billing-title">
                        <i class="ri-search-line"></i>
                        Select Item Manually
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-label">Choose Item</label>

                                <div class="item-search-wrap">

                                    <input type="text"
                                        class="form-control item-search-input"
                                        ng-model="manualSearch"
                                        ng-focus="manualDropdownOpen = true"
                                        ng-change="onManualSearchChange()"
                                        ng-blur="closeManualDropdown()"
                                        placeholder="Search or select item..."
                                        autocomplete="off" />

                                    <i class="ri-arrow-down-s-line item-search-icon"></i>

                                    <div class="item-dropdown" ng-show="manualDropdownOpen">

                                        <div class="item-dropdown-option"
                                            ng-repeat="item in canteen_items | filter:{item_name:manualSearch} | limitTo:50 track by item.canteen_item_id"
                                            ng-mousedown="selectManualItem(item,$event)">

                                            <span>
                                                <strong>@{{ item.item_name }}</strong>
                                                <small class="text-muted d-block">
                                                    Stock: @{{ item.stock }}
                                                </small>
                                            </span>

                                            <span class="item-dropdown-price">
                                                ₹@{{ item.price }}
                                            </span>
                                        </div>

                                        <div class="item-dropdown-empty"
                                            ng-if="(canteen_items | filter:{item_name:manualSearch}).length == 0">
                                            No matching item found
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- QUANTITY -->
                            <div class="form-group">
                                <label class="form-label">Quantity</label>

                                <input type="number"
                                    class="form-control"
                                    ng-model="manualQuantity"
                                    min="1"
                                    step="1"
                                    placeholder="Enter quantity" />

                                <small class="text-muted" ng-if="manualSelectedItem">
                                    Available Stock: @{{ manualSelectedItem.stock }}
                                </small>
                            </div>
                        </div>

                        <div class="col-md-4" style="margin-top: 20px;">
                            

                            <button type="button"
                                class="btn btn-primary-600 manual-add-btn"
                                ng-click="addManualItem()"
                                ng-disabled="!manualSelectedItem || !manualQuantity || manualQuantity < 1">

                                <i class="ri-add-line"></i>
                                Add Item
                            </button>
                        </div>

                    </div>

                    <!-- MANUAL TOTAL -->
                    <div class="form-group" ng-if="manualSelectedItem">
                        <small class="text-muted">
                            Price: ₹@{{ manualSelectedItem.price }}
                            × @{{ manualQuantity || 0 }}
                        </small>

                        <div>
                            <strong>
                                Amount: ₹@{{ (manualSelectedItem.price * manualQuantity) | number:2 }}
                            </strong>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>



        <!-- CUSTOMER DETAILS -->
        <div class="row mb-3">

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Name</label>

                    <input tabindex="-1"
                        type="text"
                        ng-model="formData.name"
                        class="form-control"
                        placeholder="Customer name" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Mobile</label>

                    <input tabindex="-1"
                        type="number"
                        ng-model="formData.mobile"
                        class="form-control"
                        placeholder="Mobile number" />
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="form-label">Pay Type</label>

                    <div class="payment-options">

                        <label class="payment-card" ng-class="{'selected': formData.pay_type == '1'}">
                            <input type="radio" ng-model="formData.pay_type" value="1">
                            <i class="ri-money-rupee-circle-line"></i>
                            <span>Cash</span>
                            <i class="ri-checkbox-circle-fill payment-check"></i>
                        </label>

                        <label class="payment-card" ng-class="{'selected': formData.pay_type == '2'}">
                            <input type="radio" ng-model="formData.pay_type" value="2">
                            <i class="ri-smartphone-line"></i>
                            <span>UPI</span>
                            <i class="ri-checkbox-circle-fill payment-check"></i>
                        </label>

                    </div>
                </div>
            </div>
        </div>

        <!-- ITEMS TABLE -->
        <div class="row">
            <div class="col-md-12">

                <h6 class="mb-3">
                    <i class="ri-shopping-cart-line"></i>
                    Items (@{{ formData.total_item }})
                </h6>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped items-table">

                        <thead>
                            <tr>
                                <th>Sn</th>
                                <th>Item Name</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>#</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr ng-repeat="item in products track by $index">

                                <td>@{{ $index + 1 }}</td>
                                <td class="item-name">@{{ item.item_name }}</td>
                                <td>₹@{{ item.price }}</td>
                                <td>@{{ item.quantity }}</td>
                                <td>₹@{{ item.paid_amount | number:2 }}</td>

                                <td>
                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        ng-click="removeItem($index)">

                                        X
                                    </button>
                                </td>
                            </tr>

                            <tr class="total-row" ng-if="products.length > 0">
                                <td colspan="3">
                                    <strong>Total</strong>
                                </td>

                                <td>
                                    <strong>@{{ formData.total_item }}</strong>
                                </td>

                                <td colspan="2">
                                    <strong>
                                        ₹@{{ formData.total_amount | number:2 }}
                                    </strong>
                                </td>
                            </tr>

                            <tr ng-if="products.length == 0">
                                <td colspan="6">
                                    <div class="alert alert-danger mb-0">
                                        <i class="ri-information-line"></i>
                                        No Item Added in the List
                                    </div>
                                </td>
                            </tr>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <!-- SUBMIT BILL -->
        <div class="submit-area">
            <div class="d-flex justify-content-between align-items-center flex-wrap">

                <div class="grand-total" style="flex:1;">
                    Grand Total:
                    ₹@{{ formData.total_amount | number:2 }}
                </div>

                <div style="flex:1;text-align: right;">
                    <button type="button"
                        class="btn btn-sm btn-primary-600 align-items-center gap-6 d-inline-flex"
                        ng-click="onSubmit()"
                        ng-disabled="loading || products.length == 0" style="width:auto;">

                        <span ng-if="!loading">
                            <i class="ri-check-line"></i>
                            Submit Bill
                        </span>

                        <span ng-if="loading">
                            Processing...
                        </span>
                    </button>
                </div>

            </div>
        </div>

    </form>
</div>

<div style="margin-bottom:16px;"></div>
