<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-flex">
        <?php include 'partials/sidebar.php'; ?>

        <!-- Admin stat cards -->
        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Platform overview</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Active listings</span>
                            <div class="stat-icon"><i class="bi bi-bag"></i></div>
                        </div>
                        <div class="stat-value">284</div>
                        <div class="stat-footer"><span>+12</span> since yesterday</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Registered users</span>
                            <div class="stat-icon"><i class="bi bi-people"></i></div>
                        </div>
                        <div class="stat-value">1,042</div>
                        <div class="stat-footer"><span>+5</span> new today</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card stat-card--alert">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Open reports</span>
                            <div class="stat-icon"><i class="bi bi-exclamation-triangle"></i></div>
                        </div>
                        <div class="stat-value">7</div>
                        <div class="stat-footer"><span>3</span> require urgent review</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card stat-card--warn">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Escrow disputes</span>
                            <div class="stat-icon"><i class="bi bi-exclamation-circle"></i></div>
                        </div>
                        <div class="stat-value">2</div>
                        <div class="stat-footer"><span>1</span> opened today</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Completed transactions</span>
                            <div class="stat-icon"><i class="bi bi-check-lg"></i></div>
                        </div>
                        <div class="stat-value">538</div>
                        <div class="stat-footer"><span>+5</span> this week</div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="stat-label">Revenue processed</span>
                            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                        </div>
                        <div class="stat-value">R69k</div>
                        <div class="stat-footer"><span>+R6.9k</span> this week</div>
                    </div>
                </div>

            </div>

            <!-- Two column row -->
            <div class="row g-3">

                <!-- Recent activity -->
                <div class="col-md-8">
                    <div class="dash-panel">
                        <div class="dash-panel-heading">Recent activity</div>

                        <div class="activity-item">
                            <div class="activity-icon"><i class="bi bi-person-plus"></i></div>
                            <div class="activity-body">
                                <div class="activity-text">New user registered — <strong>@jake_smith</strong></div>
                                <div class="activity-time">2 minutes ago</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon"><i class="bi bi-tag"></i></div>
                            <div class="activity-body">
                                <div class="activity-text">New listing posted — <strong>Sony WH-1000XM5</strong> by @priya_k</div>
                                <div class="activity-time">11 minutes ago</div>
                            </div>
                        </div>

                        <div class="activity-item">
                            <div class="activity-icon"><i class="bi bi-check-circle"></i></div>
                            <div class="activity-body">
                                <div class="activity-text">Transaction completed — <strong>#TXN8821</strong> for R1,200</div>
                                <div class="activity-time">34 minutes ago</div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Action queue -->
                <div class="col-md-4">
                    <div class="dash-panel">
                        <div class="dash-panel-heading">Action queue</div>

                        <div class="action-item action-item--alert">
                            <div class="action-badge">Report</div>
                            <div class="action-body">
                                <div class="action-text">Listing #441 — suspected fake item</div>
                                <div class="action-meta">Reported by @mike_t · 3h ago</div>
                            </div>
                            <a href="admin/report-detail.php?id=1" class="action-btn">Review</a>
                        </div>

                        <div class="action-item action-item--alert">
                            <div class="action-badge">Report</div>
                            <div class="action-body">
                                <div class="action-text">User @scammer99 — harassment</div>
                                <div class="action-meta">Reported by @lisa_m · 5h ago</div>
                            </div>
                            <a href="admin/report-detail.php?id=2" class="action-btn">Review</a>
                        </div>

                        <div class="action-item action-item--warn">
                            <div class="action-badge action-badge--warn">Dispute</div>
                            <div class="action-body">
                                <div class="action-text">Escrow #TXN8803 — buyer no-show</div>
                                <div class="action-meta">Opened by @seller_dan · 1d ago</div>
                            </div>
                            <a href="admin/transactions.php?id=8803" class="action-btn">Review</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>