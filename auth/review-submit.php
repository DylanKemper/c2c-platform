<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Auth guard
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard.php');
    exit;
}

$listing_id     = (int) ($_POST['listing_id']     ?? 0);
$seller_id      = (int) ($_POST['seller_id']      ?? 0);
$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
$rating         = (int) ($_POST['rating']         ?? 0);
$body           = trim($_POST['body'] ?? '');

// Basic validation
if (!$listing_id || !$seller_id || !$transaction_id) {
    header('Location: ../dashboard.php');
    exit;
}

// Guard: rating must be 1-5, body max 1000 chars
if ($rating < 1 || $rating > 5) {
    header('Location: ../review.php?error=' . urlencode('Please select a star rating.'));
    exit;
}

// Body is optional, but if provided must be <= 1000 chars
if (strlen($body) > 1000) {
    header('Location: ../review.php?error=' . urlencode('Comment must be 1000 characters or fewer.'));
    exit;
}

// Cannot review yourself
if ($user_id === $seller_id) {
    header('Location: ../dashboard.php');
    exit;
}

// Transaction must exist, belong to this buyer, and be completed
$txn_stmt = $pdo->prepare('
    SELECT transaction_id FROM transactions
    WHERE transaction_id = ?
      AND listing_id     = ?
      AND buyer_id       = ?
      AND status         = "completed"
    LIMIT 1
');
$txn_stmt->execute([$transaction_id, $listing_id, $user_id]);

if (!$txn_stmt->fetch()) {
    header('Location: ../dashboard.php');
    exit;
}

// No duplicate review
$rev_stmt = $pdo->prepare('
    SELECT review_id FROM reviews
    WHERE transaction_id = ?
      AND reviewer_id   = ?
      AND role          = "buyer"
    LIMIT 1
');
$rev_stmt->execute([$transaction_id, $user_id]);

if ($rev_stmt->fetch()) {
    header('Location: ../dashboard.php');
    exit;
}

// All guards passed — insert review
$insert = $pdo->prepare('
    INSERT INTO reviews (transaction_id, reviewer_id, reviewee_id, role, rating, body, created_at)
    VALUES (?, ?, ?, "buyer", ?, ?, NOW())
');
$insert->execute([$transaction_id, $user_id, $seller_id, $rating, $body ?: null]);

header('Location: ../user-dashboard.php');
exit;