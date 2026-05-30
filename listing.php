<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid listing ID.');
}

$listingId = (int) $_GET['id'];

$sql = '
    SELECT
        l.listing_id,
        l.category_id,
        l.condition,
        l.title,
        l.price,
        l.description,
        l.created_at AS listing_created_at,
        l.status AS listing_status,

        li.filename,

        u.user_id,
        u.username,
        u.created_at AS user_created_at,

        ROUND(AVG(r.rating), 1) AS avg_rating,
        COUNT(DISTINCT r.review_id) AS review_count,

        COUNT(DISTINCT active_listings.listing_id) AS active_listing_count

    FROM listings l

    LEFT JOIN listing_images li
        ON li.listing_id = l.listing_id
        AND li.is_primary = 1

    LEFT JOIN users u
        ON u.user_id = l.seller_id

    LEFT JOIN reviews r
        ON r.reviewee_id = l.seller_id
        AND r.role = "seller"

    LEFT JOIN listings active_listings
        ON active_listings.seller_id = l.seller_id
        AND active_listings.status = "active"

    WHERE l.listing_id = ?

    GROUP BY
        l.listing_id,
        l.category_id,
        l.title,
        l.price,
        l.description,
        l.created_at,
        l.status,
        li.filename,
        u.user_id,
        u.username,
        u.created_at
';

$stmt = $pdo->prepare($sql);
$stmt->execute([$listingId]);

$listing = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    die('Listing not found.');
}

$is_seller = isset($_SESSION['user_id']) && $_SESSION['user_id'] === $listing['user_id'];
$is_guest  = !isset($_SESSION['user_id']);

$imageSrc = $listing['filename']
    ? 'uploads/listings/' . htmlspecialchars($listing['filename'])
    : 'Sample-Images/Sample-Image.jpg';

$sellerInitials = strtoupper(substr($listing['username'], 0, 2));

$avgRating = $listing['avg_rating']
    ? number_format($listing['avg_rating'], 1)
    : 'New';

$reviewCount = (int) $listing['review_count'];

$activeListingCount = (int) $listing['active_listing_count'];

$memberSince = date('Y', strtotime($listing['user_created_at']));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'partials/navbar.php'; ?>
    <?php include 'partials/register-modal.php'; ?>
    <?php include 'partials/login-modal.php'; ?>
    <nav class="custom-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="search.php?category=1">Electronics</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wireless Headphones</li>
        </ol>
    </nav>

    <main class="flex-grow-1">
        <div class="listing-page">
            <div class="product-listing-layout">

                <div class="image-panel">
                    <img
                        src="<?= $listing['filename'] ? 'uploads/listings/' . htmlspecialchars($listing['filename']) : 'Sample-Images/Sample-Image.jpg' ?>"
                        alt="Mechanical Keyboard"
                        class="listing-image-main">
                </div>

                <div class="listing-details-panel">
                    <div class="d-flex align-items-center justify-content-between gap-3 mb-2">
                        <div>
                            <h1 class="listing-title mb-0">
                                <?= htmlspecialchars($listing['title']) ?>
                            </h1>
                        </div>
                        <span class="badge badge--lg badge--info">
                            <?= htmlspecialchars($listing['condition']) ?>
                        </span>
                    </div>
                    <div class="listing-price-row">
                        <span class="listing-price">R <?= number_format($listing['price'], 2) ?></span>
                    </div>
                    <p class="listing-description-text">
                        <?= nl2br(htmlspecialchars($listing['description'])) ?>
                    </p>

                    <a href="user-profile.php?id=<?= $listing['user_id'] ?>" class="seller-card">
                        <div class="seller-avatar">
                            <?= htmlspecialchars($sellerInitials) ?>
                        </div>
                        <div class="seller-info">
                            <p class="seller-name">
                                <?= htmlspecialchars($listing['username']) ?>
                            </p>
                            <p class="seller-meta">
                                Member since <?= $memberSince ?>
                                &nbsp;·&nbsp;
                                <?= $activeListingCount ?> active listings
                            </p>
                        </div>
                        <span class="seller-rating">
                            <i class="bi bi-star-fill"></i>
                            <?php if ($reviewCount > 0): ?>
                                <?= $avgRating ?>
                                <span class="seller-rating-count">
                                    (<?= $reviewCount ?>)
                                </span>
                            <?php else: ?>
                                No reviews
                            <?php endif; ?>
                        </span>
                    </a>

                    <div class="listing-actions">
                        <?php if ($is_seller): ?>
                            <button class="btn-platform btn-primary-solid" disabled>This is your listing</button>

                        <?php elseif ($is_guest): ?>
                            <button class="btn-platform btn-primary-solid" disabled>Login to Purchase</button>

                        <?php else: ?>
                            <form action="payment.php" method="POST">
                                <input type="hidden" name="id" value="<?= $listing['listing_id'] ?>">
                                <button type="submit" class="btn-platform btn-primary-solid btn-block">Buy Now</button>
                            </form>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/register-modal.php'; ?>
    <?php include 'partials/login-modal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>