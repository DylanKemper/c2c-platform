<?php
require_once __DIR__ . '/../config/db.php';
$dispute_id = (int) ($_POST['dispute_id'] ?? 0);
$transaction_id = (int) ($_POST['transaction_id']    ?? 0);
$resolution     = $_POST['resolution']      ?? '';
$note           = trim($_POST['resolution_note'] ?? '');

if (!in_array($resolution, ['refunded', 'released'], true) || !$note || !$dispute_id || !$transaction_id) {
    header('Location: ../admin/disputes.php');
    exit;
}

// Set dispute status
$pdo->prepare(
    'UPDATE disputes
    SET status = "resolved",
        resolution = ?,
        resolution_note = ?,
        resolved_at = NOW()
    WHERE dispute_id = ?'
)->execute([$resolution, $note, $dispute_id]);

// Update the transaction status
$txn_status = $resolution === 'released' ? 'completed' : 'refunded';
$pdo->prepare(
    'UPDATE transactions SET status = ? WHERE transaction_id = ?
'
)->execute([$txn_status, $transaction_id]);

// If the transaction was refunded, relist the product
if ($resolution === 'refunded') {
    $pdo->prepare(
        'UPDATE listings SET status = "active" WHERE listing_id = (
        SELECT listing_id FROM transactions WHERE transaction_id = ?
    )
    '
    )->execute([$transaction_id]);
};

header('Location: ../admin/disputes.php?resolved=1');
exit;
