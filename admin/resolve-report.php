<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: reports.php');
    exit;
}

$report_id = (int) ($_POST['report_id'] ?? 0);
$action    = $_POST['action'] ?? '';
$note      = trim($_POST['resolution_note'] ?? '');

if (!in_array($action, ['resolved', 'dismissed'], true) || !$report_id) {
    header('Location: reports.php');
    exit;
}

$stmt = $pdo->prepare('
    UPDATE reports
    SET status = ?, resolved_at = NOW(), resolution_note = ?
    WHERE report_id = ?
');
$stmt->execute([$action, $note, $report_id]);

header('Location: report-detail.php?id=' . $report_id);
exit;
