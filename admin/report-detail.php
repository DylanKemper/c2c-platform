<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report #42 — Lootly Admin</title>
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

        <!-- ═══════════ SIDEBAR ═══════════ -->
        <?php include 'partials/sidebar.php'; ?>

        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <div class="main-content flex-grow-1">
            <!-- Page header -->
            <a href="reports.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to reports
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">Report #42</h1>
                <span class="badge-status badge-review">
                    <i class="bi bi-clock" style="font-size:9px"></i> Under review
                </span>
            </div>

            <!-- Two-column detail layout -->
            <div class="row g-3 align-items-start">

                <!-- ── LEFT COLUMN (col-8): info panels ── -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- Report details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Report details</span>
                            <span class="badge-status badge-type-listing">
                                <i class="bi bi-tag" style="font-size:9px"></i> Listing report
                            </span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>Report ID</label>
                                    <span>#42</span>
                                </div>
                                <div class="report-item">
                                    <label>Submitted</label>
                                    <span>Today, 09:14 — 2 hours ago</span>
                                </div>
                                <div class="report-item">
                                    <label>Reporter</label>
                                    <a href="user-detail.php?id=31">@jsmith92</a>
                                </div>
                                <div class="report-item">
                                    <label>Target</label>
                                    <a href="listing-detail.php?id=17">Listing #17</a>
                                </div>
                            </div>

                            <div class="report-item">
                                <label>Reason</label>
                            </div>
                            <div class="report-reason-box">
                                This listing appears to be selling counterfeit goods. The photos look identical
                                to another listing I saw last week that was removed, and the price is suspiciously
                                low for a supposedly authentic item.
                            </div>

                            <div class="report-item">
                                <label>Reported entity</label>
                            </div>
                            <div class="report-object-preview">
                                <div class="report-object-icon-box">
                                    <i class="bi bi-tag"></i>
                                </div>
                                <div>
                                    <div class="report-object-name">Nike Air Max 90 — Size 10</div>
                                    <div class="report-object-sub">
                                        Listed by <strong>@sneakerhead_ct</strong> &nbsp;·&nbsp; R 850
                                        &nbsp;·&nbsp; Posted 3 days ago
                                    </div>
                                </div>
                                <a href="listing-detail.php?id=17" class="report-object-link">
                                    View listing <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Report timeline panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Report timeline</span>
                        </div>
                        <div class="panel__body">
                            <div class="timeline">
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-done">
                                        <i class="bi bi-check"></i>
                                    </div>
                                    <div>
                                        <div class="tl-text">Report submitted</div>
                                        <div class="tl-time">Today, 09:14</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-active">
                                        <i class="bi bi-eye"></i>
                                    </div>
                                    <div>
                                        <div class="tl-text">Marked as under review</div>
                                        <div class="tl-time">Today, 10:02</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-pending">
                                        <i class="bi bi-circle-dashed"></i>
                                    </div>
                                    <div>
                                        <div class="tl-text" style="color:var(--muted)">Resolved</div>
                                        <div class="tl-time">Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reporter panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reporter</span>
                        </div>
                        <div class="panel__body">
                            <div class="user-row">
                                <div class="user-avatar">JS</div>
                                <div>
                                    <div class="user-name">@jsmith92</div>
                                    <div class="user-sub">
                                        Member since Jan 2024 &nbsp;·&nbsp; 14 completed trades &nbsp;·&nbsp; 0 prior reports
                                    </div>
                                </div>
                                <a href="user-detail.php?id=31" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ── RIGHT COLUMN: action panel ── -->
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Actions</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">
                            <div class="form-field">
                                <textarea
                                    id="resolution-note"
                                    name="resolution_note"
                                    class="form-textarea"
                                    rows="4"
                                    placeholder="Add a note before resolving…"></textarea>
                            </div>

                            <button class="btn-platform btn-primary-solid">
                                <i class="bi bi-check-circle"></i> Mark as resolved
                            </button>
                            <button class="btn-platform btn-danger-outline">
                                <i class="bi bi-x-circle"></i> Dismiss report
                            </button>

                            <hr class="panel-divider">


                            <div class="panel__title">
                                Jump to
                            </div>

                            <a href="listing-detail.php?id=17" class="btn-platform btn-outline">
                                <i class="bi bi-tag"></i> View listing #17
                            </a>
                            <a href="user-detail.php?id=88" class="btn-platform btn-outline">
                                <i class="bi bi-person"></i> View seller account
                            </a>
                            <a href="user-detail.php?id=31" class="btn-platform btn-outline">
                                <i class="bi bi-person-check"></i> View reporter account
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>