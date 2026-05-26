<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}

function currentUserId(): ?int
{
    return $_SESSION['user_id'] ?? null;
}

function currentUsername(): ?string
{
    return $_SESSION['username'] ?? null;
}

function loginUser(array $user): void
{
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
}

function logoutUser(): void
{
    session_unset();
    session_destroy();
}