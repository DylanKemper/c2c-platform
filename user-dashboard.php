<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/helpers/render-stars.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

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
   2. USER INITIALS
========================================================= */
$userInitials = strtoupper(substr($user['username'], 0, 2));

/* =========================================================
   3. SELLER REVIEW STATS
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
   4. BUYER REVIEW STATS
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
$activeListingsSql = '
    SELECT
        listing_id,
        title,
        category,
        price,
        `condition`,
        created_at
    FROM listings
    WHERE seller_id = ?
    AND status = "active"
    ORDER BY created_at DESC
';

$activeListingsStmt = $pdo->prepare($activeListingsSql);
$activeListingsStmt->execute([$userId]);

$activeListings = $activeListingsStmt->fetchAll(PDO::FETCH_ASSOC);

$activeListingCount = count($activeListings);

/* =========================================================
   6. SOLD LISTINGS
========================================================= */
$soldListingsSql = '
    SELECT
        l.listing_id,
        l.title,
        l.category,
        l.price,
        l.`condition`,
        t.created_at

    FROM transactions t

    INNER JOIN listings l
        ON l.listing_id = t.listing_id

    WHERE t.seller_id = ?
    AND t.status = "completed"

    ORDER BY t.created_at DESC
';

$soldListingsStmt = $pdo->prepare($soldListingsSql);
$soldListingsStmt->execute([$userId]);

$soldListings = $soldListingsStmt->fetchAll(PDO::FETCH_ASSOC);

$totalItemsSold = count($soldListings);

/* =========================================================
   ITEMS BOUGHT (WITH REVIEW STATUS)
========================================================= */
$itemsBoughtSql = '
    SELECT
        l.listing_id,
        l.title,
        l.category,
        l.price,
        l.`condition`,
        t.created_at AS purchased_at,
        u.username AS seller_username,

        r.review_id

    FROM transactions t

    INNER JOIN listings l
        ON l.listing_id = t.listing_id

    INNER JOIN users u
        ON u.user_id = t.seller_id

    LEFT JOIN reviews r
        ON r.transaction_id = t.transaction_id
        AND r.reviewer_id = ?

    WHERE t.buyer_id = ?
    AND t.status = "completed"

    ORDER BY t.created_at DESC
';

$stmt = $pdo->prepare($itemsBoughtSql);
$stmt->execute([$userId, $userId]);

$itemsBought = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   8. COMPLETED TRANSACTIONS
========================================================= */
$completedTransactionsSql = '
    SELECT COUNT(*) AS completed_transaction_count
    FROM transactions
    WHERE (
        seller_id = ?
        OR buyer_id = ?
    )
    AND status = "completed"
';

$completedTransactionsStmt = $pdo->prepare($completedTransactionsSql);
$completedTransactionsStmt->execute([$userId, $userId]);

$completedTransactions = $completedTransactionsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   9. REVIEWS RECEIVED
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

$reviewsReceived = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
$reviewsReceivedCount = count($reviewsReceived);

/* =========================================================
   10. REVIEWS LEFT
========================================================= */
$reviewsLeftSql = '
    SELECT
        r.review_id,
        r.rating,
        r.body,
        r.role,
        r.created_at,

        reviewee.user_id AS reviewee_id,
        reviewee.username AS reviewee_username,

        l.listing_id,
        l.title AS listing_title

    FROM reviews r

    LEFT JOIN users reviewee
        ON reviewee.user_id = r.reviewee_id

    LEFT JOIN transactions t
        ON t.transaction_id = r.transaction_id

    LEFT JOIN listings l
        ON l.listing_id = t.listing_id

    WHERE r.reviewer_id = ?

    ORDER BY r.created_at DESC
';

$reviewsLeftStmt = $pdo->prepare($reviewsLeftSql);
$reviewsLeftStmt->execute([$userId]);

$reviewsLeft = $reviewsLeftStmt->fetchAll(PDO::FETCH_ASSOC);
$reviewsLeftCount = count($reviewsLeft);

