<?php
require_once __DIR__ . '/config/db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Validate listing ID
$listing_id = (int) ($_GET['id'] ?? 0);
if (!$listing_id) {
    header('Location: index.php');
    exit;
}

// Fetch listing + seller info
$stmt = $pdo->prepare('
    SELECT l.*, u.username, u.user_id AS seller_user_id,
           ROUND(AVG(r.rating), 1) AS seller_rating,
           COUNT(r.review_id) AS review_count
    FROM listings l
    JOIN users u ON u.user_id = l.seller_id
    LEFT JOIN reviews r ON r.reviewee_id = l.seller_id AND r.role = "seller"
    WHERE l.listing_id = ? AND l.status = "active"
    GROUP BY l.listing_id
');
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

if (!$listing) {
    header('Location: index.php');
    exit;
}

// Block seller from buying their own listing
if ($listing['seller_user_id'] === $_SESSION['user_id']) {
    header('Location: listing.php?id=' . $listing_id);
    exit;
}

// Fetch primary image
$img_stmt = $pdo->prepare('SELECT filename FROM listing_images WHERE listing_id = ? AND is_primary = 1 LIMIT 1');
$img_stmt->execute([$listing_id]);
$image = $img_stmt->fetch();
$img_src = $image ? 'uploads/listings/' . htmlspecialchars($image['filename']) : 'Sample-Images/Sample-Image.jpg';

$avg = (int) round($listing['seller_rating'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase — <?= htmlspecialchars($listing['title']) ?> — Lootly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'partials/navbar.php'; ?>

    <!-- Breadcrumb -->
    <nav class="custom-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="listing.php?id=<?= $listing_id ?>">
                <?= htmlspecialchars($listing['title']) ?>
            </a></li>
            <li class="breadcrumb-item active" aria-current="page">Purchase</li>
        </ol>
    </nav>

    <main class="listing-form-page flex-grow-1">
        <div class="listing-form-container">

            <div class="listing-form-header">
                <h1 class="listing-form-title">Complete Your Purchase</h1>
                <p class="listing-form-subtitle">Review the listing details and confirm your order below.</p>
            </div>

            <form action="purchase-submit.php" method="POST">
                <input type="hidden" name="listing_id" value="<?= $listing_id ?>">

                <div class="listing-form-layout">

                    <!-- ================================
                         LEFT COLUMN — Purchase Sections
                    ================================ -->
                    <div class="listing-form-col">

                        <!-- Listing Summary -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-card-text"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Listing Summary</h2>
                                    <p class="section-card-desc">You are purchasing the following item.</p>
                                </div>
                            </div>

                            <div class="purchase-listing-summary">
                                <img
                                    class="purchase-summary-img"
                                    src="<?= $img_src ?>"
                                    alt="<?= htmlspecialchars($listing['title']) ?>">
                                <div class="purchase-summary-info">
                                    <span class="product-card-category"><?= htmlspecialchars($listing['category']) ?></span>
                                    <h3 class="purchase-summary-title"><?= htmlspecialchars($listing['title']) ?></h3>
                                    <p class="purchase-summary-desc"><?= htmlspecialchars($listing['description']) ?></p>
                                    <div class="purchase-summary-meta">
                                        <span class="purchase-meta-pill">
                                            <i class="bi bi-box-seam"></i>
                                            <?= ucfirst(str_replace('_', ' ', $listing['condition'])) ?>
                                        </span>
                                        <span class="purchase-meta-pill">
                                            <i class="bi bi-geo-alt"></i>
                                            <?= htmlspecialchars($listing['location'] ?? 'Not specified') ?>
                                        </span>
                                        <span class="purchase-meta-pill">
                                            <i class="bi bi-truck"></i>
                                            <?= match($listing['delivery_method'] ?? '') {
                                                'meetup' => 'Meet-up only',
                                                'post'   => 'Post only',
                                                'both'   => 'Meet-up or Post',
                                                default  => 'Not specified'
                                            } ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Info -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Seller</h2>
                                    <p class="section-card-desc">You are buying from this seller.</p>
                                </div>
                            </div>

                            <div class="purchase-seller-row">
                                <span class="avatar avatar--lg">
                                    <?= strtoupper(substr($listing['username'], 0, 2)) ?>
                                </span>
                                <div class="purchase-seller-info">
                                    <a href="profile.php?id=<?= $listing['seller_user_id'] ?>" class="purchase-seller-name">
                                        <?= htmlspecialchars($listing['username']) ?>
                                    </a>
                                    <div class="product-card-stars">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?= $i > $avg ? 'star-empty' : '' ?>">&#9733;</span>
                                        <?php endfor; ?>
                                        <span class="rating-count">(<?= $listing['review_count'] ?> reviews)</span>
                                    </div>
                                </div>
                                <a href="profile.php?id=<?= $listing['seller_user_id'] ?>"
                                    class="btn-platform btn-outline ms-auto">
                                    View Profile
                                </a>
                            </div>
                        </div>

                        <!-- Delivery Preference -->
                        <?php if (($listing['delivery_method'] ?? '') === 'both'): ?>
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Delivery Preference</h2>
                                    <p class="section-card-desc">The seller offers both options — choose your preference.</p>
                                </div>
                            </div>

                            <div class="listing-delivery-options">
                                <label class="listing-delivery-option">
                                    <input type="radio" name="delivery_preference" value="meetup" class="listing-radio-input" required>
                                    <span class="listing-delivery-card">
                                        <i class="bi bi-people listing-delivery-icon"></i>
                                        <span class="listing-delivery-title">Meet-up</span>
                                        <span class="listing-delivery-desc">Collect in person</span>
                                    </span>
                                </label>
                                <label class="listing-delivery-option">
                                    <input type="radio" name="delivery_preference" value="post" class="listing-radio-input">
                                    <span class="listing-delivery-card">
                                        <i class="bi bi-envelope listing-delivery-icon"></i>
                                        <span class="listing-delivery-title">Post</span>
                                        <span class="listing-delivery-desc">Shipped to your address</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Message to Seller -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-chat-left-text"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Message to Seller</h2>
                                    <p class="section-card-desc">Optional — let the seller know anything relevant.</p>
                                </div>
                            </div>

                            <div class="form-field">
                                <textarea
                                    class="form-textarea"
                                    name="buyer_message"
                                    rows="4"
                                    placeholder="e.g. Is the item still available? Can we meet Saturday?"
                                    maxlength="500"></textarea>
                                <span class="form-hint">Max 500 characters. Do not share payment details here.</span>
                            </div>
                        </div>

                    </div>

                    <!-- ================================
                         RIGHT COLUMN — Order Summary
                    ================================ -->
                    <div class="listing-preview-col">
                        <div class="listing-preview-sticky">

                            <!-- Price Breakdown -->
                            <div class="section-card">
                                <div class="section-card-header" style="margin-bottom: 16px;">
                                    <div class="section-card-icon">
                                        <i class="bi bi-receipt"></i>
                                    </div>
                                    <div>
                                        <h2 class="section-card-title">Order Summary</h2>
                                    </div>
                                </div>

                                <div class="purchase-price-breakdown">
                                    <div class="purchase-price-row">
                                        <span>Item price</span>
                                        <span>R <?= number_format($listing['price'], 2) ?></span>
                                    </div>
                                    <div class="purchase-price-row">
                                        <span>Buyer protection fee</span>
                                        <span>R <?= number_format($listing['price'] * 0.03, 2) ?></span>
                                    </div>
                                    <div class="purchase-price-divider"></div>
                                    <div class="purchase-price-row purchase-price-total">
                                        <span>Total</span>
                                        <span>R <?= number_format($listing['price'] * 1.03, 2) ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm & Escrow Notice -->
                            <div class="section-card listing-submit-section">
                                <p class="section-card-desc" style="margin-bottom: 14px;">
                                    <i class="bi bi-shield-check" style="color: var(--accent);"></i>
                                    Your payment is held securely in Lootly Escrow and only released to the seller once you confirm receipt.
                                </p>
                                <button type="submit" class="btn-platform btn-primary-solid btn-block">
                                    <i class="bi bi-lock"></i>
                                    Confirm Purchase
                                </button>
                                <a href="listing.php?id=<?= $listing_id ?>" class="btn-platform btn-outline btn-block">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </main>

    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>