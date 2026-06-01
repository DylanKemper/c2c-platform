<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/db.php';

// Dynamic WHERE clause and parameters for category filtering
$where  = ['l.status = "active"'];
$params = [];

// If a category filter is applied, add it to the WHERE clause and parameters
if (!empty($_GET['category_id'])) {
    $where[]  = 'l.category_id = ?';
    $params[] = (int) $_GET['category_id'];
}

// If a search query is provided, add a LIKE condition to the WHERE clause and parameters
if (!empty($_GET['title'])) {
    $where[]  = 'l.title LIKE ?';
    $params[] = '%' . $_GET['title'] . '%';
}

$sql = 'SELECT l.listing_id, l.title, l.category_id, l.description, l.price, l.condition,
               li.filename,
               ROUND(AVG(r.rating), 0) AS avg_rating,
               COUNT(r.review_id) AS review_count,
               c.name AS category_name
        FROM listings l
        LEFT JOIN listing_images li ON li.listing_id = l.listing_id AND li.is_primary = 1
        LEFT JOIN reviews r ON r.reviewee_id = l.seller_id AND r.reviewee_role = "seller"
        LEFT JOIN categories c ON c.category_id = l.category_id
        WHERE ' . implode(' AND ', $where) . '
        GROUP BY l.listing_id
        ORDER BY l.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll();
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
                    : 'Sample-Images/default-image.jpg';
                ?>
                <div class="product-card">
                    <img class="product-card-img"
                        src="<?= $img_src ?>"
                        alt="<?= htmlspecialchars($l['title']) ?>">
                    <div class="product-card-body">
                        <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                            <span class="product-card-category">
                                <?= htmlspecialchars($l['category_name']) ?>
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

        <div class="btn-platform btn-accent-solid">
            <a href="auth/logout.php">Logout</a>
        </div>

    </main>
    <?php include 'partials/footer.php'; ?>
    <?php include 'partials/register-modal.php'; ?>
    <?php include 'partials/login-modal.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>