<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing #17 — Lootly Admin</title>
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

        <!-- SIDEBAR -->
        <?php
        $active_page = 'listings';
        include 'partials/sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">

            <a href="listings.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to listings
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">Listing #17</h1>
                <span class="badge badge--warning">
                    <i class="bi bi-flag" style="font-size:9px"></i> Flagged
                </span>
            </div>

            <div class="row g-3 align-items-start">

                <!-- LEFT COLUMN -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- Listing details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Listing details</span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>Listing ID</label>
                                    <span>#17</span>
                                </div>
                                <div class="report-item">
                                    <label>Title</label>
                                    <span>Nike Air Max 90 &mdash; Size 10</span>
                                </div>
                                <div class="report-item">
                                    <label>Price</label>
                                    <span>R 850</span>
                                </div>
                                <div class="report-item">
                                    <label>Category</label>
                                    <span>Footwear</span>
                                </div>
                                <div class="report-item">
                                    <label>Condition</label>
                                    <span>Like new</span>
                                </div>
                                <div class="report-item">
                                    <label>Posted</label>
                                    <span>3 days ago &mdash; 12 May 2025</span>
                                </div>
                            </div>

                            <div class="report-item mt-2">
                                <label>Description</label>
                            </div>
                            <div class="report-reason-box">
                                Barely worn Nike Air Max 90s in white/grey colourway. Purchased from Sportscene
                                six months ago, worn twice indoors. Box included. Selling because they're half a
                                size too small. Price is firm.
                            </div>
                        </div>
                    </div>

                    <!-- Seller panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Seller</span>
                        </div>
                        <div class="panel__body">
                            <div class="user-row">
                                <div class="user-avatar">SC</div>
                                <div>
                                    <div class="user-name">@sneakerhead_ct</div>
                                    <div class="user-sub">
                                        Member since Mar 2023 &nbsp;&middot;&nbsp; 31 completed trades &nbsp;&middot;&nbsp; 0 prior bans
                                    </div>
                                </div>
                                <a href="user-detail.php?id=88" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reports against this listing -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reports against this listing</span>
                            <span class="badge badge--warning">1 open</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <div class="report-object-preview">
                                <div class="report-object-icon-box"><i class="bi bi-flag"></i></div>
                                <div>
                                    <div class="report-object-name">Report #42 &mdash; Listing report</div>
                                    <div class="report-object-sub">
                                        Filed by <strong>@jsmith92</strong> &nbsp;&middot;&nbsp; Today, 09:14
                                        &nbsp;&middot;&nbsp;
                                    </div>
                                </div>
                                <a href="report-detail.php?id=42" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                            </div>

                        </div>
                    </div>

                    <!-- Listing status timeline -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Listing history</span>
                        </div>
                        <div class="panel__body">
                            <div class="timeline">
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-done"><i class="bi bi-check"></i></div>
                                    <div>
                                        <div class="tl-text">Listing posted</div>
                                        <div class="tl-time">12 May 2025</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-active"><i class="bi bi-flag" style="font-size:9px"></i></div>
                                    <div>
                                        <div class="tl-text">Flagged &mdash; report received</div>
                                        <div class="tl-time">Today, 09:14</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-pending"><i class="bi bi-circle-dashed"></i></div>
                                    <div>
                                        <div class="tl-text" style="color:var(--muted)">Resolved</div>
                                        <div class="tl-time">Pending</div>
                                    </div>
                                </div>
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

                            <!-- Clear flags — no prerequisite -->
                            <button class="btn-platform btn-outline" id="btn-clear">
                                <i class="bi bi-check2-circle"></i> Clear flags
                            </button>

                            <hr class="panel-divider">

                            <!-- Remove listing — reason required -->
                            <div class="form-field">
                                <textarea
                                    id="remove-reason"
                                    name="remove_reason"
                                    class="form-textarea"
                                    rows="4"
                                    placeholder="Removal reason (required)&hellip;"></textarea>
                            </div>

                            <button class="btn-platform btn-danger-outline" id="btn-remove" disabled style="opacity:0.45">
                                <i class="bi bi-trash"></i> Remove listing
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>