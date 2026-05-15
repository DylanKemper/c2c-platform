<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — Lootly Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-md-none alert alert-warning text-center p-3 mb-0 rounded-0">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">
        <?php include 'partials/sidebar.php'; ?>

        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Reports</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <div class="filter-bar">
                        <input type="text" placeholder="Search target or reporter" class="search-input">
                        <select class="filter-select">
                            <option value="">All types</option>
                            <option value="listing">Listing</option>
                            <option value="user">User</option>
                            <option value="transaction">Transaction</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All statuses</option>
                            <option value="open">Open</option>
                            <option value="under_review">Under Review</option>
                            <option value="resolved">Resolved</option>
                        </select>
                        <button class="btn-platform btn-primary-solid">Filter</button>
                        <button class="btn-platform btn-outline">Clear</button>
                    </div>

                    <!-- Reports table -->
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th style="width:5%">ID</th>
                                <th style="width:10%">Type</th>
                                <th style="width:20%">Target</th>
                                <th style="width:25%">Reason</th>
                                <th style="width:14%">Reported By</th>
                                <th style="width:12%">Date</th>
                                <th style="width:8%">Status</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Report type: listing -->
                            <tr class="clickable">
                                <td>1</td>
                                <td><span class="badge badge--listing">Listing</span></td>
                                <td>Canon EOS 5D Mark IV</td>
                                <td class="reason-cell">Item does not match description — seller...</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="user-avatar">MT</span> mike_t
                                    </div>
                                </td>
                                <td>12 Jan 2025</td>
                                <td><span class="badge badge--danger">Open</span></td>
                                <td><a href="report-detail.php?id=1" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <!-- Report type: user -->
                            <tr class="clickable">
                                <td>2</td>
                                <td><span class="badge badge--user">User</span></td>
                                <td>@scammer99</td>
                                <td class="reason-cell">Harassment and threatening messages sent...</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="user-avatar">LM</span> lisa_m
                                    </div>
                                </td>
                                <td>3 Feb 2025</td>
                                <td><span class="badge badge--warning">Under Review</span></td>
                                <td><a href="report-detail.php?id=2" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <!-- Report type: transaction -->
                            <tr class="clickable">
                                <td>3</td>
                                <td><span class="badge badge--transaction">Transaction</span></td>
                                <td>#TXN8803</td>
                                <td class="reason-cell">Payment released but item never delivered...</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="user-avatar">SD</span> seller_dan
                                    </div>
                                </td>
                                <td>19 Mar 2025</td>
                                <td><span class="badge badge--danger">Open</span></td>
                                <td><a href="report-detail.php?id=3" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <!-- Report type: listing, resolved -->
                            <tr class="clickable">
                                <td>4</td>
                                <td><span class="badge badge--listing">Listing</span></td>
                                <td>Apple MacBook Pro 16"</td>
                                <td class="reason-cell">Suspected counterfeit — serial number did...</td>
                                <td>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        <span class="user-avatar">PK</span> priya_k
                                    </div>
                                </td>
                                <td>1 Apr 2025</td>
                                <td><span class="badge badge--success">Resolved</span></td>
                                <td><a href="report-detail.php?id=4" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>