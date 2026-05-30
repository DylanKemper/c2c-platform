<?php
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/session.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$transaction_id = (int) ($_GET['id'] ?? 0);
if ($transaction_id <= 0) {
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare('
    SELECT t.amount, t.fee
    FROM transactions t
    WHERE t.transaction_id = ? AND t.buyer_id = ?
');
$stmt->execute([$transaction_id, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header('Location: index.php');
    exit;
}

$total = round((float)$transaction['amount'] + (float)$transaction['fee'], 2);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmed — Lootly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'partials/navbar.php'; ?>
    <div class="confirm-page" style="min-height: calc(100vh - var(--navbar-height)); display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
        <div class="panel" style="max-width: 400px; width: 100%; text-align: center;">

            <div class="panel__header">
                <p class="panel__title">Payment confirmed</p>
            </div>

            <div class="panel__body" style="display: flex; flex-direction: column; align-items: center; gap: 24px; padding-top: 32px; padding-bottom: 32px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background-color: #e8f7f5; display: flex; align-items: center; justify-content: center;">
                    <svg viewBox="0 0 24 24" width="38" height="38" fill="none" stroke="var(--accent)" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <p>
                    <span class="product-card-price">R <?= number_format($total, 2) ?></span>
                </p>
            </div>

            <div class="panel__footer">
                <a href="index.php" class="btn-platform btn-primary-solid">Back to listings</a>
                <p class="panel__subtitle" style="text-align: center; margin-top: 10px; font-size: 0.75rem;">
                    Redirecting in <span id="countdown">5</span>s
                </p>
            </div>

        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        let s = 10;
        const el = document.getElementById('countdown');
        const timer = setInterval(() => {
            s--;
            el.textContent = s;
            if (s <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php';
            }
        }, 1000);
        document.getElementById('home-btn').addEventListener('click', () => clearInterval(timer));
    </script>
</body>

</html>