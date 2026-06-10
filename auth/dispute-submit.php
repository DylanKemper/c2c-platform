<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Auth guard
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];

// POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../user-dashboard.php');
    exit;
}

$transaction_id = (int) ($_POST['transaction_id'] ?? 0);
$reason         = trim($_POST['reason'] ?? '');

// Validate inputs
if (!$transaction_id || $reason === '') {
    header('Location: ../user-dashboard.php');
    exit;
}

if (strlen($reason) > 1000) {
    header('Location: ../dispute.php?transaction_id=' . $transaction_id . '&error=Reason+must+be+under+1000+characters.');
    exit;
}

// Verify transaction exists and this user was part of it
$txn_stmt = $pdo->prepare('
    SELECT transaction_id, buyer_id, seller_id, status
    FROM transactions
    WHERE transaction_id = ?
      AND (buyer_id = ? OR seller_id = ?)
    LIMIT 1
');
$txn_stmt->execute([$transaction_id, $user_id, $user_id]);
$transaction = $txn_stmt->fetch();

if (!$transaction) {
    header('Location: ../user-dashboard.php');
    exit;
}

// Only allow disputes on held or completed transactions
if (!in_array($transaction['status'], ['held', 'dispatched'], true)) {
    header('Location: ../user-dashboard.php');
    exit;
}

// Block duplicate disputes from the same user on the same transaction
$dup_stmt = $pdo->prepare('
    SELECT dispute_id FROM disputes
    WHERE transaction_id = ?
      AND raised_by       = ?
    LIMIT 1
');
$dup_stmt->execute([$transaction_id, $user_id]);

if ($dup_stmt->fetch()) {
    header('Location: ../dispute.php?transaction_id=' . $transaction_id . '&error=You+have+already+filed+a+dispute+for+this+transaction.');
    exit;
}

// All checks passed — insert dispute
$insert = $pdo->prepare('
    INSERT INTO disputes (transaction_id, raised_by, reason, status, created_at)
    VALUES (?, ?, ?, "open", NOW())
');
$insert->execute([$transaction_id, $user_id, $reason]);

header('Location: ../user-dashboard.php?dispute=success');
exit;
