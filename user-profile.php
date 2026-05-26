<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/helpers/render-stars.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid user ID.');
}

$userId = (int) $_GET['id'];

/* =========================================================
   1. USER INFO
========================================================= */
$userSql = '
    SELECT
        user_id,
        username,
        created_at
    FROM users
    WHERE user_id = ?
';

$userStmt = $pdo->prepare($userSql);
$userStmt->execute([$userId]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die('User not found.');
}

/* =========================================================
   2. INITIALS
========================================================= */
$userInitials = strtoupper(substr($user['username'], 0, 2));

/* =========================================================
   3. SELLER STATS
========================================================= */
$sellerStatsSql = '
    SELECT
        COALESCE(ROUND(AVG(rating), 1), 0) AS avg_rating,
        COUNT(review_id) AS review_count
    FROM reviews
    WHERE reviewee_id = ?
    AND role = "seller"
';

$sellerStatsStmt = $pdo->prepare($sellerStatsSql);
$sellerStatsStmt->execute([$userId]);
$sellerStats = $sellerStatsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   4. BUYER STATS
========================================================= */
$buyerStatsSql = '
    SELECT
        COALESCE(ROUND(AVG(rating), 1), 0) AS avg_rating,
        COUNT(review_id) AS review_count
    FROM reviews
    WHERE reviewee_id = ?
    AND role = "buyer"
';

$buyerStatsStmt = $pdo->prepare($buyerStatsSql);
$buyerStatsStmt->execute([$userId]);
$buyerStats = $buyerStatsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   5. ACTIVE LISTINGS
========================================================= */
$listingsSql = '
    SELECT
        listing_id,
        title,
        price,
        category,
        `condition`,
        created_at
    FROM listings
    WHERE seller_id = ?
    AND status = "active"
    ORDER BY created_at DESC
';

$listingsStmt = $pdo->prepare($listingsSql);
$listingsStmt->execute([$userId]);
$activeListings = $listingsStmt->fetchAll(PDO::FETCH_ASSOC);

$activeListingCount = count($activeListings);

/* =========================================================
   6. COMPLETED TRANSACTIONS (ALL USER ACTIVITY)
========================================================= */
$completedTransactionsSql = '
    SELECT COUNT(*) AS completed_transaction_count
    FROM transactions
    WHERE (seller_id = ? OR buyer_id = ?)
    AND status = "completed"
';

$completedTransactionsStmt = $pdo->prepare($completedTransactionsSql);
$completedTransactionsStmt->execute([$userId, $userId]);
$completedTransactions = $completedTransactionsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   7. ITEMS SOLD (SELLER ONLY)
========================================================= */
$itemsSoldSql = '
    SELECT COUNT(*) AS items_sold
    FROM transactions
    WHERE seller_id = ?
    AND status = "completed"
';

$itemsSoldStmt = $pdo->prepare($itemsSoldSql);
$itemsSoldStmt->execute([$userId]);
$itemsSold = $itemsSoldStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   8. REVIEWS
========================================================= */
$reviewsSql = '
    SELECT
        r.review_id,
        r.rating,
        r.body,
        r.role,
        r.created_at,

        reviewer.user_id AS reviewer_id,
        reviewer.username AS reviewer_username,

        l.listing_id,
        l.title AS listing_title

    FROM reviews r

    LEFT JOIN users reviewer
        ON reviewer.user_id = r.reviewer_id

    LEFT JOIN transactions t
        ON t.transaction_id = r.transaction_id

    LEFT JOIN listings l
        ON l.listing_id = t.listing_id

    WHERE r.reviewee_id = ?

    ORDER BY r.created_at DESC
';

