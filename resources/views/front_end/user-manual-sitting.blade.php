<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sitting Software - User Manual</title>
    <link rel="stylesheet" href="{{ url('assets/user-manual-sitting/css/user-manual-sitting.css') }}">
    <script src="{{ url('assets/user-manual-sitting/js/user-manual-sitting.js') }}" defer></script>
</head>
<body class="sitting-manual">
    <div class="topbar">
        <div class="brand"><span>Sitting</span> Software User Manual</div>
        <button type="button" class="print-btn" id="printManualBtn">Print / Save as PDF</button>
    </div>

    <div class="layout">
        <nav aria-label="Manual contents">
            <h3>Contents</h3>
            <a href="#overview">Overview</a>
            <a href="#login">1. Login</a>
            <a href="#dashboard">2. Dashboard</a>
            <a href="#sitting">3. Sitting</a>
            <a href="#massage">4. Massage</a>
            <a href="#current-shift">5. Current Shift</a>
            <a href="#users">6. Users</a>
        </nav>

        <main>
            <div class="hero" id="overview">
                <h1>Sitting Software – User Manual</h1>
                <p>A simple step-by-step guide for daily operation of the Sitting system.</p>
                <div class="notice">
                    <strong>Purpose:</strong> This manual covers Login, Dashboard, Sitting, Massage,
                    Current Shift, and Users using the supplied application screens. The Current Shift
                    page is opened through <strong>Shift Status</strong> in the left menu.
                </div>
                <p class="screen-help">Select a screenshot to open it at full size. Records and totals shown are examples.</p>
            </div>

            <section id="login" aria-labelledby="login-title">
                <div class="section-head">
                    <span class="badge">1</span>
                    <h2 id="login-title">Login to the Application</h2>
                </div>
                <div class="section-body">
                    <p>Use this page to sign in to your account.</p>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/login.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Login screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/login.jpeg') }}" alt="Login page with Email Address, Password, a required-field message, and the Login button" width="1440" height="742">
                        </a>
                        <figcaption class="caption">Screenshot: Login to the Application</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>Open the application's Login page in your browser.</li>
                        <li>Enter your <strong>Email Address</strong>.</li>
                        <li>Enter your <strong>Password</strong>.</li>
                        <li>Click <strong>Login</strong> to continue to the Dashboard.</li>
                        <li>If <strong>This field is required.</strong> appears below Password, enter your password and click <strong>Login</strong> again.</li>
                    </ol>
                </div>
            </section>

            <section id="dashboard" aria-labelledby="dashboard-title">
                <div class="section-head">
                    <span class="badge">2</span>
                    <h2 id="dashboard-title">Dashboard</h2>
                </div>
                <div class="section-body">
                    <p>The Dashboard provides a quick summary of sitting availability.</p>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/dashboard.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Dashboard screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/dashboard.jpeg') }}" alt="Dashboard showing Total Sit 97, Booked Sit 93, and Available Sit 4" width="1438" height="669">
                        </a>
                        <figcaption class="caption">Screenshot: Dashboard</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>After login, view the Dashboard, or click <strong>Dashboard</strong> in the left menu.</li>
                        <li>Check <strong>Total Sit</strong> for the total number of seats.</li>
                        <li>Check <strong>Booked Sit</strong> for the booked seats and <strong>Available Sit</strong> for the remaining seats.</li>
                        <li>Use the left menu to open <strong>Sitting</strong>, <strong>Massage</strong>, <strong>Shift Status</strong>, or <strong>Users</strong>.</li>
                    </ol>
                </div>
            </section>

            <section id="sitting" aria-labelledby="sitting-title">
                <div class="section-head">
                    <span class="badge">3</span>
                    <h2 id="sitting-title">Manage Sitting Entries</h2>
                </div>
                <div class="section-body">
                    <p>Use Sitting to find entries, add a sitting record, review validity and payment details, and use the available row actions.</p>
                    <h3>Find and review an entry</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/sitting.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Sitting screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/sitting.jpeg') }}" alt="Sitting page with Barcodevalue, search filters, validity and payment columns, and Checkout WP, Edit, and Print actions" width="1422" height="664">
                        </a>
                        <figcaption class="caption">Screenshot: Sitting search and entry list</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>Open <strong>Sitting</strong> from the left menu.</li>
                        <li>Enter the available details in <strong>Slip ID</strong>, <strong>Name</strong>, <strong>Mobile</strong>, or <strong>PNR</strong>.</li>
                        <li>Use <strong>From Date</strong> and <strong>To Date</strong> for a date range, and <strong>Added By</strong> to select the person who added the entry. The screenshot shows <strong>All</strong>.</li>
                        <li>Click <strong>Search</strong> to apply the filters. Click <strong>Clear</strong> to reset them.</li>
                        <li>Review <strong>Slip ID</strong>, <strong>Name</strong>, <strong>Mobile No</strong>, <strong>PNR</strong>, <strong>Date</strong>, <strong>Validity</strong>, <strong>Paid Amount</strong>, and <strong>Pay Type</strong> in the table.</li>
                        <li>Check the <strong>Validity</strong> time before acting. Pink/red rows indicate entries whose validity has expired and which have not been checked out.</li>
                        <li>For the correct row, use <strong>Edit</strong> to open the entry for changes, or <strong>Print</strong> to open its slip for printing.</li>
                        <li>When checkout without penalty is required, confirm the customer's <strong>Slip ID</strong> and details, then click that row's <strong>Checkout WP</strong>.</li>
                    </ol>
                    <div class="notice">
                        <strong>Barcodevalue:</strong> Entering or scanning a slip barcode and pressing Enter
                        starts checkout. Confirm the correct slip before using this field.
                    </div>

                    <h3>Add a sitting entry</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/sitting-add.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Sitting Add screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/sitting-add.jpeg') }}" alt="Sitting Add form with customer details, adult, children and Baby/Staff counts, Hour Occ, Cash or UPI, Valid Upto, Paid Amount, Remarks, and Submit" width="888" height="581">
                        </a>
                        <figcaption class="caption">Screenshot: Sitting Add</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>On Sitting, click <strong>Add</strong> to open <strong>Sitting Add</strong>.</li>
                        <li>Enter <strong>Name</strong>, <strong>Mobile No.</strong>, and <strong>PNR/UID</strong>.</li>
                        <li>Enter the counts in <strong>No of Adults</strong>, <strong>No of children</strong>, and <strong>Baby/Staff</strong>.</li>
                        <li>Select the duration in <strong>Hour Occ</strong>.</li>
                        <li>Choose <strong>Cash</strong> or <strong>UPI</strong> under <strong>Pay Type</strong>.</li>
                        <li>Review the displayed <strong>Valid Upto</strong> and <strong>Paid Amount</strong>, then enter any <strong>Remarks</strong>.</li>
                        <li>Check the details and click <strong>Submit</strong> to save. Use the <strong>×</strong> at the top-right to close the form.</li>
                    </ol>
                </div>
            </section>

            <section id="massage" aria-labelledby="massage-title">
                <div class="section-head">
                    <span class="badge">4</span>
                    <h2 id="massage-title">Manage Massage Bills</h2>
                </div>
                <div class="section-body">
                    <p>Use Massage to find bills, add a massage entry, and print a bill.</p>
                    <h3>Find and print a bill</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/massage.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Massage screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/massage.jpeg') }}" alt="Massage bill list with Bill Number search, Search, Clear, Add, and a Print button for each bill" width="1438" height="657">
                        </a>
                        <figcaption class="caption">Screenshot: Massage bills</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>Open <strong>Massage</strong> from the left menu.</li>
                        <li>Enter a bill number in <strong>Bill Number</strong> and click <strong>Search</strong>.</li>
                        <li>Click <strong>Clear</strong> to reset the bill number filter.</li>
                        <li>Review <strong>Bill no</strong>, <strong>Name</strong>, <strong>Date</strong>, <strong>Pay Type</strong>, and <strong>Total Amount</strong> in the table.</li>
                        <li>Click <strong>Print</strong> beside the required bill to open it for printing.</li>
                    </ol>

                    <h3>Add a massage entry</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/massage-add.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Massage Add screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/massage-add.jpeg') }}" alt="Massage Add form with Name, Pay Type, Time Period, No of Person, Paid Amount, Remarks, and Submit" width="873" height="413">
                        </a>
                        <figcaption class="caption">Screenshot: Massage Add</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>On Massage, click <strong>Add</strong>.</li>
                        <li>Enter the customer's <strong>Name</strong>.</li>
                        <li>Select <strong>Pay Type</strong> and <strong>Time Period</strong>.</li>
                        <li>Set <strong>No of Person</strong> and review the displayed <strong>Paid Amount</strong>. The amount is calculated from the selected time period and number of people.</li>
                        <li>Enter any <strong>Remarks</strong>.</li>
                        <li>Check the details and click <strong>Submit</strong> to save. Use the <strong>×</strong> at the top-right to close the form.</li>
                    </ol>
                </div>
            </section>

            <section id="current-shift" aria-labelledby="current-shift-title">
                <div class="section-head">
                    <span class="badge">5</span>
                    <h2 id="current-shift-title">Current Shift / Shift Status</h2>
                </div>
                <div class="section-body">
                    <p>Use this page to review the collection report for the selected date and print the summary.</p>
                    <h3>Review the collection</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/current-shift.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Current Shift screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/current-shift.jpeg') }}" alt="Shift Status page with Total Shift Collection, a date field, two Select dropdowns, Search, Clear, Print, and collection totals" width="1440" height="659">
                        </a>
                        <figcaption class="caption">Screenshot: Current Shift / Shift Status</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>Open <strong>Shift Status</strong> from the left menu to access Current Shift.</li>
                        <li>Check the date beside <strong>Total Shift Collection</strong>.</li>
                        <li>Enter the report date in the <strong>DD-MM-YYYY</strong> field. Use the two <strong>Select</strong> dropdowns for <strong>date</strong> and <strong>user</strong>, from left to right, then click <strong>Search</strong>.</li>
                        <li>Read <strong>Last Hour</strong> and <strong>Shift Collection</strong>. Each group separates <strong>UPI</strong>, <strong>Cash</strong>, and <strong>Total</strong>.</li>
                        <li>Review the <strong>Sitting</strong>, <strong>Massage</strong>, and <strong>Locker</strong> rows, then check <strong>Grand Total</strong> at the bottom.</li>
                        <li>Click <strong>Clear</strong> to reset the report filters.</li>
                    </ol>

                    <h3>Print or save the report</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/current-shift-print.jpeg') }}" target="_blank" rel="noopener" aria-label="Open shift report print screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/current-shift-print.jpeg') }}" alt="Shift report print preview showing Save as PDF, Pages All, Layout Portrait, Cancel, and Save" width="1439" height="738">
                        </a>
                        <figcaption class="caption">Screenshot: Shift collection report – Print / Save as PDF</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>After reviewing the report, click <strong>Print</strong> at the top-right.</li>
                        <li>In the browser print window, choose a printer under <strong>Destination</strong>, or select <strong>Save as PDF</strong> as shown.</li>
                        <li>Check <strong>Pages</strong> and <strong>Layout</strong>. The screenshot shows <strong>All</strong> and <strong>Portrait</strong>.</li>
                        <li>For a PDF, click <strong>Save</strong>. Click <strong>Cancel</strong> to close the print window.</li>
                    </ol>
                </div>
            </section>

            <section id="users" aria-labelledby="users-title">
                <div class="section-head">
                    <span class="badge">6</span>
                    <h2 id="users-title">Manage Users</h2>
                </div>
                <div class="section-body">
                    <p>Use Users to find user accounts, review their status, add a user, or edit an existing record.</p>
                    <h3>Find and review a user</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/users.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Users screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/users.jpeg') }}" alt="Users list with Name and Mobile search, Add, Edit, and Active or Inactive statuses and row buttons" width="1439" height="642">
                        </a>
                        <figcaption class="caption">Screenshot: Users search, status, and actions</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>Open <strong>Users</strong> from the left menu.</li>
                        <li>Enter <strong>Name</strong> or <strong>Mobile</strong> and click <strong>Search</strong>.</li>
                        <li>Click <strong>Clear</strong> to reset the search filters.</li>
                        <li>Review <strong>Name</strong>, <strong>Mobile</strong>, and <strong>Email</strong>. The column labelled <strong>Statue</strong> in the screenshot shows <strong>Active</strong> or <strong>Inactive</strong>.</li>
                        <li>Click <strong>Edit</strong> beside the required user to open their record, update the editable details, and click <strong>Submit</strong>.</li>
                        <li>Where shown, the row's <strong>Active</strong> button is the action for an inactive account, and <strong>Inactive</strong> is the action for an active account. Check the correct user before choosing a status action.</li>
                    </ol>

                    <h3>Add a new user</h3>
                    <figure class="screen">
                        <a class="screen-link" href="{{ url('assets/user-manual-sitting/images/users-add.jpeg') }}" target="_blank" rel="noopener" aria-label="Open Users Add screenshot at full size in a new tab">
                            <img src="{{ url('assets/user-manual-sitting/images/users-add.jpeg') }}" alt="Users form with Name, Mobile No., Email/Username, Password, Confirm Password., and Submit" width="892" height="387">
                        </a>
                        <figcaption class="caption">Screenshot: Add a new user</figcaption>
                    </figure>
                    <ol class="steps">
                        <li>On Users, click <strong>Add</strong> to open the <strong>Users</strong> form.</li>
                        <li>Enter <strong>Name</strong>, <strong>Mobile No.</strong>, and <strong>Email/Username</strong>.</li>
                        <li>Enter <strong>Password</strong> and repeat it in <strong>Confirm Password.</strong></li>
                        <li>Make sure both passwords match, then click <strong>Submit</strong> to save the user.</li>
                        <li>Use the <strong>×</strong> at the top-right to close the form.</li>
                    </ol>
                </div>
            </section>

            <div class="footer">Sitting Software User Manual • Prepared from the supplied application screens • 4 November 2026</div>
        </main>
    </div>
</body>
</html>
