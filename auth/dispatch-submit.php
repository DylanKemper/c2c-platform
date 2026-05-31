<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Deny non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

// Auth check
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Get and validate transaction ID
$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
if ($transaction_id <= 0) {
    header('Location: ../index.php');
    exit;
}

$seller_id = $_SESSION['user_id'];

// Fetch transaction — seller_id and status guards are intentional:
// seller_id = ? proves ownership, status = 'held' prevents double-dispatch
$stmt = $pdo->prepare('
    SELECT transaction_id
    FROM transactions
    WHERE transaction_id = ?
    AND   seller_id      = ?
    AND   status         = "held"
');
$stmt->execute([$transaction_id, $seller_id]);
$transaction = $stmt->fetch();

// Redirect if not found, not owned by this seller, or already dispatched
if (!$transaction) {
    header('Location: ../user-dashboard.php?id=' . $_SESSION['user_id']);
    exit;
}

// Mark as dispatched
$stmt = $pdo->prepare('
    UPDATE transactions
    SET   status        = "dispatched",
          dispatched_at = NOW()
    WHERE transaction_id = ?
');
$stmt->execute([$transaction_id]);

header('Location: ../user-dashboard.php?id=' . $_SESSION['user_id']);
exit;