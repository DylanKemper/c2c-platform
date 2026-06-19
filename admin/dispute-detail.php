<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/session.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

/* =========================================================
   FETCH DISPUTE
========================================================= */
$sql = '
    SELECT
        d.dispute_id,
        d.transaction_id,
        d.reason,
        d.status,
        d.created_at,
        d.resolution_note,
        d.resolved_at,

        t.listing_id,
        t.amount,
        t.status AS transaction_status,
        t.buyer_id,
        t.seller_id,

        u.created_at AS buyer_joined_at,
        u.created_at AS seller_joined_at,

        l.title AS listing_title,

        buyer.username AS buyer_username,
        seller.username AS seller_username

    FROM disputes d

    INNER JOIN transactions t
        ON t.transaction_id = d.transaction_id

    INNER JOIN listings l
        ON l.listing_id = t.listing_id

    INNER JOIN users buyer
        ON buyer.user_id = t.buyer_id

    INNER JOIN users seller
        ON seller.user_id = t.seller_id

    LefT JOIN users u
        ON u.user_id = t.buyer_id OR u.user_id = t.seller_id

    WHERE d.dispute_id = ?
    LIMIT 1
';

$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['dispute_id']]);
$dispute = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$dispute) {
    die('Dispute not found');
}

/* =========================================================
   STATUS STYLING
========================================================= */
$statusClass = match ($dispute['status']) {
    'open'         => 'badge--warning',
    'resolved'     => 'badge--success',
    default        => 'badge--info'
};

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dispute #<?= (int)$dispute['dispute_id'] ?> — Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="d-flex">

        <?php
        $active_page = 'disputes';
        include 'partials/sidebar.php';
        ?>

        <div class="main-content flex-grow-1">

            <a href="disputes.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to disputes
            </a>

            <div class="page-heading-row">
                <h1 class="page-heading">
                    Dispute #<?= (int)$dispute['dispute_id'] ?>
                </h1>

                <span class="badge <?= $statusClass ?>">
                    <?= htmlspecialchars(ucfirst($dispute['status'])) ?>
                </span>
            </div>

            <div class="row g-3 align-items-start">

                <!-- LEFT -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- DISPUTE DETAILS -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Dispute details</span>
                        </div>

                        <div class="panel__body">
                            <div class="report-grid">

                                <div class="report-item">
                                    <label>Dispute ID</label>
                                    <span>#<?= (int)$dispute['dispute_id'] ?></span>
                                </div>

                                <div class="report-item">
                                    <label>Transaction</label>
                                    <a href="transaction-detail.php?id=<?= (int)$dispute['transaction_id'] ?>">
                                        #<?= (int)$dispute['transaction_id'] ?>
                                    </a>
                                </div>

                                <div class="report-item">
                                    <label>Listing</label>
                                    <span><?= htmlspecialchars($dispute['listing_title']) ?></span>
                                </div>

                                <div class="report-item">
                                    <label>Amount</label>
                                    <span>R <?= number_format($dispute['amount'], 2) ?></span>
                                </div>

                                <div class="report-item">
                                    <label>Created</label>
                                    <span><?= date('d M Y', strtotime($dispute['created_at'])) ?></span>
                                </div>

                                <div class="report-item">
                                    <label>Transaction status</label>
                                    <span><?= htmlspecialchars(ucfirst($dispute['transaction_status'])) ?></span>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- PARTIES -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Parties</span>
                        </div>

                        <div class="panel__body d-flex flex-column gap-2">

                            <!-- Buyer -->
                            <div class="user-row">
                                <div class="user-avatar"><?= strtoupper(substr($dispute['buyer_username'], 0, 2)) ?></div>
                                <div>
                                    <div class="user-name">
                                        @<?= htmlspecialchars($dispute['buyer_username']) ?>
                                        <span class="badge badge--info" style="font-size:10px; margin-left:6px">Buyer</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since <?= date('M Y', strtotime($dispute['buyer_joined_at'])); ?>
                                    </div>
                                </div>
                                <a href="user-detail.php?id=<?php echo htmlspecialchars($dispute['buyer_id']); ?>" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <hr class="panel-divider">

                            <!-- Seller -->
                            <div class="user-row">
                                <div class="user-avatar"><?= strtoupper(substr($dispute['seller_username'], 0, 2)) ?></div>
                                <div>
                                    <div class="user-name">
                                        @<?= htmlspecialchars($dispute['seller_username']) ?>
                                        <span class="badge badge--success" style="font-size:10px; margin-left:6px">Seller</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since <?= date('M Y', strtotime($dispute['seller_joined_at'])); ?>
                                    </div>
                                </div>
                                <a href="user-detail.php?id=<?php echo htmlspecialchars($dispute['seller_id']); ?>" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- DISPUTE REASON -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Dispute reason</span>
                        </div>

                        <div class="panel__body">
                            <div class="report-reason-box">
                                <?= nl2br(htmlspecialchars($dispute['reason'])) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-md-4 d-flex flex-column gap-3">

                    <?php if ($dispute['status'] === 'resolved'): ?>

                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Resolution</span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-3">
                                <p class="seller-meta mb-0">
                                    <i class="bi bi-check-circle" style="color: var(--accent);"></i>
                                    This dispute has been resolved.
                                </p>

                                <?php if (!empty($dispute['resolution_note'])): ?>
                                    <div class="report-reason-box">
                                        <?= nl2br(htmlspecialchars($dispute['resolution_note'])) ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($dispute['resolved_at'])): ?>
                                    <div class="report-item">
                                        <label>Resolved</label>
                                        <span><?= date('d M Y', strtotime($dispute['resolved_at'])) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    <?php else: ?>
                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">Resolve dispute</span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-3">

                                <form method="POST" action="resolve-dispute.php">
                                    <input type="hidden" name="dispute_id" value="<?= (int) $dispute['dispute_id'] ?>">
                                    <input type="hidden" name="transaction_id" value="<?= (int) $dispute['transaction_id'] ?>">

                                    <div class="d-flex flex-column gap-3">
                                        <div>
                                            <label class="form-label fw-semibold">
                                                Resolution note <span class="text-danger">*</span>
                                            </label>
                                            <textarea
                                                id="resolution-note"
                                                name="resolution_note"
                                                class="form-control"
                                                rows="4"
                                                placeholder="Explain the resolution..."></textarea>
                                        </div>

                                        <button
                                            type="submit"
                                            name="resolution"
                                            value="released"
                                            id="btn-release"
                                            class="btn-platform btn-primary-solid"
                                            disabled>
                                            <i class="bi bi-check-circle"></i> Release funds to seller
                                        </button>

                                        <button
                                            type="submit"
                                            name="resolution"
                                            value="refunded"
                                            id="btn-refund"
                                            class="btn-platform btn-danger-outline"
                                            disabled>
                                            <i class="bi bi-arrow-counterclockwise"></i> Refund buyer
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>

                    <?php endif; ?>

                </div>
                <!-- END RIGHT -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/validate.js"></script>
    <script src="../js/dispute-detail.js"></script>
</body>

</html>