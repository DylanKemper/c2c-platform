<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction #9 — Lootly Admin</title>
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
        $active_page = 'transactions';
        include 'partials/sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">

            <a href="transactions.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to transactions
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">Transaction #9</h1>
                <span class="badge badge--danger">
                    <i class="bi bi-shield-exclamation" style="font-size:9px"></i> Disputed
                </span>
            </div>

            <div class="row g-3 align-items-start">

                <!-- LEFT COLUMN -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- Transaction details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Transaction details</span>
                            <span class="badge badge--info">
                                <i class="bi bi-arrow-left-right" style="font-size:9px"></i> Escrow
                            </span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>Transaction ID</label>
                                    <span>#9</span>
                                </div>
                                <div class="report-item">
                                    <label>Listing</label>
                                    <a href="listing-detail.php?id=17">Listing #17 &mdash; Nike Air Max 90</a>
                                </div>
                                <div class="report-item">
                                    <label>Amount held</label>
                                    <span>R 850</span>
                                </div>
                                <div class="report-item">
                                    <label>Created</label>
                                    <span>3 days ago &mdash; 12 May 2025</span>
                                </div>
                                <div class="report-item">
                                    <label>Disputed</label>
                                    <span>Today, 08:47</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Parties panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Parties</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <!-- Buyer -->
                            <div class="user-row">
                                <div class="user-avatar">JS</div>
                                <div>
                                    <div class="user-name">
                                        @jsmith92
                                        <span class="badge-status badge-neutral" style="font-size:10px; margin-left:6px">Buyer</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since Jan 2024 &nbsp;&middot;&nbsp; 14 completed trades
                                    </div>
                                </div>
                                <a href="user-detail.php?id=31" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <hr class="panel-divider">

                            <!-- Seller -->
                            <div class="user-row">
                                <div class="user-avatar">SC</div>
                                <div>
                                    <div class="user-name">
                                        @sneakerhead_ct
                                        <span class="badge-status badge-neutral" style="font-size:10px; margin-left:6px">Seller</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since Mar 2023 &nbsp;&middot;&nbsp; 31 completed trades
                                    </div>
                                </div>
                                <a href="user-detail.php?id=88" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Dispute note panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Dispute reason</span>
                        </div>
                        <div class="panel__body">
                            <div class="report-reason-box">
                                Item received does not match the listing description. Seller claimed the shoes
                                were &ldquo;like new&rdquo; but they arrived with visible sole wear and scuffing
                                on the toe box. I have photos. Requesting a full refund.
                            </div>
                            <div class="report-item mt-2">
                                <label>Filed by</label>
                                <a href="user-detail.php?id=31">@jsmith92 (buyer)</a>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction timeline -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Transaction timeline</span>
                        </div>
                        <div class="panel__body">
                            <div class="timeline">
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-done"><i class="bi bi-check"></i></div>
                                    <div>
                                        <div class="tl-text">Transaction created &mdash; funds held in escrow</div>
                                        <div class="tl-time">12 May 2025, 14:03</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-done"><i class="bi bi-check"></i></div>
                                    <div>
                                        <div class="tl-text">Seller marked item as dispatched</div>
                                        <div class="tl-time">13 May 2025, 09:22</div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot tl-dot-active"><i class="bi bi-shield-exclamation" style="font-size:9px"></i></div>
                                    <div>
                                        <div class="tl-text">Dispute opened by buyer</div>
                                        <div class="tl-time">Today, 08:47</div>
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

                            <div class="form-field">
                                <textarea
                                    id="resolution-note"
                                    name="resolution_note"
                                    class="form-textarea"
                                    rows="4"
                                    placeholder="Resolution note (required)&hellip;"></textarea>
                            </div>

                            <button class="btn-platform btn-primary-solid" id="btn-release" disabled style="opacity:0.45">
                                <i class="bi bi-check-circle"></i> Release funds to seller
                            </button>
                            <button class="btn-platform btn-danger-outline" id="btn-refund" disabled style="opacity:0.45">
                                <i class="bi bi-arrow-counterclockwise"></i> Refund buyer
                            </button>

                            <hr class="panel-divider">

                            <!-- No note required for these two -->
                            <button class="btn-platform btn-outline" id="btn-info">
                                <i class="bi bi-chat-left-text"></i> Request more information
                            </button>
                            <button class="btn-platform btn-outline" id="btn-escalate">
                                <i class="bi bi-arrow-up-circle"></i> Escalate
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>