/* =========================================================
   11. CLEAN VARIABLES
========================================================= */
$username = $user['username'];
$memberSince = date('F Y', strtotime($user['created_at']));

$sellerAvgRating = $sellerStats['avg_rating'] ?? 0;
$sellerReviewCount = $sellerStats['review_count'] ?? 0;

$buyerAvgRating = $buyerStats['avg_rating'] ?? 0;
$buyerReviewCount = $buyerStats['review_count'] ?? 0;

$totalItemsSold = count($soldListings);
$totalItemsBought = count($itemsBought);
$completedTransactionCount = $completedTransactions['completed_transaction_count'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — Lootly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'partials/navbar.php'; ?>

    <div class="container py-4">
        <div class="row g-4 align-items-start">

            <!-- ══════════════════════════════════
                 LEFT COLUMN — identity + stats
            ══════════════════════════════════ -->
            <div class="col-md-4 col-lg-3 d-flex flex-column gap-3">

                <!-- Identity card — reuses .seller-card internals -->
                <div class="panel">
                    <div class="panel__body text-center d-flex flex-column align-items-center gap-2">
                        <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>
                        <div>
                            <div class="user-name">@<?= htmlspecialchars($username) ?></div>
                            <div class="user-joined-date">Member since <?= htmlspecialchars($memberSince) ?></div>
                        </div>
                        <a href="edit-profile.php" class="btn-platform btn-outline w-100 mt-1">
                            <i class="bi bi-pencil"></i> Edit profile
                        </a>
                    </div>
                </div>

                <!-- Stats card -->
                <div class="panel">
                    <div class="panel__header">
                        <span class="panel__title">Stats</span>
                    </div>
                    <div class="panel__body d-flex flex-column gap-3">

                        <!-- Seller rating -->
                        <div>
                            <div class="seller-meta mb-1">Seller rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= renderStars($sellerStats['avg_rating'] ?? 0) ?>
                                <span class="seller-rating"><?= number_format($sellerStats['avg_rating'] ?? 0, 1) ?></span>
                                <span class="rating-count">(<?= $sellerStats['review_count'] ?? 0 ?>)</span>
                            </div>
                        </div>

                        <!-- Buyer rating -->
                        <div>
                            <div class="seller-meta mb-1">Buyer rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= renderStars($buyerStats['avg_rating'] ?? 0) ?>
                                <span class="seller-rating"><?= number_format($buyerStats['avg_rating'] ?? 0, 1) ?></span>
                                <span class="rating-count">(<?= $buyerStats['review_count'] ?? 0 ?>)</span>
                            </div>
                        </div>
                        <hr class="m-0">

                        <!-- Counts — reuse seller-meta / seller-name pairing -->
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta">
                                <i class="bi bi-arrow-left-right me-1"></i>
                                Completed transactions
                            </span>
                            <span class="seller-name">
                                <?= $completedTransactionCount ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta">
                                <i class="bi bi-tag me-1"></i>
                                Active listings
                            </span>
                            <span class="seller-name">
                                <?= $activeListingCount ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta">
                                <i class="bi bi-bag-check me-1"></i>
                                Items sold
                            </span>
                            <span class="seller-name">
                                <?= $totalItemsSold ?>
                            </span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta">
                                <i class="bi bi-bag me-1"></i>
                                Items bought
                            </span>
                            <span class="seller-name">
                                <?= $totalItemsBought ?>
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════
                 RIGHT COLUMN — tabbed content
            ══════════════════════════════════ -->
            <div class="col-md-8 col-lg-9 d-flex flex-column gap-3">

                <div class="profile-tabs">
                    <button class="profile-tab active" data-tab="selling">
                        <i class="bi bi-tag"></i> Selling
                        <span class="badge-status badge-neutral ms-1"><?= $activeListingCount ?></span>
                    </button>
                    <button class="profile-tab" data-tab="sold">
                        <i class="bi bi-bag-check"></i> Sold
                        <span class="badge-status badge-neutral ms-1"><?= $totalItemsSold ?></span>
                    </button>
                    <button class="profile-tab" data-tab="bought">
                        <i class="bi bi-bag"></i> Bought
                        <span class="badge-status badge-neutral ms-1"><?= $totalItemsBought ?></span>
                    </button>
                    <button class="profile-tab" data-tab="reviews-received">
                        <i class="bi bi-star"></i> Reviews received
                        <span class="badge-status badge-neutral ms-1"><?= $reviewsReceivedCount ?></span>
                    </button>
                    <button class="profile-tab" data-tab="reviews-left">
                        <i class="bi bi-star-half"></i> Reviews left
                        <span class="badge-status badge-neutral ms-1"><?= $reviewsLeftCount ?></span>
                    </button>
                </div>

                <!-- ── SELLING TAB ── -->
                <div class="tab-panel active" id="tab-selling">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Active listings</span>
                            <a href="listing-form.php" class="btn-platform btn-primary-solid btn-sm">
                                <i class="bi bi-plus"></i> New listing
                            </a>
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

                <!-- ── SOLD TAB ── -->
                <div class="tab-panel" id="tab-sold">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Sold items</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <?php if (empty($soldListings)): ?>
                                <p class="seller-meta">No sold listings.</p>
                            <?php endif; ?>
                            <?php foreach ($soldListings as $listing): ?>
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

                <!-- ── BOUGHT TAB ── -->
                <div class="tab-panel" id="tab-bought">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Purchased items</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">
                            <?php if (empty($itemsBought)): ?>
                                <p class="seller-meta">No purchased items.</p>
                            <?php endif; ?>
                            <?php foreach ($itemsBought as $listing): ?>
                                <?php
                                $isReviewed = !empty($listing['review_id']);
                                ?>

                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <p class="seller-name mb-0"><?= htmlspecialchars($listing['title']) ?></p>

                                        <p class="seller-meta mb-0">
                                            R <?= number_format($listing['price'], 2) ?>
                                            · <?= htmlspecialchars($listing['category']) ?>
                                            · <?= htmlspecialchars($listing['condition']) ?>
                                            · <?= date('M Y', strtotime($listing['purchased_at'])) ?>
                                            · From @<?= htmlspecialchars($listing['seller_username']) ?>
                                        </p>

                                        <?php if ($isReviewed): ?>
                                            <span class="badge badge--md badge--success">
                                                <i class="bi bi-star-fill"></i>&nbsp; Reviewed
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge--md badge--warning d-inline-flex">
                                                <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Review pending
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-2 flex-shrink-0">
                                        <a href="listing.php?id=<?= $listing['listing_id'] ?>"
                                            class="btn-platform btn-outline btn-sm">
                                            View
                                        </a>

                                        <?php if (!$isReviewed): ?>
                                            <button class="btn-platform btn-primary-solid btn-sm"
                                                onclick="openReviewModal(
                            <?= $listing['listing_id'] ?>,
                            '<?= htmlspecialchars($listing['title'], ENT_QUOTES) ?>',
                            '<?= htmlspecialchars($listing['seller_username'], ENT_QUOTES) ?>'
                        )">
                                                <i class="bi bi-star"></i> Review
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <hr class="m-0">
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- ── REVIEWS RECEIVED TAB ── -->
                <div class="tab-panel" id="tab-reviews-received">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reviews received</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <?php if (empty($reviewsReceived)): ?>
                                <p class="seller-meta">No reviews yet.</p>
                            <?php endif; ?>

                            <?php foreach ($reviewsReceived as $review): ?>
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

                <!-- ── REVIEWS LEFT TAB ── -->
                <div class="tab-panel" id="tab-reviews-left">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reviews left</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <?php if (empty($reviewsLeft)): ?>
                                <p class="seller-meta">No reviews yet.</p>
                            <?php endif; ?>

                            <?php foreach ($reviewsLeft as $review): ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>