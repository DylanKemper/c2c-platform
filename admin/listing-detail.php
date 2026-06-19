<?php
require_once __DIR__ . '/../config/db.php';

/* ====================================
    Listing Details
===================================== */
$sql = '
    SELECT
        l.listing_id,
        l.title,
        l.price,
        l.category_id,
        l.condition,
        l.description,
        l.created_at,
        l.status,
        u.user_id AS seller_id,
        u.username AS seller_username

    FROM listings l
    JOIN users u ON u.user_id = l.seller_id
    WHERE l.listing_id = ?
';

$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$listing = $stmt->fetch();

/* ====================================
    Seller details
===================================== */
$sql = '
    SELECT
        user_id,
        username,
        created_at
    FROM users
    WHERE user_id = ?
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$listing['seller_id']]);
$seller = $stmt->fetch();

$sql = '
    SELECT
        r.report_id,
        r.report_type,
        r.reporter_id,
        r.created_at,
        u.username AS reporter_username
    FROM reports r
    LEFT JOIN users u ON u.user_id = r.reporter_id
    WHERE r.target_id = ?
      AND r.report_type = "listing"
    ORDER BY r.created_at DESC
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$reports = $stmt->fetchAll();

$is_removed = $listing['status'] === 'removed';

$status_badge_class = match ($listing['status']) {
    'active'  => 'badge--success',
    'sold'    => 'badge--info',
    'removed' => 'badge--danger',
    default   => 'badge--warning',
};
$status_icon = match ($listing['status']) {
    'active'  => 'bi-check-circle',
    'sold'    => 'bi-bag-check',
    'removed' => 'bi-trash',
    default   => 'bi-clock',
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listing #<?= htmlspecialchars($listing['listing_id']) ?> — Lootly Admin</title>
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
                <h1 class="page-heading">Listing <?= htmlspecialchars($listing['title']) ?></h1>
                <span class="badge badge--lg <?= $status_badge_class ?>">
                    <i class="bi <?= $status_icon ?>" style="font-size:9px"></i> <?= ucfirst(htmlspecialchars($listing['status'])) ?>
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
                                    <span>#<?= htmlspecialchars($listing['listing_id']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Title</label>
                                    <span><?= htmlspecialchars($listing['title']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Price</label>
                                    <span>R <?= htmlspecialchars($listing['price']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Category</label>
                                    <span><?= htmlspecialchars($listing['category_id']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Condition</label>
                                    <span><?= htmlspecialchars($listing['condition']) ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Posted</label>
                                    <span><?= htmlspecialchars($listing['created_at']) ?></span>
                                </div>
                            </div>

                            <div class="report-item mt-2">
                                <label>Description</label>
                            </div>
                            <div class="report-reason-box">
                                <?= nl2br(htmlspecialchars($listing['description'])) ?>
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
                                <div class="user-avatar"><?= strtoupper(substr($seller['username'], 0, 2)); ?></div>
                                <div>
                                    <div class="user-name">@<?= htmlspecialchars($seller['username']) ?></div>
                                    <div class="user-sub">
                                        Joined <?= htmlspecialchars($seller['created_at']) ?>
                                    </div>
                                </div>
                                <a href="user-detail.php?id=<?= htmlspecialchars($seller['user_id']) ?>" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Reports against this listing -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reports against this listing</span>
                            <span class="badge badge--warning"><?= count($reports) ?> open</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">
                            <?php if (empty($reports)): ?>
                                <div class="text-center text-muted py-4">No reports found.</div>
                            <?php else: ?>
                                <?php foreach ($reports as $r): ?>
                                    <div class="report-row">
                                        <div>
                                            <div class="reporter-name">@<?= htmlspecialchars($r['reporter_username']) ?></div>
                                            <div class="report-time"><?= htmlspecialchars($r['created_at']) ?></div>
                                        </div>
                                        <div class="report-reason-box">
                                            Report type: <?= htmlspecialchars($r['report_type']) ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: action panel OR removed notice -->
                <div class="col-md-4">
                    <?php if ($is_removed): ?>

                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Status</span>
                            </div>
                            <div class="panel__body">
                                <div class="text-center text-muted py-3">
                                    <i class="bi bi-trash" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 mt-2">This listing has been removed and is no longer visible on the public site.</p>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Actions</span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-2">

                                <form method="POST" action="remove-listing.php">
                                    <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">
                                    <button type="submit" class="btn-platform btn-danger-outline btn-block">
                                        <i class="bi bi-trash"></i> Remove listing
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