<?php
require_once __DIR__ . '/../config/db.php';

// ── Filters ────────────────────────────────────────────────
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

// ── Build query ────────────────────────────────────────────
$where  = [];
$params = [];

if ($search !== '') {
    $where[]  = '(u.username LIKE ? OR u.email LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status === 'banned') {
    $where[]  = 'u.is_banned = 1';
} elseif ($status === 'suspended') {
    $where[]  = 'u.is_suspended = 1 AND u.suspended_until > NOW()';
} elseif ($status === 'active') {
    $where[]  = 'u.is_banned = 0 AND (u.is_suspended = 0 OR u.suspended_until <= NOW())';
}

$sql = '
    SELECT
        u.user_id,
        u.username,
        u.email,
        u.created_at,
        u.is_banned,
        u.is_suspended,
        u.suspended_until,
        COUNT(DISTINCT l.listing_id)                                      AS listing_count,
        ROUND(AVG(CASE WHEN r.role = "seller" THEN r.rating END), 1)     AS seller_rating
    FROM users u
    LEFT JOIN listings l ON l.seller_id = u.user_id
    LEFT JOIN reviews  r ON r.reviewee_id = u.user_id
';

if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}

$sql .= ' GROUP BY u.user_id ORDER BY u.created_at DESC';

$stmt  = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

// ── Status helper ──────────────────────────────────────────
function getUserStatus(array $user): string {
    if ($user['is_banned']) return 'banned';
    if ($user['is_suspended'] && strtotime($user['suspended_until']) > time()) return 'suspended';
    return 'active';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users — Lootly Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="d-md-none alert alert-warning text-center p-3 mb-0 rounded-0">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">
        <?php
        $active_page = 'users';
        include 'partials/sidebar.php';
        ?>

        <div class="main-content flex-grow-1 p-4">
            <div class="page-heading">Users</div>

            <div class="panel">
                <div class="panel__body">

                    <!-- Filter bar -->
                    <form method="GET" action="">
                        <div class="filter-bar">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search username or email"
                                value="<?= htmlspecialchars($search) ?>"
                                class="search-input">
                            <select name="status" class="filter-select">
                                <option value="">All statuses</option>
                                <option value="active"    <?= $status === 'active'    ? 'selected' : '' ?>>Active</option>
                                <option value="banned"    <?= $status === 'banned'    ? 'selected' : '' ?>>Banned</option>
                                <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                            </select>
                            <button type="submit" class="btn-platform btn-primary-solid">Filter</button>
                            <a href="users.php" class="btn-platform btn-outline">Clear</a>
                        </div>
                    </form>

                    <table class="records-table">
                        <thead class="table-header">
                            <tr class="table-row">
                                <th style="width:28%">User</th>
                                <th style="width:24%">Email</th>
                                <th style="width:14%">Joined</th>
                                <th style="width:10%">Listings</th>
                                <th style="width:10%">Status</th>
                                <th style="width:8%">Rating</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>

                        <tbody class="table-body">
                            <?php if (empty($users)): ?>
                                <tr class="table-row">
                                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">
                                        No users found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user):
                                    $initials = strtoupper(substr($user['username'], 0, 2));
                                    $status   = getUserStatus($user);
                                    $joined   = date('d M Y', strtotime($user['created_at']));
                                    $rating   = $user['seller_rating'] ?? '—';
                                ?>
                                <tr class="table-row clickable"
                                    onclick="window.location='user-detail.php?id=<?= $user['user_id'] ?>'">
                                    <td>
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span class="user-avatar"><?= $initials ?></span>
                                            <?= htmlspecialchars($user['username']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= $joined ?></td>
                                    <td><?= $user['listing_count'] ?></td>
                                    <td>
                                        <?php if ($status === 'banned'): ?>
                                            <span class="badge badge--danger">Banned</span>
                                        <?php elseif ($status === 'suspended'): ?>
                                            <span class="badge badge--warning">Suspended</span>
                                        <?php else: ?>
                                            <span class="badge badge--success">Active</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $rating ?></td>
                                    <td>
                                        <a href="user-detail.php?id=<?= $user['user_id'] ?>"
                                           class="btn-platform btn-primary-solid view-btn"
                                           onclick="event.stopPropagation()">
                                            View
                                        </a>
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