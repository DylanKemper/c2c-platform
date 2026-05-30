<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Validate listing ID
$listing_id = (int) ($_POST['listing_id'] ?? 0);
if (!$listing_id) {
    header('Location: index.php');
    exit;
}

// Fetch listing + seller info
$stmt = $pdo->prepare('
    SELECT l.*, u.username, u.user_id AS seller_user_id,
           ROUND(AVG(r.rating), 1) AS seller_rating,
           COUNT(r.review_id) AS review_count
    FROM listings l
    JOIN users u ON u.user_id = l.seller_id
    LEFT JOIN reviews r ON r.reviewee_id = l.seller_id AND r.role = "seller"
    WHERE l.listing_id = ? AND l.status = "active"
    GROUP BY l.listing_id
');
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

if (!$listing) {
    header('Location: index.php');
    exit;
}

// Block seller from buying their own listing
if ($listing['seller_user_id'] === $_SESSION['user_id']) {
    header('Location: index.php');
    exit;
}

header('Location: ../purchase-confirm.php');
exit();