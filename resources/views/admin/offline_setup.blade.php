@extends('admin.layout')

@section('main')
<div class="card shadow mb-4 p-4">
    <h5 class="mb-2">Offline Setup</h5>
    <p>Is PC aur browser ko offline billing ke liye pehle online tayyar karein. Har operator ko apne account se login karke apna offline PIN set karna hoga.</p>
    <div class="alert alert-info">PIN isi browser me rahega. Isse aapka password replace nahi hota. Offline mode tabhi tayyar hoga jab setup, app pages aur billing data yahan save ho jayen.</div>

    <div class="row">
        <div class="col-md-4 form-group">
            <label>Offline PIN (6 digits)</label>
            <input id="offlinePin" type="password" inputmode="numeric" maxlength="6" class="form-control" autocomplete="new-password">
        </div>
        <div class="col-md-4 form-group">
            <label>Confirm Offline PIN</label>
            <input id="offlinePinConfirm" type="password" inputmode="numeric" maxlength="6" class="form-control" autocomplete="new-password">
        </div>
        <div class="col-md-4 form-group" style="padding-top:29px;">
            <button id="offlineSetupButton" type="button" class="btn btn-primary-600">Set Up This PC</button>
        </div>
    </div>
    <div id="offlineSetupStatus" class="mt-3" role="status"></div>
    <hr>
    <h6>Offline bills on this PC</h6>
    <div id="offlineBillQueueList" class="mt-2">Loading...</div>
</div>

