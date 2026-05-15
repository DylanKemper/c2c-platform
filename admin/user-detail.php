<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User @jsmith92 — Lootly Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Mobile warning (hidden on desktop) -->
    <div class="d-md-none alert alert-warning text-center p-3 admin-mobile-warning" style="display:none!important">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">
        <?php
        $active_page = 'users';
        include 'partials/sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">
            <a href="users.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to users
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">User #31</h1>
                <span class="badge badge--success">
                    <i class="bi bi-circle-fill" style="font-size:7px"></i> Active
                </span>
            </div>

            <div class="row g-3 align-items-start">
                <!-- LEFT COLUMN -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- User details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">User details</span>
                            <span class="badge badge--info">
                                <i class="bi bi-person" style="font-size:9px"></i> Standard account
                            </span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>User ID</label>
                                    <span>#31</span>
                                </div>
                                <div class="report-item">
                                    <label>Username</label>
                                    <span>@jsmith92</span>
                                </div>
                                <div class="report-item">
                                    <label>Email</label>
                                    <span>jsmith92@example.com</span>
                                </div>
                                <div class="report-item">
                                    <label>Member since</label>
                                    <span>Jan 2024</span>
                                </div>
                                <div class="report-item">
                                    <label>Last active</label>
                                    <span>Today, 09:14 &mdash; 2 hours ago</span>
                                </div>
                                <div class="report-item">
                                    <label>Completed trades</label>
                                    <span>14</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listings panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Listings</span>
                            <span class="badge badge--success">3 active</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <div class="report-object-preview">
                                <div class="report-object-icon-box"><i class="bi bi-tag"></i></div>
                                <div>
                                    <div class="report-object-name">Jordan 1 Retro High OG &mdash; Size 9</div>
                                    <div class="report-object-sub">R 1 200 &nbsp;&middot;&nbsp; Active &nbsp;&middot;&nbsp; Posted 5 days ago</div>
                                </div>
                                <a href="listing-detail.php?id=22" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                            </div>

                            <div class="report-object-preview">
                                <div class="report-object-icon-box"><i class="bi bi-tag"></i></div>
                                <div>
                                    <div class="report-object-name">Adidas Ultraboost 22 &mdash; Size 10</div>
                                    <div class="report-object-sub">R 650 &nbsp;&middot;&nbsp; Active &nbsp;&middot;&nbsp; Posted 12 days ago</div>
                                </div>
                                <a href="listing-detail.php?id=19" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                            </div>

                            <div class="report-object-preview">
                                <div class="report-object-icon-box"><i class="bi bi-tag"></i></div>
                                <div>
                                    <div class="report-object-name">New Balance 550 &mdash; Size 10</div>
                                    <div class="report-object-sub">R 480 &nbsp;&middot;&nbsp; Active &nbsp;&middot;&nbsp; Posted 20 days ago</div>
                                </div>
                                <a href="listing-detail.php?id=14" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                            </div>

                        </div>
                    </div>

                    <!-- Reports against this user -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reports against this user</span>
                            <span class="badge badge--warning">1 open</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <div class="report-object-preview">
                                <div class="report-object-icon-box"><i class="bi bi-flag"></i></div>
                                <div>
                                    <div class="report-object-name">Report #38 &mdash; User report</div>
                                    <div class="report-object-sub">
                                        Filed by <strong>@buyer_cape99</strong> &nbsp;&middot;&nbsp; 3 days ago &nbsp;&middot;&nbsp;
                                        <span style="color:var(--warning,#f59e0b)">Open</span>
                                    </div>
                                </div>
                                <a href="report-detail.php?id=38" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: action panel -->
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Actions</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <button class="btn-platform btn-outline" id="btn-warn">
                                <i class="bi bi-exclamation-triangle"></i> Send warning
                            </button>

                            <hr class="panel-divider">

                            <div class="form-field">
                                <label class="form-label" for="suspend-duration">Suspension duration</label>
                                <select id="suspend-duration" class="form-select form-select-sm">
                                    <option value="">Select duration&hellip;</option>
                                    <option value="7">7 days</option>
                                    <option value="30">30 days</option>
                                    <option value="custom">Custom</option>
                                </select>
                            </div>

                            <button class="btn-platform btn-warning-outline" id="btn-suspend" disabled style="opacity:0.45">
                                <i class="bi bi-slash-circle"></i> Suspend account
                            </button>

                            <hr class="panel-divider">

                            <div class="form-field">
                                <textarea
                                    id="ban-reason"
                                    name="ban_reason"
                                    class="form-textarea"
                                    rows="3"
                                    placeholder="Ban reason (required)&hellip;"></textarea>
                            </div>

                            <button class="btn-platform btn-danger-outline" id="btn-ban" disabled style="opacity:0.45">
                                <i class="bi bi-x-octagon"></i> Ban account
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>