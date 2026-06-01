<?php

require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: dashboard.php');
    exit;
}

$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
$listing_id      = (int) ($_POST['listing_id'] ?? 0);

if (!$transaction_id || !$listing_id) {
    header('Location: dashboard.php');
    exit;
}

/* =========================================================
   FETCH TRANSACTION (verify user is part of it)
========================================================= */
$txn_stmt = $pdo->prepare('
    SELECT
        transaction_id,
        listing_id,
        buyer_id,
        seller_id,
        amount,
        status,
        created_at
    FROM transactions
    WHERE transaction_id = ?
      AND listing_id = ?
      AND (buyer_id = ? OR seller_id = ?)
    LIMIT 1
');

$txn_stmt->execute([
    $transaction_id,
    $listing_id,
    $user_id,
    $user_id
]);

$transaction = $txn_stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    header('Location: dashboard.php');
    exit;
}

/* =========================================================
   Allow disputes only in these states
========================================================= */
if (!in_array($transaction['status'], ['dispatched', 'completed'], true)) {
    header('Location: dashboard.php');
    exit;
}

/* =========================================================
   PREVENT DUPLICATE DISPUTES
========================================================= */
$dispute_stmt = $pdo->prepare('
    SELECT dispute_id
    FROM disputes
    WHERE transaction_id = ?
    LIMIT 1
');

$dispute_stmt->execute([$transaction_id]);

if ($dispute_stmt->fetch()) {
    header('Location: dashboard.php');
    exit;
}

/* =========================================================
   FETCH LISTING
========================================================= */
$listing_stmt = $pdo->prepare('
    SELECT listing_id, title
    FROM listings
    WHERE listing_id = ?
    LIMIT 1
');

$listing_stmt->execute([$listing_id]);
$listing = $listing_stmt->fetch(PDO::FETCH_ASSOC);

if (!$listing) {
    header('Location: dashboard.php');
    exit;
}

/* =========================================================
   FETCH OTHER PARTY (for display)
========================================================= */
$other_user_id = ($transaction['buyer_id'] === $user_id)
    ? $transaction['seller_id']
    : $transaction['buyer_id'];

$user_stmt = $pdo->prepare('
    SELECT user_id, username
    FROM users
    WHERE user_id = ?
    LIMIT 1
');

$user_stmt->execute([$other_user_id]);
$other_user = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$other_user) {
    header('Location: dashboard.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Dispute</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">

                <a href="user-dashboard.php"
                    class="btn-platform btn-primary-solid mb-2">
                    <i class="bi bi-arrow-left"></i> Back to dashboard
                </a>

                <div class="panel">

                    <div class="panel__header">
                        <span class="panel__title">
                            Open Transaction Dispute
                        </span>
                    </div>

                    <div class="panel__body d-flex flex-column gap-3">

                        <p class="section-card-desc mb-0">
                            Listing: <strong><?= htmlspecialchars($listing['title']) ?></strong>
                        </p>

                        <p class="seller-meta mb-0">
                            Against: <strong>@<?= htmlspecialchars($other_user['username']) ?></strong>
                        </p>

                        <p class="seller-meta mb-0">
                            Transaction #<?= $transaction_id ?>
                        </p>

                        <p class="seller-meta mb-0">
                            Status:
                            <strong><?= htmlspecialchars(ucfirst($transaction['status'])) ?></strong>
                        </p>

                        <form method="POST" action="auth/dispute-submit.php">

                            <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">
                            <input type="hidden" name="listing_id" value="<?= $listing['listing_id'] ?>">

                            <div>
                                <label for="reason" class="form-label fw-semibold">
                                    Dispute reason
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea
                                    id="reason"
                                    name="reason"
                                    class="form-control"
                                    rows="6"
                                    maxlength="2000"
                                    required
                                    placeholder="Explain the issue with this transaction. Include as much detail as possible."></textarea>

                                <div class="form-text">
                                    Maximum 2000 characters.
                                </div>
                            </div>

                            <?php if (!empty($alreadyDisputed)): ?>
                                <div class="alert alert-warning py-2 small">
                                    You have already opened a dispute for this transaction.
                                </div>
                            <?php else: ?>
                                <div class="d-grid mt-2">
                                    <button
                                        type="submit"
                                        class="btn-platform btn-primary-solid">
                                        Submit Dispute
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>