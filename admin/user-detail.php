<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}

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
    WHERE (buyer_id = ? OR seller_id = ?)
      AND status = "completed"
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
   5. FETCH ALL REPORTS AGAINST THIS USER
========================================================= */
$sql = '
    SELECT
        r.report_id,
        r.reporter_id,
        r.report_type,
        r.target_id,
        r.reason,
        r.status,
        r.created_at,
        r.resolved_at,
        r.resolution_note,

        u.username AS reporter_username
    FROM reports r
    LEFT JOIN users u ON u.user_id = r.reporter_id
    WHERE r.target_id = ?
      AND r.report_type = "user"
    ORDER BY r.created_at DESC
';

$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   6. COUNT OPEN (PENDING) REPORTS
========================================================= */
$countSql = '
    SELECT COUNT(*) 
    FROM reports
    WHERE target_id = ?
      AND report_type = "user"
      AND status = "pending"
';

$countStmt = $pdo->prepare($countSql);
$countStmt->execute([$_GET['id']]);
$openReportCount = (int) $countStmt->fetchColumn();

$is_banned = (bool) $user['is_banned'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User @<?= htmlspecialchars($user['username']) ?> — Lootly Admin</title>
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

                            <?php if (empty($activeListings)): ?>
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
                            <?php if (empty($reports)): ?>
                                <div class="text-center text-muted py-4">No reports found.</div>
                            <?php else: ?>
                                <?php foreach ($reports as $report): ?>
                                    <div class="report-object-preview">
                                        <div class="report-object-icon-box">
                                            <i class="bi bi-flag"></i>
                                        </div>
                                        <div>
                                            <div class="report-object-name">
                                                Report #<?= (int)$report['report_id'] ?>
                                                &mdash;
                                                <?= htmlspecialchars(ucfirst($report['report_type'])) ?> report
                                            </div>

                                            <div class="report-object-sub">
                                                Filed by
                                                <strong>
                                                    @<?= htmlspecialchars($report['reporter_username'] ?? 'unknown') ?>
                                                </strong>
                                                &nbsp;&middot;&nbsp;
                                                <?= date('j M Y', strtotime($report['created_at'])) ?>
                                                &nbsp;&middot;&nbsp;
                                                <span style="color:<?= $report['status'] === 'pending' ? 'var(--warning,#f59e0b)' : 'var(--success,#10b981)' ?>">
                                                    <?= htmlspecialchars(ucfirst($report['status'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <a href="report-detail.php?id=<?= (int)$report['report_id'] ?>"
                                            class="report-object-link">
                                            View <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: action panel OR banned notice -->
                <div class="col-md-4">
                    <?php if ($is_banned): ?>

                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Status</span>
                            </div>
                            <div class="panel__body">
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-slash-circle" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 mt-2">This user has been banned. Their listings have been removed from the public site.</p>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Actions</span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-2">
                                <form method="POST" action="ban-user.php">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                    <button type="submit" class="btn-platform btn-danger-outline btn-block">
                                        <i class="bi bi-slash-circle"></i> Ban user
                                    </button>
                                </form>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>