// buyer confirms receipt of item, marking transaction as completed
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

$buyer_id = $_SESSION['user_id'];
// Fetch transaction — buyer_id and status guards are intentional:
// buyer_id = ? proves ownership, status = 'dispatched' prevents double-confirmation
$stmt = $pdo->prepare('
    SELECT transaction_id
    FROM transactions
    WHERE transaction_id = ?
    AND   buyer_id       = ?
    AND   status         = "dispatched"
');
$stmt->execute([$transaction_id, $buyer_id]);
$transaction = $stmt->fetch();

// Redirect if not found, not owned by this buyer, or not dispatched yet
if (!$transaction) {
    header('Location: ../user-dashboard.php?id=' . $_SESSION['user_id']);
    exit;
}

// Mark as completed
$stmt = $pdo->prepare('
    UPDATE transactions
    SET   status       = "completed",
          completed_at = NOW()
    WHERE transaction_id = ?
');
$stmt->execute([$transaction_id]);

header('Location: ../user-dashboard.php?id=' . $_SESSION['user_id']);
exit;   