<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escrow Disputes — Lootly Admin</title>
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
            <div class="page-heading">Escrow Disputes</div>

            <div class="records-panel">
                <!-- Filter bar -->
                <div class="filter-bar">
                    <input type="text" placeholder="Search by transaction ID or username" class="search-input">
                    <select class="filter-select">
                        <option value="">All statuses</option>
                        <option value="open">Open</option>
                        <option value="under_review">Under Review</option>
                        <option value="resolved">Resolved</option>
                    </select>
                    <select class="filter-select">
                        <option value="">All resolutions</option>
                        <option value="buyer">Awarded to Buyer</option>
                        <option value="seller">Awarded to Seller</option>
                        <option value="split">Split</option>
                    </select>
                    <button class="btn-platform btn-primary-solid">Filter</button>
                    <button class="btn-platform btn-outline">Clear</button>
                </div>

                <!-- Disputes table -->
                <table class="records-table">
                    <thead>
                        <tr>
                            <th style="width:10%">Transaction</th>
                            <th style="width:14%">Buyer</th>
                            <th style="width:14%">Seller</th>
                            <th style="width:20%">Reason</th>
                            <th style="width:8%">Amount</th>
                            <th style="width:10%">Opened</th>
                            <th style="width:12%">Status</th>
                            <th style="width:12%">Resolution</th>
                            <th style="width:6%"></th>
                        </tr>
                    </thead>
                    <tbody>

                        <!-- Open dispute — no resolution yet -->
                        <tr class="clickable">
                            <td>#TXN8803</td>
                            <td><span class="avatar">LK</span> lk_trades</td>
                            <td><span class="avatar">SD</span> seller_dan</td>
                            <td class="reason-cell">Buyer claims item never arrived after...</td>
                            <td>R 12,500</td>
                            <td>3 Feb 2025</td>
                            <td><span class="status-badge badge-red">Open</span></td>
                            <td><span class="resolution-cell">—</span></td>
                            <td><a href="dispute-detail.php?id=1" class="btn-platform btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <!-- Under review — no resolution yet -->
                        <tr class="clickable">
                            <td>#TXN8790</td>
                            <td><span class="avatar">MT</span> mike_t</td>
                            <td><span class="avatar">LM</span> lisa_m</td>
                            <td class="reason-cell">Seller claims buyer no-show at agreed...</td>
                            <td>R 28,000</td>
                            <td>19 Mar 2025</td>
                            <td><span class="status-badge badge-amber">Under Review</span></td>
                            <td><span class="resolution-cell">—</span></td>
                            <td><a href="dispute-detail.php?id=2" class="btn-platform btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <!-- Resolved — awarded to buyer -->
                        <tr class="clickable">
                            <td>#TXN8761</td>
                            <td><span class="avatar">SM</span> sarah_m</td>
                            <td><span class="avatar">TP</span> the_pete</td>
                            <td class="reason-cell">Item significantly not as described, photos...</td>
                            <td>R 9,500</td>
                            <td>1 Apr 2025</td>
                            <td><span class="status-badge badge-green">Resolved</span></td>
                            <td><span class="resolution-badge resolution--buyer">Buyer</span></td>
                            <td><a href="dispute-detail.php?id=3" class="btn-platform btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <!-- Resolved — awarded to seller -->
                        <tr class="clickable">
                            <td>#TXN8744</td>
                            <td><span class="avatar">PK</span> priya_k</td>
                            <td><span class="avatar">JD</span> john_doe</td>
                            <td class="reason-cell">Buyer requested refund outside policy window...</td>
                            <td>R 6,300</td>
                            <td>22 Apr 2025</td>
                            <td><span class="status-badge badge-green">Resolved</span></td>
                            <td><span class="resolution-badge resolution--seller">Seller</span></td>
                            <td><a href="dispute-detail.php?id=4" class="btn-platform btn-primary-solid view-btn">View</a></td>
                        </tr>

                        <!-- Resolved — split -->
                        <tr class="clickable">
                            <td>#TXN8731</td>
                            <td><span class="avatar">JD</span> john_doe</td>
                            <td><span class="avatar">MT</span> mike_t</td>
                            <td class="reason-cell">Partial delivery — only two of four items...</td>
                            <td>R 5,800</td>
                            <td>28 Apr 2025</td>
                            <td><span class="status-badge badge-green">Resolved</span></td>
                            <td><span class="resolution-badge resolution--split">Split</span></td>
                            <td><a href="dispute-detail.php?id=5" class="btn-platform btn-primary-solid view-btn">View</a></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>