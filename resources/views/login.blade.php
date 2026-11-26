<!DOCTYPE html>
<html style="height:100%;">
<head >
    <!-- Basic Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="manifest" href="{{url('manifest.webmanifest')}}">
    
    <title>Aadhyasri Web Solutions | Web Development & Digital Services</title>
    <meta name="description" content="Aadhyasri Web Solutions provides professional web development, website design, SEO and digital solutions for businesses.">
    <meta name="keywords" content="Aadhyasri Web Solutions, web development, website design, SEO services, digital marketing">
    <meta name="author" content="Aadhyasri Web Solutions">
    <link rel="icon" sizes="32x32" type="image/x-icon" href="{{url('assets/img/favicon.png')}}" >

    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/lib/bootstrap.min.css')}}">


    <link rel="stylesheet" type="text/css" href="{{url('assets1/css/style.css')}}">
    <style>
        .min-wid{
            min-width: 400px;
        }
        @media only screen and (max-width: 668px) {
            .min-wid{
                min-width: 80%;
            }
        }
        label.error{
            color: #f00 !important;
            font-size: 14px !important;
        }
    </style>
</head>
<body class="h-100">


<div class="d-lg-flex bg-white h-100">
    <div class="w-50 d-lg-flex d-none overflow-hidden h-100">
        <img src="{{url('assets/img/railway1.jpg')}}" alt="Login Image" class="w-100 h-100 object-fit-cover">
    </div>
    <div class="lg-w-50 px-24 py-32 d-flex justify-content-center align-items-center h-100">
        <div class="max-w-540-px min-wid mx-auto">
            <a href="{{url('/')}}" class="">
                <img src="{{url('front-end/images/AashyaFinal1.png')}}" style="height:50px;width: auto;">
            </a>
            <div class="mt-32 mb-32">
                <h1 class="h6 fw-bold text-primary-light mb-8">
                    Welcome Back 👋
                </h1>
                <p class="text-sm text-secondary-light mb-0">
                    Log in to your account to continue
                </p>
            </div>
            {{ Form::open(array('url' => '/login','class' => 'submit-form check-form',"method"=>"POST")) }}

                @if(Session::has('failure'))
                    <div class="alert alert-danger" style="margin-top: 10px;">
                        <i class="fa fa-ban-circle"></i><strong>Failure!</strong> {{Session::get('failure')}}
                    </div>
                @endif

                @if(Session::has('success'))
                    <div class="alert alert-success">
                       <i class="fa fa-ban-circle"></i><strong>success!</strong> {{Session::get('success')}}
                     </div>    
                @endif
                <div class="d-flex flex-column">
                    <div>
                        <label for="email" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Email Address
                            <span class="text-danger-600">*</span>
                        </label>
                        

                        {{Form::text('email','',["class"=>"form-control form-control-user","id"=>"email","autocomplete"=>"off","placeholder"=>"Enter your email Address...",'required'=>"required"])}}
                        
                    </div>

                    <div class="mt-3">
                        <label for="password" class="text-sm fw-semibold text-primary-light d-inline-block mb-8">
                            Password
                            <span class="text-danger-600">*</span>
                        </label>
                        <div class="position-relative">
                            
                            {{Form::password('password',["class"=>"password-field form-control","required"=>"true","id"=>"password","placeholder"=>"Enter Password"])}}
                            <!-- <button type="button"
                                class="toggle-password btn p-0 border-0 bg-transparent position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light cursor-pointer ri-eye-line"
                                data-toggle="#password" aria-label="Toggle password visibility">
                            </button> -->
                        </div>
                    </div>

                    <div class="text-center mt-5">
                        <button type="submit" class="btn btn-success btn-user btn-block min-wid" style="margin:auto;">Login</button>
                    </div>

                   
                </div>
              
                
            {{Form::close()}}

            <div id="offlineLoginBox" class="mt-4 p-3 border rounded" style="display:none;">
                <h2 class="h6 fw-bold mb-3">Offline Operator Login</h2>
                <div class="mb-3">
                    <label for="offlineOperator" class="text-sm fw-semibold text-primary-light d-inline-block mb-2">Operator</label>
                    <select id="offlineOperator" class="form-control"></select>
                </div>
                <div class="mb-3">
                    <label for="offlineLoginPin" class="text-sm fw-semibold text-primary-light d-inline-block mb-2">Offline PIN</label>
                    <input id="offlineLoginPin" type="password" inputmode="numeric" maxlength="6" class="form-control" autocomplete="off">
                </div>
                <button type="button" id="offlineLoginButton" class="btn btn-primary-600 w-100">Continue Offline</button>
                <div id="offlineLoginStatus" class="mt-2" role="status"></div>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = "{{url('/')}}";
    var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('assets/scripts/jquery.validate.js')}}"></script>
    <script type="text/javascript">
        $(".check-form").validate();
    </script>
    <script type="text/javascript">
    (function () {
        var box = document.getElementById('offlineLoginBox');
        var operatorSelect = document.getElementById('offlineOperator');
        var pinInput = document.getElementById('offlineLoginPin');
        var statusBox = document.getElementById('offlineLoginStatus');
        var offlineRequested = new URLSearchParams(window.location.search).get('offline') === '1';

        if ('serviceWorker' in navigator && window.isSecureContext) {
            navigator.serviceWorker.register(base_url + '/service-worker.js').catch(function () {});
        }
        document.querySelector('.check-form').addEventListener('submit', function () {
            if (navigator.onLine) {
                localStorage.removeItem('aadhyaOfflineSession');
                localStorage.removeItem('aadhyaOfflineLoginActive');
            }
        });

        function showOfflineLogin() {
            if (!box) return;
            box.style.display = 'block';
            var onlineLogin = document.querySelector('.check-form');
            if (!navigator.onLine || offlineRequested) onlineLogin.style.display = 'none';
        }

        function openDb() {
            return new Promise(function (resolve, reject) {
                var request = indexedDB.open('aadhyaOfflineBilling', 2);
                request.onupgradeneeded = function () {
                    var db = request.result;
                    if (!db.objectStoreNames.contains('operators')) db.createObjectStore('operators', { keyPath: 'user_id' });
                    if (!db.objectStoreNames.contains('api_cache')) db.createObjectStore('api_cache', { keyPath: 'cache_key' });
                    if (!db.objectStoreNames.contains('bill_queue')) db.createObjectStore('bill_queue', { keyPath: 'bill_uuid' });
                };
                request.onsuccess = function () { resolve(request.result); };
                request.onerror = function () { reject(request.error); };
            });
        }

        function getOperators() {
            return openDb().then(function (db) {
                return new Promise(function (resolve, reject) {
                    var tx = db.transaction('operators', 'readonly');
                    var request = tx.objectStore('operators').getAll();
                    request.onsuccess = function () { db.close(); resolve(request.result || []); };
                    request.onerror = function () { db.close(); reject(request.error); };
                });
            });
        }

        function deriveVerifier(pin, salt, iterations) {
            return crypto.subtle.importKey('raw', new TextEncoder().encode(pin), 'PBKDF2', false, ['deriveBits'])
                .then(function (key) {
                    return crypto.subtle.deriveBits({
                        name: 'PBKDF2',
                        salt: new Uint8Array(salt),
                        iterations: iterations,
                        hash: 'SHA-256'
                    }, key, 256);
                }).then(function (bits) {
                    return Array.from(new Uint8Array(bits)).map(function (byte) {
                        return byte.toString(16).padStart(2, '0');
                    }).join('');
                });
        }

        function loadOperators() {
            if (!window.indexedDB) return;
            getOperators().then(function (operators) {
                operatorSelect.innerHTML = '';
                operators.forEach(function (operator) {
                    var option = document.createElement('option');
                    option.value = operator.user_id;
                    option.textContent = operator.name;
                    operatorSelect.appendChild(option);
                });
                if (!operators.length) {
                    statusBox.className = 'text-danger mt-2';
                    statusBox.textContent = 'Is browser me kisi operator ka offline setup nahi mila.';
                }
            }).catch(function () {
                statusBox.className = 'text-danger mt-2';
                statusBox.textContent = 'Offline operator data nahi padh saka.';
            });
        }

        if (!navigator.onLine || offlineRequested) {
            showOfflineLogin();
            loadOperators();
        }
        window.addEventListener('offline', function () {
            showOfflineLogin();
            loadOperators();
        });

        document.getElementById('offlineLoginButton').addEventListener('click', function () {
            var userId = parseInt(operatorSelect.value, 10);
            var pin = pinInput.value;
            if (!userId || !/^\d{6}$/.test(pin)) {
                statusBox.className = 'text-danger mt-2';
                statusBox.textContent = 'Operator aur 6 digit PIN enter karein.';
                return;
            }

            getOperators().then(function (operators) {
                var operator = operators.find(function (item) { return item.user_id === userId; });
                if (!operator) throw new Error('Offline setup nahi mila.');
                return deriveVerifier(pin, operator.pin_salt, operator.pin_iterations).then(function (verifier) {
                    if (verifier !== operator.pin_verifier) throw new Error('Offline PIN sahi nahi hai.');
                    window.aadhyaOfflineSession = operator;
                    localStorage.setItem('aadhyaOfflineSession', JSON.stringify(operator));
                    localStorage.setItem('aadhyaOfflineLoginActive', '1');
                    window.location.href = base_url + '/admin/dashboard';
                });
            }).catch(function (error) {
                statusBox.className = 'text-danger mt-2';
                statusBox.textContent = error.message || 'Offline login nahi ho saka.';
            });
        });
    })();
    </script>
</body>
</html>
