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

$listing_id     = (int) ($_POST['listing_id']     ?? 0);
$reviewee_id    = (int) ($_POST['reviewee_id']    ?? 0);
$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
$reviewee_role           = $_POST['reviewee_role'] ?? '';

// reviewee_role must be buyer or seller — the reviewer's role in the transaction
if (!in_array($reviewee_role, ['buyer', 'seller'], true)) {
    header('Location: dashboard.php');
    exit;
}

if (!$listing_id || !$reviewee_id || !$transaction_id) {
    header('Location: dashboard.php');
    exit;
}

// Cannot review yourself
if ($user_id === $reviewee_id) {
    header('Location: dashboard.php');
    exit;
}

// Verify transaction exists and this user was part of it in the claimed reviewee_role
// if the reviewee_role of the reviewee is seller, then the reviewer must be buyer
if ($reviewee_role === 'seller') {
    $txn_stmt = $pdo->prepare('
        SELECT transaction_id FROM transactions
        WHERE transaction_id = ?
          AND listing_id     = ?
          AND buyer_id       = ?
          AND status         = "completed"
        LIMIT 1
    ');
    $txn_stmt->execute([$transaction_id, $listing_id, $user_id]);
} else {
    $txn_stmt = $pdo->prepare('
        SELECT transaction_id FROM transactions
        WHERE transaction_id = ?
          AND listing_id     = ?
          AND seller_id      = ?
          AND status         = "completed"
        LIMIT 1
    ');
    $txn_stmt->execute([$transaction_id, $listing_id, $user_id]);
}

if (!$txn_stmt->fetch()) {
    header('Location: dashboard.php');
    exit;
}

// No duplicate review
$rev_stmt = $pdo->prepare('
    SELECT review_id FROM reviews
    WHERE transaction_id = ?
      AND reviewer_id   = ?
      AND reviewee_role          = ?
    LIMIT 1
');
$rev_stmt->execute([$transaction_id, $user_id, $reviewee_role]);

if ($rev_stmt->fetch()) {
    header('Location: dashboard.php');
    exit;
}

// Fetch reviewee username for display — never trust POST for display values
$reviewee_stmt = $pdo->prepare('SELECT username FROM users WHERE user_id = ? LIMIT 1');
$reviewee_stmt->execute([$reviewee_id]);
$reviewee = $reviewee_stmt->fetch();

$listing_stmt = $pdo->prepare('SELECT title FROM listings WHERE listing_id = ? LIMIT 1');
$listing_stmt->execute([$listing_id]);
$listing = $listing_stmt->fetch();

if (!$reviewee || !$listing) {
    header('Location: dashboard.php');
    exit;
}

$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review @<?= htmlspecialchars($reviewee['username']) ?></title>
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

                <a href="user-dashboard.php" class="btn-platform btn-primary-solid" style="margin-bottom: 8px;">
                    <i class="bi bi-arrow-left"></i> Back to dashboard
                </a>

                <div class="panel">

                    <div class="panel__header">
                        <span class="panel__title">
                            Review @<?= htmlspecialchars($reviewee['username']) ?>
                        </span>
                    </div>

                    <div class="panel__body d-flex flex-column gap-3">

                        <p class="section-card-desc mb-0">
                            Re: <?= htmlspecialchars($listing['title']) ?>
                        </p>

                        <?php if ($error): ?>
                            <div class="alert alert-danger py-2 small mb-0">
                                <?= htmlspecialchars($error) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="auth/review-submit.php">

                            <input type="hidden" name="listing_id" value="<?= $listing_id ?>">
                            <input type="hidden" name="reviewee_id" value="<?= $reviewee_id ?>">
                            <input type="hidden" name="transaction_id" value="<?= $transaction_id ?>">
                            <input type="hidden" name="reviewee_role" value="<?= $reviewee_role ?>">

                            <!-- Star rating -->
                            <div>
                                <label class="form-label fw-semibold">
                                    Rating <span class="text-danger">*</span>
                                </label>

                                <div class="rating-stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input
                                            type="radio"
                                            name="rating"
                                            id="star<?= $i ?>"
                                            value="<?= $i ?>"
                                            required>

                                        <label for="star<?= $i ?>">★</label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <!-- Review body -->
                            <div>
                                <label for="body" class="form-label fw-semibold">
                                    Comment
                                    <span class="text-muted fw-normal">(optional)</span>
                                </label>

                                <textarea
                                    class="form-control"
                                    id="body"
                                    name="body"
                                    rows="4"
                                    maxlength="1000"
                                    placeholder="How was your experience with this seller?"></textarea>
                            </div>

                            <div class="d-grid mt-2">
                                <button type="submit" class="btn-platform btn-primary-solid">
                                    Submit Review
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