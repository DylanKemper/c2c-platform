<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$title           = trim($_POST['title']);
$description     = trim($_POST['description']);
$price           = (float) $_POST['price'];
$category_id     = (int) $_POST['category_id'];
$condition       = $_POST['condition'];
$location        = trim($_POST['location']);
$delivery_method = $_POST['delivery_method'];
$seller_id       = $_SESSION['user_id'];

// If any required field is missing or invalid, stop processing
if ($title === '' || $description === '' || $price <= 0 || $category_id <= 0 || $condition === '' || $location === '' || $delivery_method === '') {
    die('Missing fields');
}

// Validate and handle image upload
$filename = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    $mime    = mime_content_type($_FILES['image']['tmp_name']);

    if (!in_array($mime, $allowed)) die('Invalid file type.');
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) die('File too large.');

    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
    };

    $filename = uniqid('img_', true) . '.' . $ext;
}

// Insert listing into database
$sql = '
INSERT INTO listings (
    title,
    description,
    price,
    category_id,
    `condition`,
    location,
    delivery_method,
    seller_id,
    created_at
)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
';

// Prepared statement to prevent SQL injection
$stmt = $pdo->prepare($sql);
$stmt->execute([
    $title,
    $description,
    $price,
    $category_id,
    $condition,
    $location,
    $delivery_method,
    $seller_id
]);

// Get the ID of the newly created listing
$listing_id = $pdo->lastInsertId();

// Now move file and insert into listing_images
if ($filename) {
    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../uploads/listings/' . $filename);
    $stmt = $pdo->prepare('INSERT INTO listing_images (listing_id, filename, is_primary) VALUES (?, ?, 1)');
    $stmt->execute([$listing_id, $filename]);
}

// Redirect to the listing page after successful insertion
header('Location: ../listing.php?id=' . $listing_id);
exit;
