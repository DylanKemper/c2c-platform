<?php
require_once __DIR__ . '/config/db.php';

// Fetch all active listings and their details, including the image and average rating
$sql = 'SELECT l.listing_id, l.title, l.category, l.description, l.price, l.condition,
               li.filename,
               ROUND(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.review_id) AS review_count
        FROM listings l
        LEFT JOIN listing_images li ON li.listing_id = l.listing_id AND li.is_primary = 1
        LEFT JOIN reviews r ON r.reviewee_id = l.seller_id AND r.role = "seller"
        WHERE l.status = "active"   -- filter only active listings
        GROUP BY l.listing_id
        ORDER BY l.created_at DESC';    // sort by most recent listings first

$stmt = $pdo->query($sql);
$listings = $stmt->fetchAll();  // returns array of listings with their details, including avg_rating and review_count
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'partials/navbar.php'; ?>
    <?php include 'partials/register-modal.php'; ?>
    <?php include 'partials/login-modal.php'; ?>
    <main class="flex-grow-1">
        <div class="card-grid">
            <?php foreach ($listings as $l): ?>
                <?php
                $avg     = (int) round($l['avg_rating'] ?? 0);
                $count   = (int) $l['review_count'];
                $img_src = $l['filename']
                    // if the listing has an image, use it; otherwise, use a placeholder image
                    ? 'uploads/listings/' . htmlspecialchars($l['filename'])
                    : 'Sample-Images/Sample-Image.jpg';
                ?>
                <div class="product-card">
                    <img class="product-card-img"
                        src="<?= $img_src ?>"
                        alt="<?= htmlspecialchars($l['title']) ?>">
                    <div class="product-card-body">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <span class="product-card-category">
                                <?= htmlspecialchars($l['category']) ?>
                            </span>

                            <span class="badge badge--sm badge--info">
                                <?= htmlspecialchars(ucfirst($l['condition'])) ?>
                            </span>
                        </div>

                        <h3 class="product-card-title">
                            <?= htmlspecialchars($l['title']) ?>
                        </h3>
                        <p class="product-card-desc"><?= htmlspecialchars($l['description']) ?></p>
                        <div class="product-card-footer">
                            <span class="product-card-price">R <?= number_format($l['price'], 2) ?></span>
                            <div class="product-card-stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <span class="star <?= $i > $avg ? 'star-empty' : '' ?>">&#9733;</span>
                                <?php endfor; ?>
                                <span class="rating-count">(<?= $count ?>)</span>
                            </div>
                        </div>
                        <a href="listing.php?id=<?= $l['listing_id'] ?>" class="btn-platform btn-primary-solid">View listing</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>
    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>