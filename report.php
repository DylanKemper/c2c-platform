<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

// If user is in a session (logged in)
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$user_id = (int) $_SESSION['user_id'];

// Only accept post
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$target_id   = (int) ($_POST['target_id']   ?? 0);
$report_type = $_POST['report_type']        ?? '';

// report_type must be 'listing' or 'user'
if (!in_array($report_type, ['listing', 'user'], true)) {
    header('Location: index.php');
    exit;
}

if (!$target_id) {
    header('Location: index.php');
    exit;
}

// Fetch a label for display so the user can confirm what they're reporting
if ($report_type === 'listing') {
    $stmt = $pdo->prepare('SELECT title FROM listings WHERE listing_id = ? LIMIT 1');
    $stmt->execute([$target_id]);
    $target = $stmt->fetch();
    $target_label = $target ? $target['title'] : null;
} else {
    $stmt = $pdo->prepare('SELECT username FROM users WHERE user_id = ? LIMIT 1');
    $stmt->execute([$target_id]);
    $target = $stmt->fetch();
    $target_label = $target ? '@' . $target['username'] : null;
}

// Can't report something that doesn't exist
if (!$target_label) {
    header('Location: index.php');
    exit;
}

// Can't report yourself
if ($report_type === 'user' && $target_id === $user_id) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <?php require_once __DIR__ . '/partials/navbar.php'; ?>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">

                <a href="index.php" class="btn-platform btn-primary-solid" style="margin-bottom: 8px;">
                    <i class="bi bi-arrow-left"></i> Cancel Report
                </a>

                <div class="panel">

                    <div class="panel__header">
                        <span class="panel__title">
                            Report <?= $report_type === 'listing' ? 'Listing' : 'User' ?>
                        </span>
                    </div>

                    <div class="panel__body d-flex flex-column gap-3">

                        <p class="section-card-desc mb-0">
                            Re: <?= htmlspecialchars($target_label) ?>
                        </p>

                        <form method="POST" action="auth/report-submit.php">

                            <input type="hidden" name="target_id" value="<?= $target_id ?>">
                            <input type="hidden" name="report_type" value="<?= htmlspecialchars($report_type) ?>">

                            <!-- Report body -->
                            <div>
                                <label for="reason" class="form-label fw-semibold">
                                    Reason
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    class="form-control"
                                    id="reason"
                                    name="reason"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="State the reason for your report."
                                    required></textarea>
                            </div>

                            <div class="d-grid mt-2">
                                <button type="submit" class="btn-platform btn-danger-outline">
                                    Submit Report
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>