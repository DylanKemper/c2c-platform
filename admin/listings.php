<?php
$active_page = 'listings';
require_once __DIR__ . '/../config/db.php';

// Filters
$search   = trim($_GET['search'] ?? '');
$category = trim($_GET['category'] ?? '');

$where  = [];
$params = [];

if ($search !== '') {
    $where[]  = '(l.title LIKE ? OR u.username LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($category !== '') {
    $where[]  = 'l.category = ?';
    $params[] = $category;
}

$sql = 'SELECT l.listing_id, l.title, l.category, l.price, l.created_at,
               u.username, u.user_id
        FROM listings l
        JOIN users u ON u.user_id = l.seller_id';

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY l.created_at DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$listings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listings — Lootly Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-md-none alert alert-warning text-center p-3 mb-0 rounded-0">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">
        <?php include 'partials/sidebar.php'; ?>

        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Listings</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <form method="GET" class="filter-bar">
                        <input type="text" name="search" placeholder="Search title or seller"
                            class="search-input" value="<?= htmlspecialchars($search) ?>">
                        <select name="category" class="filter-select">
                            <option value="">All categories</option>
                            <option value="Audio" <?= $category === 'Audio'       ? 'selected' : '' ?>>Audio</option>
                            <option value="Electronics" <?= $category === 'Electronics' ? 'selected' : '' ?>>Electronics</option>
                            <option value="Photography" <?= $category === 'Photography' ? 'selected' : '' ?>>Photography</option>
                        </select>
                        <button type="submit" class="btn-platform btn-primary-solid">Filter</button>
                        <a href="listings.php" class="btn-platform btn-outline">Clear</a>
                    </form>

                    <!-- Listings table -->
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th style="width:6%">ID</th>
                                <th style="width:30%">Title</th>
                                <th style="width:14%">Category</th>
                                <th style="width:16%">Seller</th>
                                <th style="width:10%">Price</th>
                                <th style="width:14%">Listed</th>
                                <th style="width:10%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($listings)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No listings found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($listings as $l): ?>
                                    <tr class="clickable">
                                        <td><?= $l['listing_id'] ?></td>
                                        <td><?= htmlspecialchars($l['title']) ?></td>
                                        <td><?= htmlspecialchars($l['category']) ?></td>
                                        <td>
                                            <span class="avatar">
                                                <?= strtoupper(substr($l['username'], 0, 2)) ?>
                                            </span>
                                            <?= htmlspecialchars($l['username']) ?>
                                        </td>
                                        <td>R <?= number_format($l['price'], 0, '.', ',') ?></td>
                                        <td><?= date('d M Y', strtotime($l['created_at'])) ?></td>
                                        <td>
                                            <a href="listing-detail.php?id=<?= $l['listing_id'] ?>"
                                                class="btn-platform btn-primary-solid view-btn">View</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>