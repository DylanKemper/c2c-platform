<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

$reporter_id = (int) $_SESSION['user_id']; // never trust POST for this
$target_id   = (int) ($_POST['target_id']   ?? 0);
$report_type = $_POST['report_type']        ?? '';
$reason      = trim($_POST['reason']        ?? '');

if (!in_array($report_type, ['listing', 'user'], true)) {
    header('Location: ../index.php');
    exit;
}

if (!$target_id || $reason === '') {
    header('Location: ../index.php');
    exit;
}

// Verify the target actually exists
if ($report_type === 'listing') {
    $stmt = $pdo->prepare('SELECT listing_id FROM listings WHERE listing_id = ? LIMIT 1');
    $stmt->execute([$target_id]);
} else {
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE user_id = ? LIMIT 1');
    $stmt->execute([$target_id]);
}

if (!$stmt->fetch()) {
    header('Location: ../index.php');
    exit;
}

// Can't report yourself
if ($report_type === 'user' && $target_id === $reporter_id) {
    header('Location: ../index.php');
    exit;
}

// Prevent duplicate reports of the same target by the same user
$dup_stmt = $pdo->prepare('
    SELECT report_id FROM reports
    WHERE reporter_id  = ?
      AND report_type  = ?
      AND target_id     = ?
    LIMIT 1
');
$dup_stmt->execute([$reporter_id, $report_type, $target_id]);

if ($dup_stmt->fetch()) {
    header('Location: ../index.php?error=already_reported');
    exit;
}

$stmt = $pdo->prepare('
    INSERT INTO reports (reporter_id, report_type, target_id, reason, status, created_at)
    VALUES (?, ?, ?, ?, "open", NOW())
');
$stmt->execute([$reporter_id, $report_type, $target_id, $reason]);

header('Location: ../index.php?success=report_submitted');
exit;
