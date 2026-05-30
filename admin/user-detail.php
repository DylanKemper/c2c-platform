<?php
require_once __DIR__ . '/../config/db.php';
/* =========================================================
   1. USER INFO
========================================================= */
$sql = '
    SELECT
        u.user_id,
        u.username,
        u.email,
        u.created_at,
        u.last_active,
        u.is_suspended,
        u.suspended_until,
        u.is_banned

    FROM users u
    WHERE u.user_id = ?
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$user = $stmt->fetch();

/* =========================================================
   2. FETCH USER COMPLETED TRANSACTIONS COUNT
========================================================= */
$sql = '
    SELECT COUNT(*) FROM transactions
    WHERE buyer_id = ? OR seller_id = ? AND status = "completed"
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$user['user_id'], $user['user_id']]);
$user['transaction_count'] = $stmt->fetchColumn();

/* =========================================================
   3. FETCH ACTIVE LISTINGS
========================================================= */
$sql = '
    SELECT COUNT(*) AS active_listing_count
    FROM listings
    WHERE seller_id = ? AND status = "active"
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$activeListingCount = $stmt->fetchColumn();

/* =========================================================
   4. FETCH ACTIVE LISTINGS DETAILS
========================================================= */
$sql = '
    SELECT
        listing_id,
        title,
        price,
        status,
        created_at
    FROM listings
    WHERE seller_id = ?
      AND status = "active"
    ORDER BY created_at DESC
';

$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$activeListings = $stmt->fetchAll();

/* =========================================================
   5. FETCH REPORTS AGAINST THIS USER
   (not implemented yet, so hardcoding for demo)
========================================================= */
$sql = '
    SELECT COUNT(*) AS report_count
    FROM reports
    WHERE reporter_id = ?
      AND status = "open"
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$openReportCount = $stmt->fetchColumn();

?>

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
                <h1 class="page-heading">User <?= htmlspecialchars($user['username']) ?></h1>
                <?php if ($user['is_banned']): ?>
                    <span class="badge badge--danger">
                        Banned
                    </span>

                <?php elseif ($user['is_suspended']): ?>
                    <span class="badge badge--warning">
                        Suspended
                    </span>

                <?php else: ?>
                    <span class="badge badge--success">
                        Active
                    </span>
                <?php endif; ?>
            </div>

            <div class="row g-3 align-items-start">
                <!-- LEFT COLUMN -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- User details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">User details</span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>User ID</label>
                                    <span>#<?= $user['user_id'] ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Username</label>
                                    <span>@<?= htmlspecialchars($user['username']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Email</label>
                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Member since</label>
                                    <span><?= htmlspecialchars($user['created_at']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Last active</label>
                                    <span><?= htmlspecialchars($user['last_active']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Completed trades</label>
                                    <span><?= $user['transaction_count'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Listings panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Listings</span>
                            <span class="badge badge--success"><?= $activeListingCount ?> active</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <?php if (empty($activeListings)): ?> ?>
                                <p class="text-muted">This user has no active listings.</p>
                            <?php endif; ?>

                            <?php foreach ($activeListings as $listing): ?>
                                <div class="report-object-preview">
                                    <div class="report-object-icon-box"><i class="bi bi-tag"></i></div>
                                    <div>
                                        <div class="report-object-name"><?= htmlspecialchars($listing['title']) ?></div>
                                        <div class="report-object-sub">
                                            R <?= number_format($listing['price'], 2) ?> &nbsp;&middot;&nbsp;
                                            <?= ucfirst($listing['status']) ?> &nbsp;&middot;&nbsp;
                                            Posted <?= htmlspecialchars($listing['created_at']) ?>
                                        </div>
                                    </div>
                                    <a href="listing-detail.php?id=<?= $listing['listing_id'] ?>" class="report-object-link">View <i class="bi bi-arrow-right"></i></a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Reports against this user -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reports against this user</span>
                            <span class="badge badge--warning"><?= $openReportCount ?> open</span>
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