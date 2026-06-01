<?php
require_once __DIR__ . '/../config/db.php';

// Get transaction details
$sql = '
    SELECT
        t.transaction_id,
        t.listing_id,
        t.buyer_id,
        t.seller_id,
        l.title AS listing_title,
        t.amount,
        t.created_at,
        t.status,

        u.created_at AS buyer_joined_at,
        u.created_at AS seller_joined_at,

        buyer.username AS buyer_username,
        seller.username AS seller_username

    FROM transactions t

    JOIN users buyer
        ON buyer.user_id = t.buyer_id

    JOIN users seller
        ON seller.user_id = t.seller_id

    JOIN listings l
        ON l.listing_id = t.listing_id

    JOIN users u
        ON u.user_id = t.buyer_id OR u.user_id = t.seller_id

    WHERE t.transaction_id = ?
';
$stmt = $pdo->prepare($sql);
$stmt->execute([$_GET['id']]);
$transaction = $stmt->fetch();



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction <?php echo htmlspecialchars($transaction['transaction_id']); ?> — Lootly Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <!-- Mobile warning (hidden on desktop) -->
    <div class="d-md-none alert alert-warning text-center p-3 admin-mobile-warning" style="display:none!important">
        The admin panel is optimised for desktop. Some features may not display correctly on mobile.
    </div>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <?php
        $active_page = 'transactions';
        include 'partials/sidebar.php';
        ?>

        <!-- MAIN CONTENT -->
        <div class="main-content flex-grow-1">

            <a href="transactions.php" class="page-back">
                <i class="bi bi-arrow-left"></i> Back to transactions
            </a>
            <div class="page-heading-row">
                <h1 class="page-heading">Transaction #<?php echo htmlspecialchars($transaction['transaction_id']); ?></h1>
                <?php
                $statusClass = match ($transaction['status']) {
                    'held'        => 'badge--warning',
                    'dispatched'  => 'badge--info',
                    'completed'   => 'badge--success',
                    'disputed'    => 'badge--danger',
                    'cancelled'   => 'badge--info',
                    default       => 'badge--info'
                };
                ?>
                <span class="badge <?= $statusClass ?>">
                    <i class="bi bi-shield-exclamation" style="font-size:9px"></i> <?php echo htmlspecialchars($transaction['status']); ?>
                </span>
            </div>

            <div class="row g-3 align-items-start">

                <!-- LEFT COLUMN -->
                <div class="col-md-8 d-flex flex-column gap-3">

                    <!-- Transaction details panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Transaction details</span>
                            <span class="badge badge--info">
                                <i class="bi bi-arrow-left-right" style="font-size:9px"></i> Escrow
                            </span>
                        </div>
                        <div class="panel__body">
                            <div class="report-grid">
                                <div class="report-item">
                                    <label>Transaction ID</label>
                                    <span>#<?php echo htmlspecialchars($transaction['transaction_id']); ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Listing</label>
                                    <a href="listing-detail.php?id=<?php echo htmlspecialchars($transaction['listing_id']); ?>">
                                        <?php echo htmlspecialchars($transaction['listing_title']); ?>
                                    </a>
                                </div>
                                <div class="report-item">
                                    <label>Amount held</label>
                                    <span>R <?php echo number_format($transaction['amount'], 2); ?></span>
                                </div>
                                <div class="report-item">
                                    <label>Created</label>
                                    <span><?php echo date('d M Y', strtotime($transaction['created_at'])); ?></span>
                                </div>
                                <!-- <div class="report-item">
                                    <label>Disputed</label>
                                    <span><?php echo date('d M Y, H:i', strtotime($transaction['disputed_at'])); ?></span>
                                </div> -->
                            </div>
                        </div>
                    </div>

                    <!-- Parties panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Parties</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <!-- Buyer -->
                            <div class="user-row">
                                <div class="user-avatar"><?= strtoupper(substr($transaction['buyer_username'], 0, 2)) ?></div>
                                <div>
                                    <div class="user-name">
                                        @<?php echo htmlspecialchars($transaction['buyer_username']); ?>
                                        <span class="badge badge--info" style="font-size:10px; margin-left:6px">Buyer</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since <?= date('M Y', strtotime($transaction['buyer_joined_at'])); ?>
                                    </div>
                                </div>
                                <a href="user-detail.php?id=<?php echo htmlspecialchars($transaction['buyer_id']); ?>" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                            <hr class="panel-divider">

                            <!-- Seller -->
                            <div class="user-row">
                                <div class="user-avatar"><?= strtoupper(substr($transaction['seller_username'], 0, 2)) ?></div>
                                <div>
                                    <div class="user-name">
                                        @<?php echo htmlspecialchars($transaction['seller_username']); ?>
                                        <span class="badge badge--success" style="font-size:10px; margin-left:6px">Seller</span>
                                    </div>
                                    <div class="user-sub">
                                        Member since <?= date('M Y', strtotime($transaction['seller_joined_at'])); ?>
                                    </div>
                                </div>
                                <a href="user-detail.php?id=<?php echo htmlspecialchars($transaction['seller_id']); ?>" class="user-link">
                                    View profile <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- Dispute note panel -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Dispute reason</span>
                        </div>
                        <div class="panel__body">
                            <div class="report-reason-box">
                                Item received does not match the listing description. Seller claimed the shoes
                                were &ldquo;like new&rdquo; but they arrived with visible sole wear and scuffing
                                on the toe box. I have photos. Requesting a full refund.
                            </div>
                            <div class="report-item mt-2">
                                <label>Filed by</label>
                                <a href="user-detail.php?id=<?php echo htmlspecialchars($transaction['buyer_id']); ?>">
                                    @<?php echo htmlspecialchars($transaction['buyer_username']); ?> (buyer)
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN: action panel -->
                <div class="col-md-4">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Actions</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-2">

                            <div class="form-field">
                                <textarea
                                    id="resolution-note"
                                    name="resolution_note"
                                    class="form-textarea"
                                    rows="4"
                                    placeholder="Resolution note (required)&hellip;"></textarea>
                            </div>

                            <button class="btn-platform btn-primary-solid" id="btn-release" disabled style="opacity:0.45">
                                <i class="bi bi-check-circle"></i> Release funds to seller
                            </button>
                            <button class="btn-platform btn-danger-outline" id="btn-refund" disabled style="opacity:0.45">
                                <i class="bi bi-arrow-counterclockwise"></i> Refund buyer
                            </button>

                            <hr class="panel-divider">

                            <!-- No note required for these two -->
                            <button class="btn-platform btn-outline" id="btn-info">
                                <i class="bi bi-chat-left-text"></i> Request more information
                            </button>
                            <button class="btn-platform btn-outline" id="btn-escalate">
                                <i class="bi bi-arrow-up-circle"></i> Escalate
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>