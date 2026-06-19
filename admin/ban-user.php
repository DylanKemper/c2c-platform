<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

// if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
//     header('Location: ../index.php');
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: users.php');
    exit;
}

$user_id = (int) ($_POST['user_id'] ?? 0);

if (!$user_id) {
    header('Location: users.php');
    exit;
}

// Can't ban yourself
if ($user_id === (int) $_SESSION['user_id']) {
    header('Location: user-detail.php?id=' . $user_id . '&error=cannot_ban_self');
    exit;
}

$pdo->prepare('UPDATE users SET is_banned = 1 WHERE user_id = ?')->execute([$user_id]);

// Pull all their listings off the public site too
$pdo->prepare('UPDATE listings SET status = "removed" WHERE seller_id = ?')->execute([$user_id]);

header('Location: user-detail.php?id=' . $user_id);
exit;