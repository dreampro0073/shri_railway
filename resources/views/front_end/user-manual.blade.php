<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Cloakroom Software - User Manual</title>
<link rel="stylesheet" href="{{ asset('assets/user-manual/css/user-manual.css') }}"><script src="{{ asset('assets/user-manual/js/user-manual.js') }}" defer></script>
</head>
<body>
<div class="topbar"><div class="brand"><span>Cloakroom</span> Software User Manual</div><button class="print-btn" id="printManualBtn">Print / Save as PDF</button></div>
<div class="layout">
<nav>
  <h3>Contents</h3>
  <a href="#overview">Overview</a><a href="#login">1. Login</a><a href="#dashboard">2. Dashboard</a><a href="#cloakroom">3. Cloakroom Entry</a><a href="#all">4. Cloakrooms All</a><a href="#export">5. Export Cloakroom</a><a href="#shift">6. Shift Status</a><a href="#users">7. Users</a><a href="#add-user">8. Add User</a><a href="#buttons">Common Buttons</a><a href="#workflow">Recommended Workflow</a><a href="#support">Support Notes</a>
</nav>
<main>
  <div class="hero" id="overview">
    <h1>Cloakroom Software – User Manual</h1>
    <p>A simple step-by-step guide for daily operation of the Delhi Cloakroom system.</p>
    <div class="notice"><strong>Purpose:</strong> This manual explains login, baggage entry, searching, checkout, printing, shift collection, exporting data, and user management using the current software screens.</div>
  </div>
  <section id="login">
      <div class="section-head"><span class="badge">1</span><h2>Login to the Application</h2></div>
      <div class="section-body">
        <ol class="steps"><li>Open the Cloakroom application in your browser.</li><li>Enter your registered <strong>Email Address</strong>.</li><li>Enter your <strong>Password</strong>.</li><li>Click <strong>Login</strong> to continue.</li><li>After successful login, you will be redirected to the Dashboard.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/login.png') }}" alt="Login to the Application screenshot"><div class="caption">Screenshot: Login to the Application</div></div>
      </div>
    </section><section id="dashboard">
      <div class="section-head"><span class="badge">2</span><h2>Dashboard</h2></div>
      <div class="section-body">
        <ol class="steps"><li>The Dashboard provides a quick summary of cloakroom activity.</li><li>The <strong>Total Bag</strong> card shows the current bag count.</li><li>Use the left menu to open Cloakrooms, Cloakrooms All, Export Cloakroom, Shift Status, Users, or Logout.</li><li>The cloakroom selector in the top-right shows the currently selected cloakroom.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/dashboard.png') }}" alt="Dashboard screenshot"><div class="caption">Screenshot: Dashboard</div></div>
      </div>
    </section><section id="cloakroom">
      <div class="section-head"><span class="badge">3</span><h2>Add / Manage Cloakroom Entry</h2></div>
      <div class="section-body">
        <ol class="steps"><li>Open <strong>Cloakrooms</strong> from the left menu.</li><li>Click <strong>Add</strong> to open the Cloakroom form.</li><li>Fill in Name, Token Number, Mobile No., No. of Bag, PNR/UID, Time Duration, Pay Type, Paid Amount, and Remarks as applicable.</li><li>Click <strong>Submit</strong> to save the entry.</li><li>Use <strong>Checkout</strong> or <strong>Checkout WP</strong> when a customer collects the baggage, according to your workflow.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/cloakroom-entry.png') }}" alt="Add / Manage Cloakroom Entry screenshot"><div class="caption">Screenshot: Add / Manage Cloakroom Entry</div></div>
      </div>
    </section><section id="all">
      <div class="section-head"><span class="badge">4</span><h2>Cloakrooms All</h2></div>
      <div class="section-body">
        <ol class="steps"><li>Use this screen to view, search, and manage <strong>all cloakroom entries</strong>.</li><li>This list includes both <strong>currently checked-in entries</strong> and <strong>already checked-out entries</strong>, so you can review the complete cloakroom record from one screen.</li><li>You can search using <strong>Slip ID, Name, Mobile, or PNR</strong>.</li><li>The table displays check-in validity, bag count, PNR, payment type, total amount, checkout status, and identity number.</li><li>Use the <strong>Checkout Status</strong> column to identify whether an entry is still checked in or has already been checked out.</li><li>Use <strong>Print</strong> for an individual slip or <strong>Print Begs</strong> for the available printing action.</li><li>Click <strong>Add</strong> to create a new cloakroom record.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/cloakrooms-all.png') }}" alt="Cloakrooms All screenshot"><div class="caption">Screenshot: Cloakrooms All</div></div>
      </div>
    </section><section id="export">
      <div class="section-head"><span class="badge">5</span><h2>Export Cloakroom Data</h2></div>
      <div class="section-body">
        <ol class="steps"><li>Open <strong>Export Cloakroom</strong> from the left menu.</li><li>Select the <strong>From Date</strong> and <strong>To Date</strong>.</li><li>Click <strong>Search</strong>.</li><li>Use the generated results/export option provided by the system to download the report.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/export-cloakroom.png') }}" alt="Export Cloakroom Data screenshot"><div class="caption">Screenshot: Export Cloakroom Data</div></div>
      </div>
    </section><section id="shift">
      <div class="section-head"><span class="badge">6</span><h2>Shift Status / Shift Collection</h2></div>
      <div class="section-body">
        <div class="notice"><strong>User access:</strong> The system has two types of users: <strong>Admin</strong> and <strong>Staff</strong>. An Admin can view the overall shift collection as well as the collection of any individual staff member. A Staff user can view <strong>only their own shift status and collection</strong>.</div><ol class="steps"><li>Open <strong>Shift Status</strong>.</li><li>If an <strong>Admin</strong> is logged in, the screen shows the <strong>overall collection for the selected date</strong> by default, combining the collection made by all staff/users.</li><li>An Admin can check the collection of a <strong>particular staff member</strong> by selecting that staff/user from the <strong>User</strong> dropdown and applying the filter.</li><li>If a <strong>Staff</strong> user is logged in, only that staff member's own shift collection/status is shown. They cannot view the collection of other staff members.</li><li>You can also select the required date and cloakroom, then click <strong>Search</strong> to load the relevant collection details available for your user role.</li><li>The report shows <strong>Last Hour</strong> and <strong>Shift Collection</strong>, separated by UPI, Cash, and Total.</li><li>Click <strong>Print</strong> to print the shift collection report.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/shift-status.png') }}" alt="Shift Status / Shift Collection screenshot"><div class="caption">Screenshot: Shift Status / Shift Collection</div></div>
      </div>
    </section><section id="users">
      <div class="section-head"><span class="badge">7</span><h2>Manage Users</h2></div>
      <div class="section-body">
        <div class="notice"><strong>User Types:</strong> There are two types of users in the system: <strong>Admin</strong> and <strong>Staff</strong>. Admin users have access to overall shift collection information and can filter collection staff-wise. Staff users can view only their own shift status/collection.</div><ol class="steps"><li>Open <strong>Users</strong> from the left menu.</li><li>Search users by Name or Mobile number.</li><li>Click <strong>Add</strong> to create a new user.</li><li>Click <strong>Edit</strong> to update an existing user record.</li><li>The Status column shows whether the user is currently Active.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/users.png') }}" alt="Manage Users screenshot"><div class="caption">Screenshot: Manage Users</div></div>
      </div>
    </section><section id="add-user">
      <div class="section-head"><span class="badge">8</span><h2>Add New User</h2></div>
      <div class="section-body">
        <ol class="steps"><li>From the Users screen, click <strong>Add</strong>.</li><li>Enter Name, Mobile No., Email/Username, Password, and Confirm Password.</li><li>Make sure Password and Confirm Password match.</li><li>Click <strong>Submit</strong> to save the new user.</li></ol>
        <div class="screen"><img src="{{ asset('assets/user-manual/images/add-user.png') }}" alt="Add New User screenshot"><div class="caption">Screenshot: Add New User</div></div>
      </div>
    </section>
  <section id="buttons"><div class="section-head"><span class="badge">✓</span><h2>Common Buttons & Actions</h2></div><div class="section-body">
    <div class="grid">
      <div class="card"><h4>Search</h4><p>Loads records according to the filters entered on the screen.</p></div>
      <div class="card"><h4>Clear</h4><p>Clears the current search filters and resets the form.</p></div>
      <div class="card"><h4>Add</h4><p>Opens a form to create a new cloakroom record or user.</p></div>
      <div class="card"><h4>Edit</h4><p>Opens the selected record so it can be updated.</p></div>
      <div class="card"><h4>Print</h4><p>Prints the relevant slip, bag information, or report.</p></div>
      <div class="card"><h4>Logout</h4><p>Ends the current session. Always logout after completing work.</p></div>
    </div>
  </div></section>
  <section id="workflow"><div class="section-head"><span class="badge">→</span><h2>Recommended Daily Workflow</h2></div><div class="section-body">
    <table><thead><tr><th>Step</th><th>Action</th><th>Screen</th></tr></thead><tbody>
      <tr><td>1</td><td>Login using the assigned account.</td><td>Login</td></tr>
      <tr><td>2</td><td>Confirm the selected cloakroom and check the Dashboard.</td><td>Dashboard</td></tr>
      <tr><td>3</td><td>Create a cloakroom entry when baggage is received.</td><td>Cloakrooms</td></tr>
      <tr><td>4</td><td>Search for a customer/slip when baggage is to be collected.</td><td>Cloakrooms All</td></tr>
      <tr><td>5</td><td>Complete checkout and print the required slip.</td><td>Cloakrooms All</td></tr>
      <tr><td>6</td><td>At shift end, review UPI/Cash collection and print the summary.</td><td>Shift Status</td></tr>
      <tr><td>7</td><td>Export date-wise data when reporting is required.</td><td>Export Cloakroom</td></tr>
      <tr><td>8</td><td>Logout before leaving the terminal.</td><td>Logout</td></tr>
    </tbody></table>
  </div></section>
  <section id="support"><div class="section-head"><span class="badge">!</span><h2>Important Usage Notes</h2></div><div class="section-body">
    <div class="tip"><strong>Data accuracy:</strong> Verify mobile number, bag count, PNR/UID, payment type, amount, and duration before submitting a cloakroom entry.</div>
    <div class="tip"><strong>Checkout:</strong> Search and confirm the correct customer/slip before marking an entry as checked out.</div>
    <div class="tip"><strong>Shift closing:</strong> Match Cash and UPI totals with the Shift Status report before closing the shift.</div>
    <div class="tip warn"><strong>Account security:</strong> Do not share passwords. Use individual user accounts where possible and logout after use.</div>
  </div></section>
  <div class="footer">Cloakroom Software User Manual • Prepared from the current application screens • 28 September 2026</div>
</main></div></body></html>