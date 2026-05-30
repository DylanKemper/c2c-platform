<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';
    
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid listing ID.');
}

$listingId = (int) $_GET['id'];

header('Location: ../index.php');
exit();