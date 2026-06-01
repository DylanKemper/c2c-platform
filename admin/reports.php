<?php
require_once __DIR__ . '/../config/db.php';

// ── Filters ────────────────────────────────────────────────
$search = trim($_GET['search'] ?? '');
$status = $_GET['status'] ?? '';

// ── Build query ────────────────────────────────────────────
$where  = [];
$params = [];
if ($search !== '') {
    $where[]  = '(r.reason LIKE ? OR u.username LIKE ? OR reporter.username LIKE ?)';
    $params[] = "%$search%";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status === 'open') {
    $where[]  = 'r.status = "open"';
} elseif ($status === 'under_review') {
    $where[]  = 'r.status = "under_review"';
} elseif ($status === 'resolved') {
    $where[]  = 'r.status = "resolved"';
}

$sql = '
    SELECT
        r.report_id,
        r.report_type,
        r.reason,
        r.created_at,
        r.status,

        reporter.username AS reporter_username,
        target.username AS target_username

    FROM reports r

    JOIN users reporter
        ON reporter.user_id = r.reporter_id

    LEFT JOIN users target
        ON r.report_type = "user"
       AND target.user_id = r.target_id
';
if ($where) {
    $sql .= ' WHERE ' . implode(' AND ', $where);
}
$sql .= ' ORDER BY r.created_at DESC';
$stmt    = $pdo->prepare($sql);
$stmt->execute($params);
$reports = $stmt->fetchAll();

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports — Lootly Admin</title>
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
            <div class="page-heading">Reports</div>

            <div class="panel">
                <div class="panel__body">
                    <!-- Filter bar -->
                    <div class="filter-bar">
                        <input type="text" placeholder="Search target or reporter" class="search-input">
                        <select class="filter-select">
                            <option value="">All types</option>
                            <option value="listing">Listing</option>
                            <option value="user">User</option>
                        </select>
                        <select class="filter-select">
                            <option value="">All statuses</option>
                            <option value="open">Open</option>
                            <option value="under_review">Under Review</option>
                            <option value="resolved">Resolved</option>
                        </select>
                        <button class="btn-platform btn-primary-solid">Filter</button>
                        <button class="btn-platform btn-outline">Clear</button>
                    </div>

                    <!-- Reports table -->
                    <table class="records-table">
                        <thead>
                            <tr>
                                <th style="width:5%">ID</th>
                                <th style="width:10%">Type</th>
                                <th style="width:20%">Target</th>
                                <th style="width:25%">Reason</th>
                                <th style="width:14%">Reported By</th>
                                <th style="width:12%">Date</th>
                                <th style="width:8%">Status</th>
                                <th style="width:6%"></th>
                            </tr>
                        </thead>
                        <tbody class="table-body">
                            <?php if (empty($reports)): ?>
                                <tr class="table-row">
                                    <td colspan="7" style="text-align:center; padding:2rem; color:var(--muted)">
                                        No reports found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($reports as $report): ?>
                                    <tr class="table-row clickable"
                                        onclick="window.location='report-detail.php?id=<?= $report['report_id'] ?>'">
                                        <td><?= $report['report_id'] ?></td>
                                        <td>
                                        <td>
                                            <?php if ($report['report_type'] === 'listing'): ?>
                                                <span class="badge badge--info">Listing</span>
                                            <?php elseif ($report['report_type'] === 'user'): ?>
                                                <span class="badge badge--success">User</span>
                                            <?php endif; ?>
                                        </td>
                                        <span class="badge badge--<?= $report['report_type'] ?>">
                                            <?= ucfirst($report['report_type']) ?>
                                        </span>
                                        </td>
                                        <td><?= htmlspecialchars($report['target_username'] ?? $report['target_id']) ?></td>
                                        <td class="reason-cell"><?= htmlspecialchars($report['reason']) ?></td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                <span class="user-avatar"><?= strtoupper(substr($report['reporter_username'], 0, 2)) ?></span>
                                                <?= htmlspecialchars($report['reporter_username']) ?>
                                            </div>
                                        </td>
                                        <td>12 Jan 2025</td>
                                        <td>
                                            <?php if ($report['status'] === 'open'): ?>
                                                <span class="badge badge--danger">Open</span>
                                            <?php elseif ($report['status'] === 'under_review'): ?>
                                                <span class="badge badge--warning">Under Review</span>
                                            <?php else: ?>
                                                <span class="badge badge--success">Resolved</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><a href="report-detail.php?id=<?= $report['report_id'] ?>" class="btn-platform btn-primary-solid view-btn">View</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>