<?php
require_once __DIR__ . '/../config/db.php';

// Filters
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

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
if ($status === 'held') {
    $where[]  = 't.status = "Held"';
} elseif ($status === 'dispatched') {
    $where[]  = 't.status = "Dispatched"';
} elseif ($status === 'received') {
    $where[]  = 't.status = "Received"';
} elseif ($status === 'completed') {
    $where[]  = 't.status = "Completed"';
} elseif ($status === 'disputed') {
    $where[]  = 't.status = "Disputed"';
} elseif ($status === 'cancelled') {
    $where[]  = 't.status = "Cancelled"';
}

$sql = '
    SELECT
        t.transaction_id,
        t.listing_id,
        l.title AS listing_title,
        t.amount,
        t.created_at,
        t.status,

        buyer.username AS buyer_username,
        seller.username AS seller_username

    FROM transactions t

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
$sql .= ' ORDER BY t.created_at DESC';
$stmt    = $pdo->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions — Lootly Admin</title>
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
            <div class="page-heading">Transactions</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <form method="GET" action="">
                        <div class="filter-bar">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search transaction ID, buyer or seller"
                                value="<?= htmlspecialchars($search) ?>"
                                class="search-input">
                            <select name="status" class="filter-select">
                                <option value="">All statuses</option>
                                <option value="held" <?= $status === 'held' ? 'selected' : '' ?>>Held</option>
                                <option value="dispatched" <?= $status === 'dispatched' ? 'selected' : '' ?>>Dispatched</option>
                                <option value="received" <?= $status === 'received' ? 'selected' : '' ?>>Received</option>
                                <option value="completed" <?= $status === 'completed' ? 'selected' : '' ?>>Completed</option>
                                <option value="disputed" <?= $status === 'disputed' ? 'selected' : '' ?>>Disputed</option>
                                <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                            <button type="submit" class="btn-platform btn-primary-solid">Filter</button>
                            <a href="transactions.php" class="btn-platform btn-outline">Clear</a>
                        </div>
                    </form>

                    <!-- Transactions table -->
                    <table class="records-table">
                        <thead class="table-header">
                            <tr class="table-row">
                                <th style="width:10%">ID</th>
                                <th style="width:18%">Buyer</th>
                                <th style="width:18%">Seller</th>
                                <th style="width:24%">Listing</th>
                                <th style="width:10%">Amount</th>
                                <th style="width:10%">Date</th>
                                <th style="width:10%">Status</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody class="table-body">

                            <?php if (empty($transactions)): ?>
                                <tr class="table-row">
                                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">
                                        No transactions found.
                                    </td>
                                </tr>
                            <?php endif; ?>

                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="table-row clickable">
                                    <td><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span class="user-avatar"><?= strtoupper(substr($transaction['buyer_username'], 0, 2)) ?></span> <?php echo htmlspecialchars($transaction['buyer_username']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span class="user-avatar"><?= strtoupper(substr($transaction['seller_username'], 0, 2)) ?></span> <?php echo htmlspecialchars($transaction['seller_username']); ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($transaction['listing_title']); ?></td>
                                    <td>R <?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['created_at'])); ?></td>
                                    <?php
                                    $statusClass = match ($transaction['status']) {
                                        'held'        => 'badge--warning',
                                        'dispatched'  => 'badge--info',
                                        'completed'   => 'badge--success',
                                        'disputed'    => 'badge--danger',
                                        'cancelled'   => 'badge--neutral',
                                        default       => 'badge--neutral'
                                    };
                                    ?>
                                    <td><span class="badge <?= $statusClass ?>"><?php echo htmlspecialchars($transaction['status']); ?></span></td>
                                    <td><a href="transaction-detail.php?id=<?php echo htmlspecialchars($transaction['transaction_id']); ?>" class="btn-platform btn-primary-solid view-btn">View</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>