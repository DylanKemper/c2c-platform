<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions — Lootly Admin</title>
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
            <div class="page-heading">Transactions</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <div class="filter-bar">
                        <input type="text" placeholder="Search by ID, buyer or seller" class="search-input">
                        <select class="filter-select">
                            <option value="">All statuses</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="disputed">Disputed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                        <button class="btn-platform btn-primary-solid">Filter</button>
                        <button class="btn-platform btn-outline">Clear</button>
                    </div>

                    <!-- Transactions table -->
                    <table class="records-table">
                        <thead class="table-header">
                            <tr class="table-row">
                                <th style="width:10%">ID</th>
                                <th style="width:18%">Buyer</th>
                                <th style="width:18%">Seller</th>
                                <th style="width:24%">Listing</th>
                                <th style="width:10%">Amount</th>
                                <th style="width:10%">Date</th>
                                <th style="width:10%">Status</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody class="table-body">

                            <tr class="table-row clickable">
                                <td>#TXN8821</td>
                                <td><span class="avatar">JD</span> john_doe</td>
                                <td><span class="avatar">PK</span> priya_k</td>
                                <td>Sony WH-1000XM5 Headphones</td>
                                <td>R 4,200</td>
                                <td>12 Jan 2025</td>
                                <td><span class="status-badge badge-green">Completed</span></td>
                                <td><a href="transaction-detail.php?id=8821" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <tr class="table-row clickable">
                                <td>#TXN8803</td>
                                <td><span class="avatar">LK</span> lk_trades</td>
                                <td><span class="avatar">SD</span> seller_dan</td>
                                <td>Canon EOS 5D Mark IV</td>
                                <td>R 12,500</td>
                                <td>3 Feb 2025</td>
                                <td><span class="status-badge badge-red">Disputed</span></td>
                                <td><a href="transaction-detail.php?id=8803" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <tr class="table-row clickable">
                                <td>#TXN8790</td>
                                <td><span class="avatar">MT</span> mike_t</td>
                                <td><span class="avatar">LM</span> lisa_m</td>
                                <td>Apple MacBook Pro 16"</td>
                                <td>R 28,000</td>
                                <td>19 Mar 2025</td>
                                <td><span class="status-badge badge-amber">Pending</span></td>
                                <td><a href="transaction-detail.php?id=8790" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <tr class="table-row clickable">
                                <td>#TXN8775</td>
                                <td><span class="avatar">TP</span> the_pete</td>
                                <td><span class="avatar">JD</span> john_doe</td>
                                <td>Rode NT-USB Microphone</td>
                                <td>R 3,800</td>
                                <td>1 Apr 2025</td>
                                <td><span class="status-badge badge-green">Completed</span></td>
                                <td><a href="transaction-detail.php?id=8775" class="btn-platform btn-primary-solid view-btn">View</a></td>
                            </tr>

                            <tr class="table-row clickable">
                                <td>#TXN8761</td>
                                <td><span class="avatar">SM</span> sarah_m</td>
                                <td><span class="avatar">MT</span> mike_t</td>
                                <td>DJI Osmo Pocket 3</td>
                                <td>R 9,500</td>
                                <td>22 Apr 2025</td>
                                <td><span class="status-badge badge-amber">Refunded</span></td>
                                <td><a href="transaction-detail.php?id=8761" class="btn-platform btn-primary-solid view-btn">View</a></td>
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