<script>
(function () {
    var setupButton = document.getElementById('offlineSetupButton');
    var statusBox = document.getElementById('offlineSetupStatus');
    var iterations = 210000;
    var authUserId = {{ (int) Auth::id() }};
    var activeServiceIds = @json(Session::get('service_ids') ?: []);
    var companyInfo = {
        name: @json(Session::get('client_name')),
        print_name: @json(Session::get('print_name')),
        gst: @json(Session::get('gst_no')),
        address: @json(Session::get('address')),
        mobile: @json(Session::get('mobile'))
    };
    var initRoutes = [
        '/api/sitting/init',
        '/api/recliners/init',
        '/api/cloak-rooms/init/0',
        '/api/daily-entries/init',
        '/api/massage/init',
        '/api/locker/init',
        '/api/rooms/init/1',
        '/api/rooms/init/2',
        '/api/rooms/init/3',
        '/api/scanning/init',
        '/api/rest/init'
    ];
    var pageRoutes = [
        '/login',
        '/admin/dashboard',
        '/admin/sitting',
        '/admin/recliners',
        '/admin/cloak-rooms',
        '/admin/daily-entries',
        '/admin/massage',
        '/admin/locker',
        '/admin/rooms/1',
        '/admin/rooms/2',
        '/admin/rooms/3',
        '/admin/scanning',
        '/admin/rest'
    ];
    var assetRoutes = [
        '/assets1/css/remixicon.css',
        '/assets1/css/lib/bootstrap.min.css',
        '/assets1/css/style.css',
        '/assets/css/selectize.css',
        '/assets/css/custom.css',
        '/plugins/bootstrap-datepicker/css/datepicker3.css',
        '/assets1/js/lib/jquery-3.7.1.min.js',
        '/assets1/js/lib/bootstrap.bundle.min.js',
        '/assets1/js/app.js',
        '/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
        '/assets/scripts/jquery.min.js',
        '/assets/scripts/jquery.validate.js',
        '/assets/scripts/selectize.min.js',
        '/assets/scripts/angular.min.js',
        '/assets/scripts/ng-file-upload.min.js',
        '/assets/scripts/angular-selectize.js',
        '/assets/scripts/jcs-auto-validate.js',
        '/assets/scripts/core/custom.js',
        '/assets/scripts/core/app.js',
        '/assets/scripts/core/services.js',
        '/assets/scripts/core/controller.js'
    ];
    var requiredInitRoutes = {
        1: ['/api/sitting/init'],
        2: ['/api/cloak-rooms/init/0'],
        3: ['/api/daily-entries/init'],
        4: ['/api/massage/init'],
        5: ['/api/locker/init'],
        7: ['/api/recliners/init'],
        8: ['/api/rooms/init/1', '/api/rooms/init/2', '/api/rooms/init/3'],
        9: ['/api/scanning/init'],
        10: ['/api/rest/init']
    };

    function setStatus(message, type) {
        statusBox.className = 'mt-3 alert alert-' + (type || 'info');
        statusBox.textContent = message;
    }

    function openOfflineDb() {
        return new Promise(function (resolve, reject) {
            var request = indexedDB.open('aadhyaOfflineBilling', 2);
            request.onupgradeneeded = function () {
                var db = request.result;
                if (!db.objectStoreNames.contains('operators')) {
                    db.createObjectStore('operators', { keyPath: 'user_id' });
                }
                if (!db.objectStoreNames.contains('api_cache')) {
                    db.createObjectStore('api_cache', { keyPath: 'cache_key' });
                }
                if (!db.objectStoreNames.contains('bill_queue')) {
                    db.createObjectStore('bill_queue', { keyPath: 'bill_uuid' });
                }
            };
            request.onsuccess = function () { resolve(request.result); };
            request.onerror = function () { reject(request.error); };
        });
    }

    function saveRecord(storeName, record) {
        return openOfflineDb().then(function (db) {
            return new Promise(function (resolve, reject) {
                var tx = db.transaction(storeName, 'readwrite');
                tx.objectStore(storeName).put(record);
                tx.oncomplete = function () { db.close(); resolve(); };
                tx.onerror = function () { db.close(); reject(tx.error); };
            });
        });
    }

    function getOperatorRecord(userId) {
        return openOfflineDb().then(function (db) {
            return new Promise(function (resolve) {
                var tx = db.transaction('operators', 'readonly');
                var request = tx.objectStore('operators').get(Number(userId));
                request.onsuccess = function () { db.close(); resolve(request.result || null); };
                request.onerror = function () { db.close(); resolve(null); };
            });
        }).catch(function () { return null; });
    }

    function refreshQueueList() {
        openOfflineDb().then(function (db) {
            if (!db.objectStoreNames.contains('bill_queue')) {
                db.close();
                document.getElementById('offlineBillQueueList').textContent = 'No offline bills.';
                return;
            }
            var tx = db.transaction('bill_queue', 'readonly');
            var request = tx.objectStore('bill_queue').getAll();
            request.onsuccess = function () {
                var bills = request.result || [];
                var list = document.getElementById('offlineBillQueueList');
                if (!bills.length) {
                    list.textContent = 'No offline bills.';
                    db.close();
                    return;
                }
                var table = document.createElement('table');
                table.className = 'table table-bordered table-sm';
                table.innerHTML = '<thead><tr><th>Bill No</th><th>Service</th><th>Date</th><th>Status</th><th>Message</th></tr></thead>';
                var body = document.createElement('tbody');
                bills.sort(function (a, b) { return b.created_at - a.created_at; }).forEach(function (bill) {
                    var row = document.createElement('tr');
                    [bill.bill_number, bill.service, new Date(bill.created_at).toLocaleString('en-IN'), bill.status, bill.last_error || ''].forEach(function (value) {
                        var cell = document.createElement('td');
                        cell.textContent = value || '';
                        row.appendChild(cell);
                    });
                    body.appendChild(row);
                });
                table.appendChild(body);
                list.innerHTML = '';
                list.appendChild(table);
                db.close();
            };
            request.onerror = function () { db.close(); };
        }).catch(function () {
            document.getElementById('offlineBillQueueList').textContent = 'Offline bill queue abhi available nahi hai.';
        });
    }

    function getDeviceUuid() {
        var deviceUuid = localStorage.getItem('aadhyaOfflineDeviceUuid');
        if (!deviceUuid) {
            deviceUuid = crypto.randomUUID();
            localStorage.setItem('aadhyaOfflineDeviceUuid', deviceUuid);
        }
        return deviceUuid;
    }

    function makeVerifier(pin, salt) {
        var encoder = new TextEncoder();
        return crypto.subtle.importKey('raw', encoder.encode(pin), 'PBKDF2', false, ['deriveBits'])
            .then(function (key) {
                return crypto.subtle.deriveBits({
                    name: 'PBKDF2',
                    salt: salt,
                    iterations: iterations,
                    hash: 'SHA-256'
                }, key, 256);
            }).then(function (bits) {
                return Array.from(new Uint8Array(bits)).map(function (byte) {
                    return byte.toString(16).padStart(2, '0');
                }).join('');
            });
    }

    function cacheSetupData() {
        var headers = {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'apiToken': api_key
        };
        var tasks = initRoutes.map(function (route) {
            return fetch(base_url + route, {
                method: 'POST',
                credentials: 'same-origin',
                headers: headers,
                body: '{}'
            }).then(function (response) {
                if (!response.ok) return null;
                return response.json().then(function (data) {
                    if (!data || !data.success) return null;
                    return saveRecord('api_cache', {
                        cache_key: String(authUserId) + '|' + route + '|{}',
                        route: route,
                        user_id: authUserId,
                        data: data,
                        saved_at: Date.now()
                    }).then(function () { return true; });
                });
            }).catch(function () { return null; });
        });
        return Promise.all(tasks).then(function (results) {
            if (!results.some(Boolean)) throw new Error('Billing data cache nahi ho saka. Internet/login check karke dobara setup karein.');
            var required = [];
            activeServiceIds.forEach(function (serviceId) {
                (requiredInitRoutes[Number(serviceId)] || []).forEach(function (route) {
                    if (required.indexOf(route) === -1) required.push(route);
                });
            });
            var missing = required.filter(function (route) {
                return results[initRoutes.indexOf(route)] !== true;
            });
            if (missing.length) throw new Error('Kuch service data cache nahi hua (' + missing.join(', ') + '). Internet/login check karke setup dobara karein.');
        });
    }

    function cachePages() {
        var pages = pageRoutes.map(function (route) {
            return fetch(base_url + route, { credentials: 'same-origin' }).catch(function () { return null; });
        });
        var assets = assetRoutes.map(function (route) {
            return fetch(base_url + route, { credentials: 'same-origin' }).catch(function () { return null; });
        });
        return Promise.all(pages.concat(assets)).then(function (results) {
            if (!results[1] || !results[1].ok) throw new Error('Dashboard offline ke liye cache nahi ho saka. Setup dobara try karein.');
        });
    }

    setupButton.addEventListener('click', function () {
        var pin = document.getElementById('offlinePin').value;
        var pinConfirm = document.getElementById('offlinePinConfirm').value;

        if (!/^\d{6}$/.test(pin)) {
            setStatus('PIN 6 digit ka hona chahiye.', 'danger');
            return;
        }
        if (pin !== pinConfirm) {
            setStatus('Dono PIN match nahi karte.', 'danger');
            return;
        }
        if (!window.isSecureContext || !('serviceWorker' in navigator) || !window.indexedDB || !window.crypto || !crypto.subtle || !crypto.randomUUID) {
            setStatus('Is browser me secure offline setup supported nahi hai. Updated Chrome aur HTTPS par app kholen.', 'danger');
            return;
        }

        setupButton.disabled = true;
        setStatus('PC register ho raha hai aur offline data save kiya ja raha hai...', 'info');

        var deviceUuid = getDeviceUuid();
        getOperatorRecord(authUserId).then(function (previousOperator) {
            return fetch(base_url + '/api/offline-setup', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'apiToken': api_key
                },
                body: JSON.stringify({
                    device_uuid: deviceUuid,
                    device_name: navigator.userAgent.slice(0, 120),
                    offline_token: previousOperator ? previousOperator.offline_token : ''
                })
            });
        }).then(function (response) {
            return response.json().then(function (data) {
                if (!response.ok || !data.success) throw new Error(data.message || 'PC register nahi ho saka.');
                return data;
            });
        }).then(function (data) {
            var salt = crypto.getRandomValues(new Uint8Array(16));
            return makeVerifier(pin, salt).then(function (verifier) {
                return saveRecord('operators', {
                    user_id: data.user.id,
                    client_id: data.user.client_id,
                    name: data.user.name,
                    priv: data.user.priv,
                    service_ids: data.user.service_ids,
                    company: companyInfo,
                    device_uuid: deviceUuid,
                    offline_token: data.offline_token,
                    pin_salt: Array.from(salt),
                    pin_verifier: verifier,
                    pin_iterations: iterations,
                    saved_at: Date.now()
                });
            });
        }).then(function () {
            if ('serviceWorker' in navigator) {
                return navigator.serviceWorker.register(base_url + '/service-worker.js')
                    .then(function () { return navigator.serviceWorker.ready; })
                    .catch(function () { return null; });
            }
            return null;
        }).then(function () {
            return Promise.all([cacheSetupData(), cachePages()]);
        }).then(function () {
            setStatus('Offline setup complete. Is operator ka PIN aur billing data is PC/browser me save hai. Doosre operator ko bhi online login karke apna setup karna hoga. Offline bill OFF number ke saath print hoga aur net aate hi sync hoga.', 'success');
            document.getElementById('offlinePin').value = '';
            document.getElementById('offlinePinConfirm').value = '';
        }).catch(function (error) {
            setStatus(error.message || 'Offline setup nahi ho saka.', 'danger');
        }).finally(function () {
            setupButton.disabled = false;
        });
    });

    refreshQueueList();
    window.addEventListener('aadhya-offline-bill-queued', refreshQueueList);
    window.addEventListener('aadhya-offline-bill-synced', refreshQueueList);
    window.addEventListener('online', function () { setTimeout(refreshQueueList, 1500); });
})();
</script>
@endsection
