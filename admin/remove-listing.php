<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: listings.php');
    exit;
}

$listing_id = (int) ($_POST['listing_id'] ?? 0);

if (!$listing_id) {
    header('Location: listings.php');
    exit;
}

$stmt = $pdo->prepare('UPDATE listings SET status = "removed" WHERE listing_id = ?');
$stmt->execute([$listing_id]);

header('Location: listing-detail.php?id=' . $listing_id);
exit;
