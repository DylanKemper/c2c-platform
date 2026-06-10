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
$userStmt = $pdo->prepare('
    SELECT user_id, username, created_at
    FROM users
    WHERE user_id = ?
');
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
$sellerStatsStmt = $pdo->prepare('
    SELECT
        COALESCE(ROUND(AVG(rating), 1), 0) AS avg_rating,
        COUNT(review_id) AS review_count
    FROM reviews
    WHERE reviewee_id = ?
      AND reviewee_role = "seller"
');
$sellerStatsStmt->execute([$userId]);
$sellerStats = $sellerStatsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   4. BUYER REVIEW STATS
========================================================= */
$buyerStatsStmt = $pdo->prepare('
    SELECT
        COALESCE(ROUND(AVG(rating), 1), 0) AS avg_rating,
        COUNT(review_id) AS review_count
    FROM reviews
    WHERE reviewee_id = ?
      AND reviewee_role = "buyer"
');
$buyerStatsStmt->execute([$userId]);
$buyerStats = $buyerStatsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   5. ACTIVE LISTINGS
========================================================= */
$activeListingsStmt = $pdo->prepare('
    SELECT
        l.listing_id,
        l.title,
        l.category_id,
        l.price,
        l.`condition`,
        l.created_at,
        c.name AS category_name
    FROM listings l
    LEFT JOIN categories c ON c.category_id = l.category_id
    WHERE l.seller_id = ?
      AND l.status = "active"
    ORDER BY l.created_at DESC
');
$activeListingsStmt->execute([$userId]);
$activeListings = $activeListingsStmt->fetchAll(PDO::FETCH_ASSOC);
$activeListingCount = count($activeListings);

/* =========================================================
   6. SOLD LISTINGS (with buyer review status)
========================================================= */
// reviewer_id = ? filters the LEFT JOIN to only this seller's review of the buyer.
// reviewee_role = "seller" ensures we don't accidentally match the buyer's review of the seller.
$soldListingsStmt = $pdo->prepare('
    SELECT
        l.listing_id,
        l.title,
        l.price,
        t.transaction_id,
        t.buyer_id,
        t.status,
        t.created_at,
        r.review_id
    FROM transactions t
    INNER JOIN listings l ON l.listing_id = t.listing_id
    LEFT JOIN reviews r
        ON  r.transaction_id = t.transaction_id
        AND r.reviewer_id    = ?
        AND r.reviewee_role           = "buyer"
    WHERE t.seller_id = ?
    ORDER BY t.created_at DESC
');
$soldListingsStmt->execute([$userId, $userId]);
$soldListings = $soldListingsStmt->fetchAll(PDO::FETCH_ASSOC);
$totalItemsSold = count($soldListings);

/* =========================================================
   7. ITEMS BOUGHT (with seller review status)
========================================================= */
// reviewer_id = ? filters the LEFT JOIN to only this buyer's review of the seller.
// reviewee_role = "buyer" ensures we don't accidentally match the seller's review of the buyer.
$itemsBoughtStmt = $pdo->prepare('
    SELECT
        l.listing_id,
        l.title,
        l.price,
        t.created_at AS purchased_at,
        t.seller_id,
        t.transaction_id,
        t.status,
        u.username AS seller_username,
        r.review_id
    FROM transactions t
    INNER JOIN listings l ON l.listing_id = t.listing_id
    INNER JOIN users u    ON u.user_id    = t.seller_id
    LEFT JOIN reviews r
        ON  r.transaction_id = t.transaction_id
        AND r.reviewer_id    = ?
        AND r.reviewee_role           = "seller"
    WHERE t.buyer_id = ?
    ORDER BY t.created_at DESC
');
$itemsBoughtStmt->execute([$userId, $userId]);
$itemsBought = $itemsBoughtStmt->fetchAll(PDO::FETCH_ASSOC);
$totalItemsBought = count($itemsBought);

/* =========================================================
   8. COMPLETED TRANSACTIONS
========================================================= */
$completedTransactionsStmt = $pdo->prepare('
    SELECT COUNT(*) AS completed_transaction_count
    FROM transactions
    WHERE (seller_id = ? OR buyer_id = ?)
      AND status = "completed"
');
$completedTransactionsStmt->execute([$userId, $userId]);
$completedTransactions = $completedTransactionsStmt->fetch(PDO::FETCH_ASSOC);

/* =========================================================
   9. REVIEWS RECEIVED
========================================================= */
$reviewsStmt = $pdo->prepare('
    SELECT
        r.review_id,
        r.rating,
        r.body,
        r.reviewee_role,
        r.created_at,
        reviewer.user_id   AS reviewer_id,
        reviewer.username  AS reviewer_username,
        l.listing_id,
        l.title            AS listing_title
    FROM reviews r
    LEFT JOIN users reviewer ON reviewer.user_id    = r.reviewer_id
    LEFT JOIN transactions t ON t.transaction_id    = r.transaction_id
    LEFT JOIN listings l     ON l.listing_id        = t.listing_id
    WHERE r.reviewee_id = ?
    ORDER BY r.created_at DESC
');
$reviewsStmt->execute([$userId]);
$reviewsReceived = $reviewsStmt->fetchAll(PDO::FETCH_ASSOC);
$reviewsReceivedCount = count($reviewsReceived);

/* =========================================================
   10. REVIEWS LEFT
========================================================= */
$reviewsLeftStmt = $pdo->prepare('
    SELECT
        r.review_id,
        r.rating,
        r.body,
        r.reviewee_role,
        r.created_at,
        reviewee.user_id   AS reviewee_id,
        reviewee.username  AS reviewee_username,
        l.listing_id,
        l.title            AS listing_title
    FROM reviews r
    LEFT JOIN users reviewee ON reviewee.user_id    = r.reviewee_id
    LEFT JOIN transactions t ON t.transaction_id    = r.transaction_id
    LEFT JOIN listings l     ON l.listing_id        = t.listing_id
    WHERE r.reviewer_id = ?
    ORDER BY r.created_at DESC
');
$reviewsLeftStmt->execute([$userId]);
$reviewsLeft = $reviewsLeftStmt->fetchAll(PDO::FETCH_ASSOC);
$reviewsLeftCount = count($reviewsLeft);

/* =========================================================
   11. CLEAN VARIABLES
========================================================= */
$username                  = $user['username'];
$memberSince               = date('F Y', strtotime($user['created_at']));
$sellerAvgRating           = $sellerStats['avg_rating']  ?? 0;
$sellerReviewCount         = $sellerStats['review_count'] ?? 0;
$buyerAvgRating            = $buyerStats['avg_rating']   ?? 0;
$buyerReviewCount          = $buyerStats['review_count'] ?? 0;
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

                <!-- Identity card -->
                <div class="panel">
                    <div class="panel__body text-center d-flex flex-column align-items-center gap-2">
                        <div class="user-avatar"><?= htmlspecialchars($userInitials) ?></div>
                        <div>
                            <div class="user-name">@<?= htmlspecialchars($username) ?></div>
                            <div class="user-joined-date">Member since <?= htmlspecialchars($memberSince) ?></div>
                        </div>
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
                                <?= renderStars($sellerAvgRating) ?>
                                <span class="seller-rating"><?= number_format($sellerAvgRating, 1) ?></span>
                                <span class="rating-count">(<?= $sellerReviewCount ?>)</span>
                            </div>
                        </div>

                        <!-- Buyer rating -->
                        <div>
                            <div class="seller-meta mb-1">Buyer rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <?= renderStars($buyerAvgRating) ?>
                                <span class="seller-rating"><?= number_format($buyerAvgRating, 1) ?></span>
                                <span class="rating-count">(<?= $buyerReviewCount ?>)</span>
                            </div>
                        </div>

                        <hr class="m-0">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-arrow-left-right me-1"></i>Completed transactions</span>
                            <span class="seller-name"><?= $completedTransactionCount ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-tag me-1"></i>Active listings</span>
                            <span class="seller-name"><?= $activeListingCount ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-bag-check me-1"></i>Items sold</span>
                            <span class="seller-name"><?= $totalItemsSold ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-bag me-1"></i>Items bought</span>
                            <span class="seller-name"><?= $totalItemsBought ?></span>
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
                                            · <?= htmlspecialchars($listing['category_name']) ?>
                                            · <?= htmlspecialchars($listing['condition']) ?>
                                            · <?= date('M Y', strtotime($listing['created_at'])) ?>
                                        </p>
                                    </div>
                                    <a href="listing.php?id=<?= $listing['listing_id'] ?>" class="btn-platform btn-outline btn-sm">
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
                                <?php $isReviewed = !empty($listing['review_id']); ?>

                                <div class="d-flex justify-content-between align-items-center gap-3">
                                    <div>
                                        <p class="seller-name mb-0"><?= htmlspecialchars($listing['title']) ?></p>
                                        <p class="seller-meta mb-0">
                                            R <?= number_format($listing['price'], 2) ?>
                                            · <?= date('M Y', strtotime($listing['created_at'])) ?>
                                        </p>

                                        <!-- Review badge — only once completed -->
                                        <?php if ($listing['status'] === 'completed'): ?>
                                            <?php if ($isReviewed): ?>
                                                <span class="badge badge--md badge--success">
                                                    <i class="bi bi-star-fill"></i>&nbsp; Reviewed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge--md badge--warning">
                                                    <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Review pending
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-2 justify-content-end align-items-center flex-shrink-0">
                                        <a href="listing.php?id=<?= $listing['listing_id'] ?>" class="btn-platform btn-outline btn-sm">
                                            View
                                        </a>

                                        <?php if ($listing['status'] === 'held'): ?>
                                            <form method="POST" action="auth/dispatch-submit.php">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $listing['transaction_id'] ?>">
                                                <button type="submit" class="btn-platform btn-primary-solid btn-sm">
                                                    Confirm Dispatched
                                                </button>
                                            </form>
                                        <?php elseif ($listing['status'] === 'dispatched'): ?>
                                            <span class="badge badge--lg badge--warning">Awaiting buyer confirmation</span>
                                        <?php elseif ($listing['status'] === 'completed' && !$isReviewed): ?>
                                            <!-- Seller reviews the buyer — reviewee_id is the buyer -->
                                            <form method="POST" action="review.php">
                                                <input type="hidden" name="listing_id" value="<?= (int) $listing['listing_id'] ?>">
                                                <input type="hidden" name="reviewee_id" value="<?= (int) $listing['buyer_id'] ?>">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $listing['transaction_id'] ?>">
                                                <input type="hidden" name="reviewee_role" value="buyer">
                                                <button type="submit" class="btn-platform btn-primary-solid btn-sm">
                                                    <i class="bi bi-star"></i> Review Buyer
                                                </button>
                                            </form>
                                        <?php elseif ($listing['status'] === 'completed' && $isReviewed): ?>
                                            <span class="badge badge--lg badge--success">Completed</span>
                                        <?php endif; ?>
                                    </div>
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
                                <?php $isReviewed = !empty($listing['review_id']); ?>

                                <div class="d-flex justify-content-between align-items-center gap-3">

                                    <div>
                                        <p class="seller-name mb-0"><?= htmlspecialchars($listing['title']) ?></p>
                                        <p class="seller-meta mb-0">
                                            R <?= number_format($listing['price'], 2) ?>
                                            · <?= date('M Y', strtotime($listing['purchased_at'])) ?>
                                        </p>

                                        <?php if ($listing['status'] === 'held'): ?>
                                            <span class="badge badge--md badge--warning">
                                                <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Awaiting dispatch
                                            </span>
                                        <?php elseif ($listing['status'] === 'dispatched'): ?>
                                            <span class="badge badge--md badge--info">
                                                <i class="bi bi-truck"></i>&nbsp; Dispatched
                                            </span>
                                        <?php elseif ($listing['status'] === 'completed'): ?>
                                            <span class="badge badge--md badge--success">
                                                <i class="bi bi-check-circle"></i>&nbsp; Completed
                                            </span>
                                        <?php endif; ?>

                                        <?php if ($listing['status'] === 'completed'): ?>
                                            <?php if ($isReviewed): ?>
                                                <span class="badge badge--md badge--success">
                                                    <i class="bi bi-star-fill"></i>&nbsp; Reviewed
                                                </span>
                                            <?php else: ?>
                                                <span class="badge badge--md badge--warning">
                                                    <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Review pending
                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <div class="d-flex gap-2 flex-wrap justify-content-end">
                                        <a href="listing.php?id=<?= $listing['listing_id'] ?>" class="btn-platform btn-outline btn-sm">
                                            View
                                        </a>

                                        <?php if ($listing['status'] === 'dispatched'): ?>
                                            <form method="POST" action="auth/receipt-submit.php">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $listing['transaction_id'] ?>">
                                                <button type="submit" class="btn-platform btn-primary-solid btn-sm">
                                                    <i class="bi bi-check-lg"></i> Confirm Receipt
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if (in_array($listing['status'], ['held', 'dispatched'])): ?>
                                            <form method="POST" action="open-dispute.php">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $listing['transaction_id'] ?>">
                                                <input type="hidden" name="listing_id" value="<?=  (int) $listing['listing_id'] ?>">
                                                <button type="submit" class="btn-platform btn-danger-outline btn-sm">
                                                    <i class="bi bi-flag"></i> Dispute Transaction
                                                </button>
                                            </form>
                                        <?php endif; ?>

                                        <?php if ($listing['status'] === 'completed' && !$isReviewed): ?>
                                            <!-- Buyer reviews the seller — reviewee_id is the seller -->
                                            <form method="POST" action="review.php">
                                                <input type="hidden" name="listing_id" value="<?= (int) $listing['listing_id'] ?>">
                                                <input type="hidden" name="reviewee_id" value="<?= (int) $listing['seller_id'] ?>">
                                                <input type="hidden" name="transaction_id" value="<?= (int) $listing['transaction_id'] ?>">
                                                <input type="hidden" name="reviewee_role" value="seller">
                                                <button type="submit" class="btn-platform btn-primary-solid btn-sm">
                                                    <i class="bi bi-star"></i> Review
                                                </button>
                                            </form>
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
                                        <span class="seller-meta">
                                            <?= date('M Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>
                                    <div class="product-card-stars">
                                        <?= renderStars($review['rating']) ?>
                                    </div>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($review['body'])) ?></p>
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
                                        <span class="seller-meta">
                                            <?= date('M Y', strtotime($review['created_at'])) ?>
                                        </span>
                                    </div>
                                    <div class="product-card-stars">
                                        <?= renderStars($review['rating']) ?>
                                    </div>
                                    <p class="mb-0"><?= nl2br(htmlspecialchars($review['body'])) ?></p>
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

</body>

</html>