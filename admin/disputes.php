<?php
require_once __DIR__ . '/../config/db.php';

// Filters
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';
$resolution = $_GET['resolution'] ?? '';

// Build query
$where  = [];
$params = [];
if ($search !== '') {
    $where[]  = '(t.transaction_id LIKE ? OR buyer.username LIKE ? OR seller.username LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Status filter
if ($status === 'open') {
    $where[]  = 'd.status = "Open"';
} elseif ($status === 'under_review') {
    $where[]  = 'd.status = "Under Review"';
} elseif ($status === 'resolved') {
    $where[]  = 'd.status = "Resolved"';
}

// Resolution filter
if ($resolution === 'refund_buyer') {
    $where[]  = 'd.resolution = "Buyer"';
} elseif ($resolution === 'refund_seller') {
    $where[]  = 'd.resolution = "Seller"';
}

$sql = '
    SELECT
        d.dispute_id,
        t.transaction_id,
        t.listing_id,
        l.title AS listing_title,
        t.amount,
        t.created_at,

        buyer.username AS buyer_username,
        seller.username AS seller_username,

        d.reason,
        d.status AS dispute_status,
        d.resolution

    FROM disputes d

    JOIN transactions t
        ON t.transaction_id = d.transaction_id

    JOIN users buyer
        ON buyer.user_id = t.buyer_id

    JOIN users seller
        ON seller.user_id = t.seller_id

    JOIN listings l
        ON l.listing_id = t.listing_id
';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' ORDER BY d.created_at DESC';
$stmt    = $pdo->prepare($sql);
$stmt->execute($params);
$disputes = $stmt->fetchAll();


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Escrow Disputes — Lootly Admin</title>
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
            <div class="page-heading">Escrow Disputes</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <form method="GET" action="">
                        <div class="filter-bar">
                            <input
                                type="text"
                                name="search"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Search by transaction ID or username"
                                class="search-input">
                            <select name="status" class="filter-select">
                                <option value="">All statuses</option>
                                <option value="open" <?= $status === 'open' ? 'selected' : '' ?>>Open</option>
                                <option value="resolved" <?= $status === 'resolved' ? 'selected' : '' ?>>Resolved</option>
                            </select>
                            <select name="resolution" class="filter-select">
                                <option value="">All resolutions</option>
                                <option value="refund_buyer" <?= $resolution === 'refund_buyer' ? 'selected' : '' ?>>Awarded to Buyer</option>
                                <option value="refund_seller" <?= $resolution === 'refund_seller' ? 'selected' : '' ?>>Awarded to Seller</option>
                            </select>
                            <button type="submit" class="btn-platform btn-primary-solid">Filter</button>
                            <a href="disputes.php" class="btn-platform btn-outline">Clear</a>
                        </div>
                    </form>

                    <!-- Disputes table -->
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th style="width:10%">Transaction</th>
                                <th style="width:14%">Buyer</th>
                                <th style="width:14%">Seller</th>
                                <th style="width:20%">Reason</th>
                                <th style="width:8%">Amount</th>
                                <th style="width:10%">Opened</th>
                                <th style="width:12%">Status</th>
                                <th style="width:12%">Resolution</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($disputes)): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4">No disputes found.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($disputes as $dispute): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($dispute['transaction_id']) ?></td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <span class="user-avatar"><?= strtoupper(substr($dispute['buyer_username'], 0, 2)) ?></span> <?php echo htmlspecialchars($dispute['buyer_username']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <span class="user-avatar"><?= strtoupper(substr($dispute['seller_username'], 0, 2)) ?></span> <?php echo htmlspecialchars($dispute['seller_username']); ?>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($dispute['reason']) ?></td>
                                        <td>$<?= number_format($dispute['amount'], 2) ?></td>
                                        <td><?= date('Y-m-d', strtotime($dispute['created_at'])) ?></td>
                                        <?php
                                        $statusClass = match ($dispute['dispute_status']) {
                                            'open'          => 'badge--danger',
                                            'resolved'      => 'badge--success',
                                            default         => 'badge--danger'
                                        };
                                        ?>
                                        <td><span class="badge <?= $statusClass ?>"><?= htmlspecialchars($dispute['dispute_status']) ?></span></td>
                                        <td><?= $dispute['resolution'] ? htmlspecialchars($dispute['resolution']) : 'N/A' ?></td>
                                        <td><a href="dispute-detail.php?dispute_id=<?= $dispute['dispute_id'] ?>" class="btn-platform btn-sm btn-outline">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>