<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard.php');
    exit;
}

$listing_id     = (int) ($_POST['listing_id']     ?? 0);
$reviewee_id    = (int) ($_POST['reviewee_id']    ?? 0);
$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
$rating         = (int) ($_POST['rating']         ?? 0);
$reviewee_role           = $_POST['reviewee_role'] ?? '';
$body           = trim($_POST['body'] ?? '');

if (!in_array($reviewee_role, ['buyer', 'seller'], true)) {
    header('Location: ../dashboard.php');
    exit;
}

if (!$listing_id || !$reviewee_id || !$transaction_id) {
    header('Location: ../dashboard.php');
    exit;
}

if ($rating < 1 || $rating > 5) {
    header('Location: ../review.php?error=' . urlencode('Please select a star rating.'));
    exit;
}

if (strlen($body) > 1000) {
    header('Location: ../review.php?error=' . urlencode('Comment must be 1000 characters or fewer.'));
    exit;
}

// Cannot review yourself
if ($user_id === $reviewee_id) {
    header('Location: ../dashboard.php');
    exit;
}

// Verify transaction and reviewee_role membership
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
    header('Location: ../dashboard.php');
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
    header('Location: ../dashboard.php');
    exit;
}

// All guards passed — insert
$insert = $pdo->prepare('
    INSERT INTO reviews (transaction_id, reviewer_id, reviewee_id, reviewee_role, rating, body, created_at)
    VALUES (?, ?, ?, ?, ?, ?, NOW())
');
$insert->execute([$transaction_id, $user_id, $reviewee_id, $reviewee_role, $rating, $body ?: null]);

header('Location: ../user-dashboard.php?reviewed=1');
exit;