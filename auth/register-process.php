<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

$username        = trim($_POST['username'] ?? '');
$email           = trim($_POST['email'] ?? '');
$password        = $_POST['password'] ?? '';

if ($username === '' || $email === '' || $password === '') {
    die('Missing fields');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$role = 'user';

$sql = '
INSERT INTO users (
    username,
    email,
    password_hash,
    role,
    created_at
)
VALUES (?, ?, ?, ?, NOW())
';

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $username,
    $email,
    $passwordHash,
    $role
]);

$userId = $pdo->lastInsertId();

session_regenerate_id(true);

$_SESSION['user_id'] = $userId;
$_SESSION['username'] = $username;
$_SESSION['role'] = $role;
$_SESSION['logged_in'] = true;

header('Location: ../index.php');
exit;