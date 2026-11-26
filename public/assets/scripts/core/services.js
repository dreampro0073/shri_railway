function offlineDbOpen() {
    return new Promise(function(resolve, reject) {
        if (!window.indexedDB) {
            reject(new Error('IndexedDB is not available'));
            return;
        }
        var request = indexedDB.open('aadhyaOfflineBilling', 2);
        request.onupgradeneeded = function() {
            var db = request.result;
            if (!db.objectStoreNames.contains('operators')) db.createObjectStore('operators', { keyPath: 'user_id' });
            if (!db.objectStoreNames.contains('api_cache')) db.createObjectStore('api_cache', { keyPath: 'cache_key' });
            if (!db.objectStoreNames.contains('bill_queue')) db.createObjectStore('bill_queue', { keyPath: 'bill_uuid' });
        };
        request.onsuccess = function() { resolve(request.result); };
        request.onerror = function() { reject(request.error); };
    });
}

function offlineCacheKey(route, data) {
    var offlineSession = window.aadhyaOfflineSession || null;
    var userId = offlineSession ? offlineSession.user_id : (window.auth_user_id || 0);
    return String(userId) + '|' + route + '|' + JSON.stringify(data || {});
}

function offlineCacheUserId() {
    var offlineSession = window.aadhyaOfflineSession || null;
    return Number(offlineSession ? offlineSession.user_id : (window.auth_user_id || 0));
}

function saveOfflineApiCache(route, data, responseData) {
    return offlineDbOpen().then(function(db) {
        return new Promise(function(resolve, reject) {
            var transaction = db.transaction('api_cache', 'readwrite');
            transaction.objectStore('api_cache').put({
                cache_key: offlineCacheKey(route, data),
                route: route,
                user_id: offlineCacheUserId(),
                request_data: data || {},
                data: responseData,
                saved_at: Date.now()
            });
            transaction.oncomplete = function() { db.close(); resolve(); };
            transaction.onerror = function() { db.close(); reject(transaction.error); };
        });
    });
}

function getOfflineApiCache(route, data) {
    return offlineDbOpen().then(function(db) {
        return new Promise(function(resolve) {
            var transaction = db.transaction('api_cache', 'readonly');
            var request = transaction.objectStore('api_cache').get(offlineCacheKey(route, data));
            request.onsuccess = function() {
                var result = request.result;
                if (result) {
                    db.close();
                    resolve(result.data);
                    return;
                }
                var allRequest = transaction.objectStore('api_cache').getAll();
                allRequest.onsuccess = function() {
                    var matches = (allRequest.result || []).filter(function(item) {
                        return item.route === route && Number(item.user_id) === offlineCacheUserId();
                    }).sort(function(a, b) { return b.saved_at - a.saved_at; });
                    db.close();
                    resolve(matches.length ? matches[0].data : null);
                };
                allRequest.onerror = function() { db.close(); resolve(null); };
            };
            request.onerror = function() { db.close(); resolve(null); };
        });
    }).catch(function() { return null; });
}

function getOfflineOperator(userId) {
    var offlineSession = window.aadhyaOfflineSession || null;
    if (offlineSession && Number(offlineSession.user_id) === Number(userId)) {
        return Promise.resolve(offlineSession);
    }
    return offlineDbOpen().then(function(db) {
        return new Promise(function(resolve) {
            var transaction = db.transaction('operators', 'readonly');
            var request = transaction.objectStore('operators').get(Number(userId));
            request.onsuccess = function() { db.close(); resolve(request.result || null); };
            request.onerror = function() { db.close(); resolve(null); };
        });
    }).catch(function() { return null; });
}

function saveOfflineBill(bill) {
    return offlineDbOpen().then(function(db) {
        return new Promise(function(resolve, reject) {
            var transaction = db.transaction('bill_queue', 'readwrite');
            transaction.objectStore('bill_queue').put(bill);
            transaction.oncomplete = function() { db.close(); resolve(); };
            transaction.onerror = function() { db.close(); reject(transaction.error); };
        });
    });
}

function getOfflineBills() {
    return offlineDbOpen().then(function(db) {
        return new Promise(function(resolve) {
            var transaction = db.transaction('bill_queue', 'readonly');
            var request = transaction.objectStore('bill_queue').getAll();
            request.onsuccess = function() { db.close(); resolve(request.result || []); };
            request.onerror = function() { db.close(); resolve([]); };
        });
    }).catch(function() { return []; });
}

function updateOfflineBill(bill) {
    return saveOfflineBill(bill);
}

function isOfflineBillRoute(route) {
    return /^\/api\/(sitting|recliners|cloak-rooms|daily-entries|massage|locker|rooms|scanning|rest)\/(store|checkout-store)(\/\d+)?$/.test(route);
}

function makeOfflineUuid() {
    if (window.crypto && crypto.randomUUID) return crypto.randomUUID();
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0;
        return (c === 'x' ? r : (r & 3 | 8)).toString(16);
    });
}

