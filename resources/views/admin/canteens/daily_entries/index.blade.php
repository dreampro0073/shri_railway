
@extends('admin.layout')

@section('main')
<div class="main" ng-controller="dailyEntryCtrl" ng-init="init();">

    @include("admin.canteens.daily_entries.add")

    <div class="card shadow mb-4 p-4">
        <div class="filters mb-3">

            <form name="filterForm" novalidate>
                <div class="row">

                    <div class="col-md-2 form-group">
                        <label class="label-control">Bill ID</label>
                        <input type="text" class="form-control" ng-model="filter.id" />
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="label-control">Name</label>
                        <input type="text" class="form-control" ng-model="filter.name" />
                    </div>

                    <div class="col-md-2 form-group">
                        <label class="label-control">Mobile</label>
                        <input type="text" class="form-control" ng-model="filter.mobile" />
                    </div>

                    <div class="col-md-3 text-right mb-2" style="margin-top:28px;">

                        <button type="button"
                            ng-click="searchData()"
                            class="btn btn-sm btn-primary-600 align-items-center gap-6 d-inline-flex">
                            <span class="d-flex text-md">
                                <i class="ri-search-line"></i>
                            </span>
                            Search
                        </button>

                        <button type="button"
                            ng-click="filterClear()"
                            class="btn btn-sm btn-warning-600 align-items-center gap-6 d-inline-flex">
                            <span class="d-flex text-md">
                                <i class="ri-blur-off-line"></i>
                            </span>
                            Clear
                        </button>

                    </div>

                </div>
            </form>
            <!-- Pagination -->
            <!-- <div class="cloak-pagination-wrapper" ng-if="total_entries > 0">

                <div class="cloak-pagination-info">
                    Showing
                    <strong>
                        @{{ ((filter.page_no - 1) * per_page) + 1 }}
                    </strong>
                    to
                    <strong>
                        @{{ ((filter.page_no - 1) * per_page) + daily_entries.length }}
                    </strong>
                    of
                    <strong>@{{ total_entries }}</strong>
                    records
                </div>

                <div class="cloak-pagination-controls">

                    <button type="button"
                        class="cloak-page-btn"
                        ng-click="getData(-1)"
                        ng-disabled="filter.page_no <= 1"
                        title="Previous page">

                        <i class="ri-arrow-left-s-line"></i>
                        <span style="margin-left:3px;">Previous</span>

                    </button>

                    <button type="button"
                        class="cloak-page-btn active"
                        disabled>
                        @{{ filter.page_no }}
                    </button>

                    <span class="cloak-page-label">
                        of @{{ total_pages }}
                    </span>

                    <button type="button"
                        class="cloak-page-btn"
                        ng-click="getData(1)"
                        ng-disabled="filter.page_no >= total_pages"
                        title="Next page">

                        <span style="margin-right:3px;">Next</span>
                        <i class="ri-arrow-right-s-line"></i>

                    </button>

                </div>

            </div> -->

            <!-- No Records -->
            <div class="alert alert-danger" ng-if="total_entries == 0">
                <i class="ri-information-line"></i>
                No records found!
            </div>
            <div class="dt-layout-row dt-layout-table">
                <div class="dt-layout-cell">

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">

                            <thead>
                                <tr>
                                    <th>S.no</th>
                                    <th>Bill ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Paid Amount</th>
                                    <th>Time</th>
                                    <th>#</th>
                                </tr>
                            </thead>

                            <tbody ng-if="daily_entries.length > 0">

                                <tr ng-repeat="item in daily_entries track by item.id">

                                    <td>
                                        @{{ ((filter.page_no - 1) * per_page) + $index + 1 }}
                                    </td>

                                    <td>
                                        @{{ item.id }}
                                    </td>

                                    <td>
                                        @{{ item.name }}
                                    </td>

                                    <td>
                                        @{{ item.mobile }}
                                    </td>

                                    <td>
                                        @{{ item.total_amount }}
                                    </td>

                                    <td>
                                        @{{ item.time }}
                                    </td>

                                    <td>
                                        <a href="{{ url('admin/daily-entries/print/') }}/@{{item.id}}"
                                            class="btn btn-sm btn-warning"
                                            target="_blank">
                                            Print
                                        </a>
                                    </td>

                                </tr>

                            </tbody>

                        </table>
                    </div>

                    

                </div>
            </div>

        </div>
    </div>

</div>
@endsection
