<?php
require_once __DIR__ . '/../config/db.php';

$sql = '
    SELECT
        r.report_id,
        r.reporter_id,
        r.report_type,
        r.target_id,
        r.reason,
        r.created_at,
        r.status,
        r.resolved_at,
        r.resolution_note,

        reporter.username AS reporter_username,
        target.username   AS target_username

    FROM reports r

    JOIN users reporter
        ON reporter.user_id = r.reporter_id

    LEFT JOIN users target
        ON r.report_type = "user"
       AND target.user_id = r.target_id

    WHERE r.report_id = ?
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$report = $stmt->fetch();

$is_closed = in_array($report['status'], ['resolved', 'dismissed'], true);

$status_icon = match ($report['status']) {
    'resolved'  => 'bi-check-circle',
    'dismissed' => 'bi-x-circle',
    default     => 'bi-clock',
};

$status_badge_class = $is_closed ? 'badge--success' : 'badge--warning';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report #<?php echo $report['report_id']; ?> — Lootly Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Mobile warning (hidden on desktop) -->
    <div class="d-md-none alert alert-warning text-center p-3 admin-mobile-warning" style="display:none!important">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">

        <!-- ═══════════ SIDEBAR ═══════════ -->
        <?php include 'partials/sidebar.php'; ?>

        <!-- ═══════════ MAIN CONTENT ═══════════ -->
        <div class="main-content flex-grow-1">
            <!-- Page header -->
            <a href="reports.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to reports
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">Report #<?php echo $report['report_id']; ?></h1>
                <span class="badge badge--lg <?= $status_badge_class ?>">
                    <i class="bi <?= $status_icon ?>" style="font-size:9px"></i> <?php echo ucfirst(str_replace('_', ' ', $report['status'])); ?>
                </span>
            </div>

            <!-- Two-column detail layout -->
            <div class="row g-3 align-items-start">

                <!-- ── LEFT COLUMN (col-8): info panels ── -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- Report details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Report details</span>
                            <span class="badge badge--lg badge--info">
                                <i class="bi bi-tag" style="font-size:9px"></i> <?php echo ucfirst($report['report_type']); ?>
                            </span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>Report ID</label>
                                    <span>#<?php echo $report['report_id']; ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Submitted</label>
                                    <span><?php echo $report['created_at']; ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Reporter</label>
                                    <a href="user-detail.php?id=<?php echo $report['reporter_id']; ?>">
                                        @<?php echo $report['reporter_username']; ?>
                                    </a>
                                </div>
                                <div class="report-item">
                                    <label>Target</label>
                                    <a href="listing-detail.php?id=<?php echo $report['target_id']; ?>">Listing #<?php echo $report['target_id']; ?></a>
                                </div>
                            </div>

                            <div class="report-item">
                                <label>Reason</label>
                            </div>
                            <div class="report-reason-box">
                                <p><?php echo $report['reason']; ?></p>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- ── RIGHT COLUMN: action panel OR resolution panel ── -->
                <div class="col-md-4">
                    <?php if ($is_closed): ?>

                        <!-- Resolution outcome panel -->
                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Resolution</span>
                                <span class="badge badge--lg <?= $status_badge_class ?>">
                                    <i class="bi <?= $status_icon ?>" style="font-size:9px"></i> <?php echo ucfirst($report['status']); ?>
                                </span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-3">
                                <div class="report-item">
                                    <label>Resolved</label>
                                    <span><?php echo $report['resolved_at']; ?></span>
                                </div>

                                <div class="report-item">
                                    <label>Note</label>
                                </div>
                                <div class="report-reason-box">
                                    <?php if (!empty($report['resolution_note'])): ?>
                                        <p><?php echo htmlspecialchars($report['resolution_note']); ?></p>
                                    <?php else: ?>
                                        <p class="text-muted mb-0">No note was added.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <!-- Pending — action panel -->
                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Actions</span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-2">
                                <form method="POST" action="resolve-report.php">
                                    <input type="hidden" name="report_id" value="<?= $report['report_id'] ?>">

                                    <textarea name="resolution_note" class="form-textarea" rows="4"
                                        placeholder="Add a note before resolving…"></textarea>

                                    <div class="d-flex flex-column gap-2 mt-2">
                                        <button type="submit" name="action" value="resolved" class="btn-platform btn-primary-solid btn-block">
                                            <i class="bi bi-check-circle"></i> Mark as resolved
                                        </button>
                                        <button type="submit" name="action" value="dismissed" class="btn-platform btn-danger-outline btn-block">
                                            <i class="bi bi-x-circle"></i> Dismiss report
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>