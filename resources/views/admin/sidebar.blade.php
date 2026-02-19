<button type="button" class="sidebar-close-btn">
   <i class="ri-close-large-line"></i>
</button>
<div class="">
    <div class="sidebar-logo d-flex align-items-center justify-content-between">
        <!-- <a href="javascript:;" class="">
            <span>{{Session::get('client_name')}}</span>
        </a> -->
        <button type="button" class="text-xxl d-xl-flex d-none line-height-1 sidebar-toggle text-neutral-500" aria-label="Collapse Sidebar">
            <i class="ri-contract-left-line"></i>
        </button>
    </div>
</div>
<div class="sidebar-menu-area">
    <ul class="sidebar-menu" id="sidebar-menu">
        @if(Auth::user()->priv !=5 && Auth::user()->priv !=1)
            <li class="@if(isset($sidebar)) @if($sidebar == 'dashboard') active @endif @endif">
                <a href="{{url('/admin/dashboard')}}">
                    <i class="ri-home-4-line"></i><span>Dashboard</span>
                </a>
            </li>
            @if(in_array(1, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'sitting') active @endif @endif">
                    <a href="{{url('/admin/sitting')}}">
                        <i class="ri-armchair-line"></i><span>Sitting</span>
                    </a>
                </li>
            @endif
            @if(in_array(8, $service_ids))
                <li class="@if(isset($sidebar)) @if($sidebar == 'pods') active @endif @endif">
                    <a href="{{url('/admin/rooms/1')}}">
                        <i class="ri-insert-row-top"></i><span>PODs</span>
                    </a>
                </li>
                <li class="@if(isset($sidebar)) @if($sidebar == 'scabins') active @endif @endif">
                    <a href="{{url('/admin/rooms/2')}}">
                        <i class="ri-hotel-bed-fill"></i><span>Single Suit Cabin</span>
                    </a>
                </li>
                <li class="@if(isset($sidebar)) @if($sidebar == 'beds') active @endif @endif">
                    <a href="{{url('/admin/rooms/3')}}">
                        <i class="ri-hotel-bed-line"></i><span>Double Beds</span>
                    </a>
                </li>

                <li class="@if(isset($sidebar)) @if($sidebar == 'beds') active @endif @endif">
                    <a href="{{url('/admin/rooms/4')}}">
                        <i class="ri-hotel-bed-line"></i><span>Online Booking</span>
                    </a>
                </li>
            @endif
            @if(in_array(8, $service_ids) && Auth::user()->priv == 2)
                <li class="@if(isset($sidebar)) @if($sidebar == 'all-entries') active @endif @endif">
                    <a href="{{url('/admin/all-rooms')}}">
                        <i class="ri-home-office-line"></i><span>All Rooms</span>
                    </a>
                </li>
            @endif
            @if(in_array(7, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'rec') active @endif @endif">
                    <a href="{{url('/admin/recliners')}}">
                        <i class="ri-armchair-fill"></i><span>Recliners</span>
                    </a>
                </li>
            @endif
            @if(in_array(4, $service_ids) || Auth::user()->priv == 1)
            <li class="@if(isset($sidebar)) @if($sidebar == 'massage') active @endif @endif">
                <a href="{{url('/admin/massage')}}">
                    <i class="ri-body-scan-line"></i><span>Massage</span>
                </a>
            </li>
            @endif
            @if(in_array(5, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'locker') active @endif @endif">
                    <a href="{{url('/admin/locker')}}">
                        <i class="ri-luggage-deposit-line"></i><span>Locker</span>
                    </a>
                </li>
            @endif
            @if(in_array(9, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'scanning') active @endif @endif">
                    <a href="{{url('/admin/scanning')}}">
                        <i class="ri-qr-scan-line"></i><span>Scanning</span>
                    </a>
                </li>
            @endif
            @if(in_array(10, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'rest') active @endif @endif">
                    <a href="{{url('/admin/rest')}}">
                        <i class="ri-tent-line"></i><span>Rest</span>
                    </a>
                </li>
            @endif
            @if(in_array(2, $service_ids) || Auth::user()->priv == 1)
                <li class="@if(isset($sidebar)) @if($sidebar == 'cloakrooms') active @endif @endif">
                    <a href="{{url('/admin/cloak-rooms')}}">
                        <i class="ri-briefcase-line"></i><span>Cloakrooms</span>
                    </a>
                </li>
                @if(Auth::user()->priv == 1 || Auth::user()->priv == 2)
                    <li class="@if(isset($sidebar)) @if($sidebar == 'all-cloakrooms') active @endif @endif">
                        <a href="{{url('/admin/cloak-rooms/all')}}">
                            <i class="ri-briefcase-4-line"></i><span>Cloakrooms All</span>
                        </a>
                    </li>
                @endif
                <li class="@if(isset($sidebar)) @if($sidebar == 'export') active @endif @endif">
                    <a href="{{url('/admin/cloak-rooms/export')}}">
                        <i class="ri-file-excel-line"></i><span>Export Cloakroom</span>
                    </a>
                </li>
                @if(Auth::user()->priv == 4 && Auth::user()->client_id == 6 && Auth::id() == 48 )
                    <li class="@if(isset($sidebar)) @if($sidebar == 'csitting') active @endif @endif">
                        <a href="{{url('/admin/collect-cloak')}}">
                            <i class="fa fa-sitemap"></i><span>Collect Cloack</span>
                        </a>
                    </li>
                @endif
            @endif                     
            @if(in_array(3, $service_ids) || Auth::user()->priv == 1)
                @if(Auth::user()->priv == 2 || Auth::user()->priv == 1)
                    <li class="@if(isset($sidebar)) @if($sidebar == 'cant_items') active @endif @endif">
                        <a href="{{url('/admin/canteens/items')}}">
                            <i class="ri-restaurant-2-line"></i><span>Canteen Items</span>
                        </a>
                    </li>
                @endif
                <li class="@if(isset($sidebar)) @if($sidebar == 'daily_entries') active @endif @endif">
                    <a href="{{url('/admin/daily-entries')}}">
                        <i class="ri-cup-line"></i><span>Daily Entries</span>
                    </a>
                </li>
            @endif
            @if(Auth::user()->priv == 2 && in_array(6, $service_ids))
                <li class="dropdown @if(isset($sidebar)) @if($sidebar == 'acc') active @endif @endif">
                    <a href="javascript:void(0)">
                        <i class="ri-home-4-line"></i>
                        <span>Accouting</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                           <a href="{{url('/admin/income')}}">
                               <i class="ri-circle-fill circle-icon w-auto"></i>
                               Income
                            </a>
                        </li>
                        <li>
                           <a href="{{url('/admin/expenses')}}">
                               <i class="ri-circle-fill circle-icon w-auto"></i>
                               Expense
                            </a>
                        </li>
                        <li>
                           <a href="{{url('/admin/summary')}}">
                               <i class="ri-circle-fill circle-icon w-auto"></i>
                               Summary
                            </a>
                        </li>
                       
                    </ul>
                </li>
            @endif
            <li class="@if(isset($sidebar)) @if($sidebar == 'shift') active @endif @endif">
                <a href="{{url('/admin/shift/current')}}">
                    <i class="ri-24-hours-fill"></i><span>Shift Status</span>
                </a>
            </li>
            @if(Auth::user()->priv == 2)
                <li class="@if(isset($sidebar)) @if($sidebar == 'users') active @endif @endif">
                    <a href="{{url('/admin/users')}}">
                        <i class="ri-group-line"></i><span>Users</span>
                    </a>
                </li>
            @endif
     
        @endif
        @if(Auth::user()->priv == 5 && Auth::user()->org_id == 1 && Auth::user()->is_super == 1)
            <li class="@if(isset($sidebar)) @if($sidebar == 'set_amount') active @endif @endif">
                <a href="{{url('/admin/clients/set-amount')}}">
                    <i class="ri-24-hours-fill"></i><span>Daily Set Hide Amount</span>
                </a>
            </li>
        @endif
        @if(Auth::user()->priv == 5 && Auth::user()->org_id == 1)
            <li class="@if(isset($sidebar)) @if($sidebar == 'shift_status') active @endif @endif">
                <a href="{{url('/admin/clients/shift-status')}}">
                    <i class="ri-24-hours-fill"></i><span>Daily Overall Shift Status</span>
                </a>
            </li>
        @endif
        @if(Auth::user()->priv == 1)
            <li class="@if(isset($sidebar)) @if($sidebar == 'dashboard') active @endif @endif">
                <a href="{{url('/superAdmin/dashboard')}}">
                    <i class="ri-home-4-line"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="@if(isset($sidebar)) @if($sidebar == 'clients') active @endif @endif">
                <a href="{{url('/superAdmin/clients')}}">
                    <i class="ri-service-line"></i><span>Clients</span>
                </a>
            </li>
        @endif
        <li>
            <a href="{{url('/logout')}}">
                <i class="ri-logout-circle-r-line"></i><span>Logout</span>
            </a>
        </li>
    </ul>
</div>