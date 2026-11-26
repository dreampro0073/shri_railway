var CACHE_NAME = 'aadhya-offline-v2';
var APP_SCOPE = new URL(self.registration.scope);
var OFFLINE_PAGE = new URL('offline.html', APP_SCOPE).href;
var OFFLINE_LOGIN = new URL('login', APP_SCOPE).href;

function appPath(pathname) {
    return pathname.indexOf(APP_SCOPE.pathname) === 0
        ? pathname.slice(APP_SCOPE.pathname.length - 1)
        : pathname;
}

function getQueuedBill(billUuid) {
    return new Promise(function (resolve) {
        var request = indexedDB.open('aadhyaOfflineBilling', 2);
        request.onsuccess = function () {
            var db = request.result;
            if (!db.objectStoreNames.contains('bill_queue')) {
                db.close();
                resolve(null);
                return;
            }
            var transaction = db.transaction('bill_queue', 'readonly');
            var billRequest = transaction.objectStore('bill_queue').get(billUuid);
            billRequest.onsuccess = function () {
                var bill = billRequest.result || null;
                db.close();
                resolve(bill);
            };
            billRequest.onerror = function () { db.close(); resolve(null); };
        };
        request.onerror = function () { resolve(null); };
    });
}

function escapeHtml(value) {
    return String(value == null ? '' : value).replace(/[&<>"']/g, function (character) {
        return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[character];
    });
}