$reviewsStmt = $pdo->prepare($reviewsSql);
$reviewsStmt->execute([$userId]);
$reviews = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($user['username']) ?> — Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'partials/navbar.php'; ?>

    <div class="container py-4">
        <div class="row g-4 align-items-start">

            <!-- LEFT COLUMN -->
            <div class="col-md-4 col-lg-3 d-flex flex-column gap-3">

                <div class="panel">
                    <div class="panel__body text-center d-flex flex-column align-items-center gap-2">
                        <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>

                        <div>
                            <div class="user-name"><?= htmlspecialchars($user['username']) ?></div>
                            <div class="user-joined-date">
                                Member since <?= date('F Y', strtotime($user['created_at'])) ?>
                            </div>
                        </div>

                        <a href="report.php?type=user&id=<?= $userId ?>" class="btn-platform btn-block btn-danger-outline">
                            <i class="bi bi-flag"></i> Report user
                        </a>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel__header">
                        <span class="panel__title">Stats</span>
                    </div>

                    <div class="panel__body d-flex flex-column gap-3">

                        <!-- Seller rating -->
                        <div>
                            <div class="seller-meta mb-1">Seller rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="product-card-stars">
                                    <?= renderStars($sellerStats['avg_rating'] ?? 0) ?>
                                </div>
                                <span class="seller-rating">
                                    <?= number_format($sellerStats['avg_rating'] ?? 0, 1) ?>
                                </span>
                                <span class="rating-count">
                                    (<?= $sellerStats['review_count'] ?? 0 ?>)
                                </span>
                            </div>
                        </div>

                        <!-- Buyer rating -->
                        <div>
                            <div class="seller-meta mb-1">Buyer rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="product-card-stars">
                                    <?= renderStars($buyerStats['avg_rating'] ?? 0) ?>
                                </div>
                                <span class="seller-rating">
                                    <?= number_format($buyerStats['avg_rating'] ?? 0, 1) ?>
                                </span>
                                <span class="rating-count">
                                    (<?= $buyerStats['review_count'] ?? 0 ?>)
                                </span>
                            </div>
                        </div>

                        <hr class="m-0">

                        <div class="d-flex justify-content-between">
                            <span class="seller-meta">Completed transactions</span>
                            <span class="seller-name">
                                <?= $completedTransactions['completed_transaction_count'] ?? 0 ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="seller-meta">Active listings</span>
                            <span class="seller-name">
                                <?= $activeListingCount ?>
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <span class="seller-meta">Items sold</span>
                            <span class="seller-name">
                                <?= $itemsSold['items_sold'] ?? 0 ?>
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN -->
            <div class="col-md-8 col-lg-9 d-flex flex-column gap-3">

                <div class="profile-tabs">
                    <button class="profile-tab active" data-tab="listings">
                        <i class="bi bi-tag"></i> Listings
                        <span class="badge-status badge-neutral"><?= $activeListingCount ?></span>
                    </button>

                    <button class="profile-tab" data-tab="reviews">
                        <i class="bi bi-star"></i> Reviews
                        <span class="badge-status badge-neutral"><?= $buyerStats['review_count'] ?? 0 ?></span>
                    </button>
                </div>

                <!-- LISTINGS -->
                <div class="tab-panel active" id="tab-listings">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Active listings</span>
                        </div>

                        <div class="panel__body d-flex flex-column gap-3">

                            <?php if (empty($activeListings)): ?>
                                <p class="seller-meta">No active listings.</p>
                            <?php endif; ?>

                            <?php foreach ($activeListings as $listing): ?>
                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <p class="seller-name mb-0"><?= htmlspecialchars($listing['title']) ?></p>
                                        <p class="seller-meta mb-0">
                                            R <?= number_format($listing['price'], 2) ?>
                                            · <?= htmlspecialchars($listing['category']) ?>
                                            · <?= htmlspecialchars($listing['condition']) ?>
                                            · <?= date('M Y', strtotime($listing['created_at'])) ?>
                                        </p>
                                    </div>

                                    <a href="listing.php?id=<?= $listing['listing_id'] ?>"
                                        class="btn-platform btn-outline btn-sm">
                                        View
                                    </a>
                                </div>

                                <hr class="m-0">
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <!-- REVIEWS -->
                <div class="tab-panel" id="tab-reviews">

                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">All reviews</span>
                        </div>

                        <div class="panel__body d-flex flex-column gap-3">

                            <?php if (empty($reviews)): ?>
                                <p class="seller-meta">No reviews yet.</p>
                            <?php endif; ?>

                            <?php foreach ($reviews as $review): ?>
                                <div class="d-flex flex-column gap-2">

                                    <div class="d-flex justify-content-between">
                                        <a href="user-profile.php?id=<?= $review['reviewer_id'] ?>"
                                            class="seller-name">
                                            <?= htmlspecialchars($review['reviewer_username']) ?>
                                        </a>

                                        <span class="seller-meta">
                                            <?= date('M Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>

                                    <div class="product-card-stars">
                                        <?= renderStars($review['rating']) ?>
                                    </div>

                                    <p class="mb-0">
                                        <?= nl2br(htmlspecialchars($review['body'])) ?>
                                    </p>

                                    <?php if (!empty($review['listing_id'])): ?>
                                        <a class="btn-platform btn-outline btn-sm"
                                            href="listing.php?id=<?= $review['listing_id'] ?>">
                                            View listing
                                        </a>
                                    <?php endif; ?>

                                    <hr class="m-0">
                                </div>
                            <?php endforeach; ?>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.profile-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.profile-tab').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));

                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            });
        });
    </script>

    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/register-modal.php'; ?>
    <?php include 'partials/login-modal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>