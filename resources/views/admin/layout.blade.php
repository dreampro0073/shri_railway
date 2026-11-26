<?php 
    $version = env('JS_VERSION'); 
    $host = env('APP_ENV'); 
    $service_ids = Session::get('service_ids');

    $suff = ($host == 'prod') ? '.js' : '.js'; 
?>

<!DOCTYPE html>
<html>
<head>
   <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{url('manifest.webmanifest')}}">

    <title>Aadhyasri Web Solutions</title>
    
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/remixicon.css')}}">
    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/bootstrap.min.css')}}">
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/apexcharts.css')}}"> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/flatpickr.min.css')}}"> -->
    <!-- <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/calendar.css')}}"> -->

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/style.css')}}">

    <link rel="stylesheet" type="text/css" href="{{url('plugins/bootstrap-datepicker/css/datepicker3.css')}}">
    <link href="{{url('assets/css/selectize.css')}}" rel="stylesheet" type="text/css"/>

     <link rel="stylesheet" type="text/css" href="{{url('assets/css/custom.css?v='.$version)}}">
    <!-- Google tag (gtag.js) -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=G-WEC6NN0XE0"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-WEC6NN0XE0');
    </script> -->
</head>
<!-- <body  ng-app="app" oncontextmenu="return false;"> -->
<body  ng-app="app">
    <aside class="sidebar">
        @include('admin.theme_setting')
        @include('admin.sidebar')
    </aside>
  
    <main class="dashboard-main">
        <div class="navbar-header text-end">
            <div class="row">
                <div class="col-6">
                    <div class="d-flex flex-wrap align-items-center gap-4">
                        <button type="button" class="sidebar-mobile-toggle" aria-label="Sidebar Mobile Toggler Button" style="margin-top:8px;">
                          <i class="ri-menu-line"></i>
                        </button>
                        
                    </div>
                </div>
                <div class="col-6 text-end">
                    <div class="dropdown profile-dropdown d-inline-block">
                        <button type="button" class="profile-dropdown__button d-flex align-items-center justify-content-between p-10 w-100 overflow-hidden bg-neutral-50 radius-12 "  data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <span class="d-flex align-items-start gap-10">
                                <span class="profile-dropdown__contents">
                                    
                                    <!-- Desktop -->
                                    <span class="h6 mb-0 text-md d-block text-primary-light profile-name-desktop">
                                        {{ Auth::user()->name }}
                                    </span>

                                    <!-- Mobile -->
                                    <span class="h6 mb-0 text-md d-block text-primary-light profile-name-mobile">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                    </span>

                                </span>
                            </span>
                            <span class="profile-dropdown__icon pe-8 text-xl d-flex line-height-1">
                                <i class="ri-arrow-right-s-line"></i>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-lg-end border p-12">
                            <li>
                                <a href="{{url('/admin/reset-password')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-settings-3-line"></i>
                                Reset Password
                            </a>
                         </li>
                         <li>
                            <a href="{{url('/admin/offline-setup')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-wifi-off-line"></i>
                                Offline Setup
                            </a>
                         </li>
                         <li>
                            <a id="offlineSwitchLink" href="{{url('/login?offline=1')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-user-switch-line"></i>
                                Switch Offline Operator
                            </a>
                         </li>
                         <li>
                            <a href="{{url('logout')}}" class="dropdown-item rounded text-secondary-light bg-hover-neutral-200 text-hover-neutral-900 d-flex align-items-center gap-2 py-6">
                                <i class="ri-shut-down-line"></i>
                                Log Out
                            </a>
                         </li>
                      </ul>
                   </div>
                </div>
            </div>
           
           
           
        </div>

        <div class="dashboard-main-body">
            <div id="offlineBillNotice" class="alert alert-warning" style="display:none;" role="status"></div>
            @yield('main')
        </div>
    </main>

	
    

    <script type="text/javascript">
        var base_url = "{{url('/')}}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
        var auto_alert_status = "{{Session::get('auto_alert_status')}}";
        var authCheck = "{{Auth::user()->is_auto_alert_access}}";
        var api_key = "{{Auth::user()->api_token}}";
        var auth_user_id = {{ (int) Auth::id() }};
        try {
            if (!navigator.onLine) {
                window.aadhyaOfflineSession = JSON.parse(localStorage.getItem('aadhyaOfflineSession') || 'null');
            }
        } catch (e) {
            window.aadhyaOfflineSession = null;
        }
        if (window.aadhyaOfflineSession) {
            var offlineServiceRoutes = {
                '/admin/sitting': 1,
                '/admin/cloak-rooms': 2,
                '/admin/daily-entries': 3,
                '/admin/massage': 4,
                '/admin/locker': 5,
                '/admin/recliners': 7,
                '/admin/rooms': 8,
                '/admin/scanning': 9,
                '/admin/rest': 10
            };
            var offlineAllowedServices = (window.aadhyaOfflineSession.service_ids || []).map(Number);
            var offlineCurrentPath = window.location.pathname;
            var offlineCurrentServiceRoute = Object.keys(offlineServiceRoutes).find(function (route) {
                return offlineCurrentPath === route || offlineCurrentPath.indexOf(route + '/') === 0;
            });
            if (offlineCurrentServiceRoute && offlineAllowedServices.indexOf(offlineServiceRoutes[offlineCurrentServiceRoute]) === -1) {
                window.location.replace(base_url + '/admin/dashboard');
            }
            document.querySelectorAll('#sidebar-menu a[href]').forEach(function (link) {
                var linkPath = new URL(link.href, window.location.href).pathname;
                var serviceRoute = Object.keys(offlineServiceRoutes).find(function (route) {
                    return linkPath === route || linkPath.indexOf(route + '/') === 0;
                });
                if (serviceRoute && offlineAllowedServices.indexOf(offlineServiceRoutes[serviceRoute]) === -1) {
                    var item = link.closest('li');
                    if (item) item.style.display = 'none';
                }
                if (linkPath.indexOf('/admin/users') === 0 && Number(window.aadhyaOfflineSession.priv) !== 2) {
                    var userItem = link.closest('li');
                    if (userItem) userItem.style.display = 'none';
                }
            });
        }
    </script>

    <!-- <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('bootstrap3/js/bootstrap.min.js')}}"></script> -->

    <script type="text/javascript" src="{{url('assets1/js/lib/jquery-3.7.1.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets1/js/lib/bootstrap.bundle.min.js')}}"></script>
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/apexcharts.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/iconify-icon.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/dataTables.min.js')}}"></script> -->
    <!-- <script type="text/javascript" src="{{url('assets1/js/lib/jquery-ui.min.js')}}"></script> -->
    <script type="text/javascript" src="{{url('assets1/js/app.js')}}"></script>
    <script type="text/javascript" src="{{url('plugins/bootstrap-datepicker/js/bootstrap-datepicker.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/selectize.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/ng-file-upload.min.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/angular-selectize.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/jcs-auto-validate.js')}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/custom'.$suff.'?v='.$version)}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/app'.$suff.'?v='.$version)}}" ></script>
    <script type="text/javascript" src="{{url('assets/scripts/core/services'.$suff.'?v='.$version)}}" ></script>
    <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/controller'.$suff.'?v='.$version)}}"></script>
    <!-- <script type="text/javascript" type="text/javascript" src="{{url('assets/scripts/core/gcanteen_ctrl'.$suff.'?v='.$version)}}"></script> -->
      
   

    @yield('footer_scripts')

    <script>
      angular.module("app").constant("CSRF_TOKEN", "{{ csrf_token() }}");
    </script>
    <script>
      if ('serviceWorker' in navigator && window.isSecureContext) {
        navigator.serviceWorker.register("{{url('service-worker.js')}}").catch(function () {});
      }
      (function () {
        var offlineSwitchLink = document.getElementById('offlineSwitchLink');
        function updateOfflineSwitchLink() {
          if (offlineSwitchLink) offlineSwitchLink.style.display = navigator.onLine ? 'none' : '';
        }
        updateOfflineSwitchLink();
        window.addEventListener('online', function () {
          window.aadhyaOfflineSession = null;
          localStorage.removeItem('aadhyaOfflineSession');
          localStorage.removeItem('aadhyaOfflineLoginActive');
          updateOfflineSwitchLink();
        });
        window.addEventListener('offline', updateOfflineSwitchLink);
        function refreshOfflineBillNotice() {
          if (!window.indexedDB) return;
          var request = indexedDB.open('aadhyaOfflineBilling', 2);
          request.onsuccess = function () {
            var db = request.result;
            if (!db.objectStoreNames.contains('bill_queue')) { db.close(); return; }
            var tx = db.transaction('bill_queue', 'readonly');
            var read = tx.objectStore('bill_queue').getAll();
            read.onsuccess = function () {
              var bills = read.result || [];
              var pending = bills.filter(function (bill) { return bill.status === 'pending'; }).length;
              var review = bills.filter(function (bill) { return bill.status === 'needs_review'; }).length;
              var notice = document.getElementById('offlineBillNotice');
              if (pending || review) {
                notice.style.display = 'block';
                notice.className = 'alert ' + (review ? 'alert-danger' : 'alert-warning');
                notice.textContent = pending + ' offline bill(s) waiting to sync.' + (review ? ' ' + review + ' bill(s) need review: open Offline Setup on this PC.' : '');
              } else {
                notice.style.display = 'none';
              }
              db.close();
            };
            read.onerror = function () { db.close(); };
          };
        }
        window.addEventListener('aadhya-offline-bill-queued', refreshOfflineBillNotice);
        window.addEventListener('aadhya-offline-bill-synced', refreshOfflineBillNotice);
        window.addEventListener('online', function () { setTimeout(refreshOfflineBillNotice, 1000); });
        setTimeout(refreshOfflineBillNotice, 1000);
      })();
    </script>
</body>
</html>