function renderQueuedBill(pathname) {
    var billUuid = pathname.split('/').pop();
    if (!/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i.test(billUuid)) {
        return Promise.resolve(null);
    }

    return getQueuedBill(billUuid).then(function (bill) {
        if (!bill) return null;
        var data = bill.data || {};
        var company = bill.company || {};
        var labels = {
            sitting: 'SITTING',
            recliners: 'RECLINER',
            'cloak-rooms': 'CLOAK ROOM',
            'daily-entries': 'CANTEEN',
            massage: 'MASSAGE',
            locker: 'LOCKER',
            rooms: 'ROOM',
            scanning: 'SCANNING',
            rest: 'REST'
        };
        var service = labels[bill.service] || 'BILL';
        var date = new Date(bill.created_at || Date.now()).toLocaleString('en-IN');
        var syncLabel = bill.status === 'synced' ? 'SYNCED' : 'PENDING SYNC';
        var fields = [
            ['Name', data.name],
            ['Mobile', data.mobile || data.mobile_no],
            ['PNR / UID', data.pnr_uid],
            ['Train No', data.train_no],
            ['No. of Bags', data.no_of_bag],
            ['Total Bags', data.total_bag],
            ['No. of Days', data.no_of_day],
            ['No. of Lockers', Array.isArray(data.sl_lockers) ? data.sl_lockers.length : ''],
            ['No. of Rooms', data.no_of_rooms],
            ['No. of People', data.no_of_people || data.no_of_person],
            ['No. of Adults', data.no_of_adults],
            ['No. of Children', data.no_of_children],
            ['No. of Items', data.no_of_item],
            ['No. of Hours', data.no_of_hours || data.hours_occ],
            ['Time Period', data.time_period ? data.time_period + ' Min' : ''],
            ['Check In', data.check_in],
            ['Valid Up To', data.check_out],
            ['Payment', Number(data.pay_type) === 2 ? 'UPI' : (data.pay_type ? 'Cash' : '')],
            ['Total Amount', data.total_amount],
            ['Paid Amount', data.paid_amount],
            ['Discount', data.discount_amount],
            ['Balance', data.balance_amount],
            ['Remarks', data.remarks]
        ].filter(function (field) { return field[1] !== undefined && field[1] !== null && field[1] !== ''; });

        var details = fields.map(function (field) {
            return '<div class="row"><span>' + escapeHtml(field[0]) + '</span><b>' + escapeHtml(field[1]) + '</b></div>';
        }).join('');
        if (Array.isArray(data.products) && data.products.length) {
            details += '<h5>Items</h5>' + data.products.map(function (item) {
                var itemName = item.item_name || item.name || ('Item ' + (item.canteen_item_id || ''));
                return '<div class="row"><span>' + escapeHtml(itemName) + ' × ' + escapeHtml(item.quantity) + '</span><b>' + escapeHtml(item.paid_amount || (Number(item.price || 0) * Number(item.quantity || 0))) + '</b></div>';
            }).join('');
        }
        var companyName = company.print_name && company.print_name !== '0' ? company.print_name : company.name;
        var html = '<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">' +
            '<title>' + escapeHtml(service) + ' Bill</title><style>@page{margin:0}body{margin:0;font-family:Arial,sans-serif;color:#000}.main{width:300px;padding:5px;box-sizing:border-box}h4,h5,p{text-align:center;margin:2px 0;font-size:12px}h4{font-size:14px}.row{display:flex;justify-content:space-between;gap:8px;padding:2px 0;font-size:12px;font-weight:bold}.row b{text-align:right}.offline{text-align:center;border-top:1px dashed #000;border-bottom:1px dashed #000;padding:4px;margin:8px 0;font-weight:bold;font-size:11px}.small{text-align:center;font-size:10px;margin-top:10px}</style></head><body>' +
            '<div class="main" id="printableArea"><h4>' + escapeHtml(companyName || 'Aadhyasri') + '</h4>' +
            '<p>' + escapeHtml(company.address || '') + '</p><h5>' + escapeHtml(company.gst || '') + '</h5>' +
            '<h5>' + escapeHtml(service) + '</h5><div class="offline">OFFLINE BILL · ' + escapeHtml(syncLabel) + '<br>Bill No: ' + escapeHtml(bill.bill_number) + '</div>' +
            '<div class="row"><span>Date</span><b>' + escapeHtml(date) + '</b></div>' + details +
            '<div class="row"><span>Operator</span><b>' + escapeHtml(bill.operator_name || '') + '</b></div>' +
            (bill.status === 'synced' && bill.response_data ? '<div class="row"><span>Server Record ID</span><b>' + escapeHtml(bill.response_data.id || bill.response_data.entry_id || bill.response_data.print_id || '') + '</b></div>' : '') +
            '<p class="small">Saved on this PC. Online sync updates the server bill record.</p></div>' +
            '<script>window.onload=function(){window.print();};<\/script></body></html>';
        return new Response(html, { headers: { 'Content-Type': 'text/html; charset=utf-8' } });
    });
}

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            return cache.add(OFFLINE_PAGE);
        }).then(function () {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keys) {
            return Promise.all(keys.map(function (key) {
                if (key.indexOf('aadhya-offline-') === 0 && key !== CACHE_NAME) {
                    return caches.delete(key);
                }
            }));
        }).then(function () {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function (event) {
    var request = event.request;
    if (request.method !== 'GET') return;

    var url = new URL(request.url);
    if (url.origin !== self.location.origin) return;

    if (request.mode === 'navigate') {
        var navigationPath = appPath(url.pathname);
        if (navigationPath.indexOf('/admin/') === 0 && navigationPath.indexOf('/print') !== -1) {
            event.respondWith(renderQueuedBill(navigationPath).then(function (billPage) {
                if (billPage) return billPage;
                return fetch(request).catch(function () {
                    return caches.match(request, { ignoreSearch: true }).then(function (cached) {
                        if (cached) return cached;
                        return caches.match(OFFLINE_LOGIN).then(function (loginPage) {
                            return loginPage || caches.match(OFFLINE_PAGE);
                        });
                    });
                });
            }));
            return;
        }
        event.respondWith(
            fetch(request).then(function (response) {
                var path = appPath(url.pathname);
                var responsePath = response && response.url ? appPath(new URL(response.url).pathname) : path;
                if (response && response.ok && responsePath === path && (path.indexOf('/admin') === 0 || path === '/login')) {
                    var copy = response.clone();
                    caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(request, copy);
                    });
                }
                return response;
            }).catch(function () {
                var path = appPath(url.pathname);
                return caches.match(request, { ignoreSearch: true }).then(function (cached) {
                    if (cached) return cached;
                    return caches.match(OFFLINE_LOGIN).then(function (loginPage) {
                        return loginPage || caches.match(OFFLINE_PAGE);
                    });
                });
            })
        );
        return;
    }

    var assetPath = appPath(url.pathname);
    if (assetPath.indexOf('/assets/') === 0 || assetPath.indexOf('/assets1/') === 0 || assetPath.indexOf('/plugins/') === 0 || assetPath.indexOf('/front-end/') === 0) {
        event.respondWith(
            caches.match(request).then(function (cached) {
                var network = fetch(request).then(function (response) {
                    if (response && response.ok) {
                        var copy = response.clone();
                        caches.open(CACHE_NAME).then(function (cache) {
                            cache.put(request, copy);
                        });
                    }
                    return response;
                }).catch(function (error) {
                    if (cached) return cached;
                    throw error;
                });
                return cached || network;
            })
        );
    }
});