function getOfflineServiceName(route) {
    var parts = route.split('/').filter(Boolean);
    return parts.length > 1 ? parts[1] : 'billing';
}

function syncOfflineBills() {
    if (!navigator.onLine || !window.fetch || !window.CSRF_TOKEN || window.aadhyaOfflineSyncRunning) return;
    window.aadhyaOfflineSyncRunning = true;
    getOfflineBills().then(function(bills) {
        return bills.filter(function(bill) { return bill.status === 'pending'; }).reduce(function(chain, bill) {
            return chain.then(function() {
                return fetch(base_url + bill.route, {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'apiToken': api_key,
                        'X-Offline-Id': bill.bill_uuid,
                        'X-Offline-Device': bill.device_uuid,
                        'X-Offline-User': String(bill.user_id),
                        'X-Offline-Token': bill.offline_token
                    },
                    body: JSON.stringify(bill.data)
                }).then(function(response) {
                    return response.json().then(function(result) {
                        if (!response.ok || !result || !result.success) {
                            var syncError = new Error((result && result.message) || 'Bill sync failed.');
                            syncError.permanent = !!(response.ok && result && result.success === false) || response.status === 409 || response.status === 422;
                            throw syncError;
                        }
                        bill.status = 'synced';
                        bill.synced_at = Date.now();
                        bill.response_data = result;
                        bill.last_error = '';
                        delete bill.offline_token;
                        return updateOfflineBill(bill).then(function() {
                            window.dispatchEvent(new CustomEvent('aadhya-offline-bill-synced', { detail: bill }));
                        });
                    });
                }).catch(function(error) {
                    bill.attempts = (bill.attempts || 0) + 1;
                    bill.last_error = error.message || 'Connection unavailable.';
                    if (error.permanent) bill.status = 'needs_review';
                    return updateOfflineBill(bill);
                });
            });
        }, Promise.resolve());
    }).finally(function() {
        window.aadhyaOfflineSyncRunning = false;
    });
}

window.addEventListener('online', function() {
    setTimeout(syncOfflineBills, 500);
});
setTimeout(syncOfflineBills, 1500);

app.service('DBService', function($http, $rootScope, $q){
    this.getCall = function(route){
        var promise = $http({
            method: 'GET',
            url: base_url + route,
            headers: {
                'apiToken': api_key
            }
        })
        .then(function(response) {
            console.log(response);
            if(response.status == 200){
                if(response.data.success){
                    return response.data;
                } else {
                    return response.data;
                }
            }
        });
        return promise;
    }
    this.postCall = function(data, route){
        var isBillWrite = isOfflineBillRoute(route);
        var activeUserId = window.aadhyaOfflineSession
            ? window.aadhyaOfflineSession.user_id
            : (window.auth_user_id || 0);

        return $q.when(getOfflineOperator(activeUserId)).then(function(operator) {
            var offlineUuid = isBillWrite && operator ? makeOfflineUuid() : '';
            var headers = { 'apiToken': api_key };
            if (offlineUuid) {
                headers['X-Offline-Id'] = offlineUuid;
                headers['X-Offline-Device'] = operator.device_uuid;
                headers['X-Offline-User'] = String(operator.user_id);
                headers['X-Offline-Token'] = operator.offline_token;
            }

            return $http({
                method: 'POST',
                url: base_url + route,
                data: data,
                headers: headers
            }).then(function(response) {
                if(response.status == 200){
                    saveOfflineApiCache(route, data, response.data).catch(function(){});
                    return response.data;
                }
            }, function(error) {
                if (isBillWrite && operator && (!navigator.onLine || error.status === 0)) {
                    var bill = {
                        bill_uuid: offlineUuid,
                        route: route,
                        service: getOfflineServiceName(route),
                        data: angular.copy(data || {}),
                        user_id: Number(operator.user_id),
                        client_id: Number(operator.client_id),
                        device_uuid: operator.device_uuid,
                        offline_token: operator.offline_token,
                        company: operator.company || {},
                        operator_name: operator.name,
                        bill_number: 'OFF-' + offlineUuid.replace(/-/g, '').slice(0, 16).toUpperCase(),
                        created_at: Date.now(),
                        status: 'pending',
                        attempts: 0,
                        last_error: ''
                    };
                    return saveOfflineBill(bill).then(function() {
                        window.dispatchEvent(new CustomEvent('aadhya-offline-bill-queued', { detail: bill }));
                        return {
                            success: true,
                            offline: true,
                            offline_uuid: offlineUuid,
                            id: offlineUuid,
                            entry_id: offlineUuid,
                            print_id: offlineUuid,
                            message: 'Bill saved offline and will sync when internet returns.'
                        };
                    });
                }

                if (!/\/(init|edit-init)(\/\d+)?$/.test(route)) {
                    return $q.reject(error);
                }
                return getOfflineApiCache(route, data).then(function(cachedData) {
                    if (cachedData) return cachedData;
                    return $q.reject(error);
                });
            });
        });
    }
});
