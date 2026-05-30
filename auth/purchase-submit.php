<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Auth check and input validation
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Input validation
$listing_id = (int) ($_POST['listing_id'] ?? 0);
if ($listing_id <= 0) {
    header('Location: ../index.php');
    exit;
}
$buyer_id = $_SESSION['user_id'];

// Fetch listing details and seller info from DB
$stmt = $pdo->prepare('
    SELECT
        l.listing_id,
        l.title,
        l.price,
        l.delivery_method,
        l.location,
        l.condition,
        l.seller_id,
        u.username   AS seller_username,
        li.filename  AS image_filename
    FROM listings l
    JOIN  users u          ON u.user_id       = l.seller_id
    LEFT JOIN listing_images li ON li.listing_id = l.listing_id AND li.is_primary = 1
    WHERE l.listing_id = ?
    AND   l.status     = "active"
');
$stmt->execute([$listing_id]);
$listing = $stmt->fetch();

// Validate listing exists and buyer is not the seller
if (!$listing) {
    header('Location: ../index.php');
    exit;
}
if ((int)$listing['seller_id'] === $buyer_id) {
    header('Location: ../index.php');
    exit;
}

// Calculate fees and total
const SHIPPING_FEE        = 80.00;
const BUYER_PROTECTION_FEE = 20.00;
$price = (float) $listing['price'];
$fee   = SHIPPING_FEE + BUYER_PROTECTION_FEE;
$total = round($price + $fee, 2);

// Write transaction to DB and mark listing as sold
try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare('
        INSERT INTO transactions (listing_id, buyer_id, seller_id, amount, fee, status, created_at)
        VALUES (?, ?, ?, ?, ?, "held", NOW())
    ');
    $stmt->execute([
        $listing['listing_id'],
        $buyer_id,
        $listing['seller_id'],
        $price,
        $fee
    ]);

    $transaction_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare('UPDATE listings SET status = "sold" WHERE listing_id = ?');
    $stmt->execute([$listing['listing_id']]);

    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    header('Location: ../index.php');
    exit;
}

// Redirect to confirm page after successful transaction
header('Location: ../purchase-confirm.php?id=' . $transaction_id);
exit;