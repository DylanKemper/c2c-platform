<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// Basic input validation
if (empty($_POST['email']) || empty($_POST['password'])) {
    die('Email and password are required');
}
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// SQL Query to fetch user by email
$sql = '
SELECT user_id, username, password_hash, role
FROM users
WHERE email = ?';

$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify credentials
if (!$user || !password_verify($password, $user['password_hash'])) {
    die('Invalid credentials');
}

if ($user['is_banned']) {
    die('This account has been banned.');
}

// Regenerate session ID
session_regenerate_id(true);

// Set session variables
$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['logged_in'] = true;

// Update last active timestamp for the user
$updateStmt = $pdo->prepare('
    UPDATE users
    SET last_active = NOW()
    WHERE user_id = ?
');
$updateStmt->execute([$user['user_id']]);

if ($_SESSION['role'] === 'admin') {
    header('Location: ../admin/dashboard.php');
} else {
    header('Location: ../index.php');
}
exit;