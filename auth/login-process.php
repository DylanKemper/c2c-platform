<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

$sql = '
SELECT user_id, username, password_hash, role
FROM users
WHERE email = ?';

$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password_hash'])) {
    die('Invalid credentials');
}   

session_regenerate_id(true);

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['username'] = $user['username'];
$_SESSION['role'] = $user['role'];
$_SESSION['logged_in'] = true;

header('Location: ../index.php');
exit();