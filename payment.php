<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/db.php';

if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    die('Invalid listing ID.');
}

$listingId = (int) $_POST['id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'partials/navbar.php'; ?>

    <main class="flex-grow-1">
        <div class="container py-4">
            <form action="auth/purchase.php" method="POST">
                <!-- Form fields for payment information -->
                <input type="hidden" name="listing_id" value="<?php echo $listingId; ?>">
                <div class="listing-form-layout">
                    <!-- =========================================
                     LEFT COLUMN — PAYMENT FORM
                ========================================== -->
                    <div class="listing-form-col">
                        <!-- Payment Method -->
                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">
                                    <i class="bi bi-credit-card"></i>
                                    Payment Details
                                </span>
                            </div>
                            <div class="panel__body d-flex flex-column gap-4">

                                <!-- Payment Methods -->
                                <div>
                                    <label class="form-label">
                                        Payment method
                                    </label>
                                    <div class="payment-method-grid">
                                        <!-- Card -->
                                        <label class="payment-method-card">
                                            <input
                                                type="radio"
                                                name="payment_method"
                                                checked>
                                            <div class="payment-method-content">
                                                <i class="bi bi-credit-card-2-front payment-method-icon"></i>
                                                <div>
                                                    <div class="payment-method-title">
                                                        Credit / Debit Card
                                                    </div>
                                                    <div class="payment-method-desc">
                                                        Visa, Mastercard
                                                    </div>
                                                </div>
                                            </div>
                                        </label>

                                        <!-- EFT -->
                                        <label class="payment-method-card">
                                            <input
                                                type="radio"
                                                name="payment_method">
                                            <div class="payment-method-content">
                                                <i class="bi bi-bank payment-method-icon"></i>
                                                <div>
                                                    <div class="payment-method-title">
                                                        EFT Transfer
                                                    </div>
                                                    <div class="payment-method-desc">
                                                        Instant bank payment
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Card Fields -->
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label">
                                            Cardholder name
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="John Doe">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Card number
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="1234 5678 9012 3456">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">
                                            Expiry
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="MM / YY">
                                    </div>

                                    <div class="col-6">
                                        <label class="form-label">
                                            CVV
                                        </label>
                                        <input
                                            type="password"
                                            class="form-control custom-input"
                                            placeholder="123">
                                    </div>
                                </div>

                                <!-- Billing -->
                                <div>
                                    <label class="form-label">
                                        Billing address
                                    </label>
                                    <textarea
                                        class="form-control custom-input"
                                        rows="3"
                                        placeholder="Street address"></textarea>
                                </div>

                            </div>
                        </div>

                        <!-- Shipping -->
                        <div class="panel">
                            <div class="panel__header">
                                <span class="panel__title">
                                    <i class="bi bi-truck"></i>
                                    Delivery Information
                                </span>
                            </div>

                            <div class="panel__body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            First name
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="John">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Last name
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="Doe">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Phone number
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="+27 71 234 5678">
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">
                                            Delivery address
                                        </label>
                                        <textarea
                                            class="form-control custom-input"
                                            rows="3"
                                            placeholder="Street address"></textarea>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            City
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="Cape Town">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">
                                            Postal code
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control custom-input"
                                            placeholder="8001">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =========================================
                     RIGHT COLUMN — ORDER SUMMARY
                ========================================== -->
                    <div class="listing-preview-col">
                        <div class="listing-preview-sticky">
                            <!-- Order Summary -->
                            <div class="panel">
                                <div class="panel__header">
                                    <span class="panel__title">
                                        <i class="bi bi-receipt"></i>
                                        Order Summary
                                    </span>
                                </div>

                                <div class="panel__body d-flex flex-column gap-3">
                                    <p class="section-card-desc mb-0">
                                        Review your purchase before completing payment.
                                    </p>

                                    <!-- Product Preview -->
                                    <div class="preview-card">
                                        <div class="preview-card-img-wrapper">
                                            <img
                                                class="preview-card-img"
                                                src="uploads/example.jpg"
                                                alt="Product">
                                        </div>

                                        <div class="preview-card-body">
                                            <span class="preview-card-category">
                                                Sneakers
                                            </span>
                                            <h3 class="preview-card-title">
                                                Nike Dunk Low Panda
                                            </h3>
                                            <p class="preview-card-desc">
                                                Excellent condition. Includes original box and extra laces.
                                            </p>
                                            <div class="preview-card-footer">
                                                <span class="preview-card-price">
                                                    R 1,200.00
                                                </span>
                                                <span class="preview-card-condition">
                                                    Excellent
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seller -->
                                    <div class="seller-card mb-0">
                                        <div class="seller-avatar">
                                            CT
                                        </div>

                                        <div class="seller-info">
                                            <p class="seller-name mb-0">
                                                @ct_kicks
                                            </p>

                                            <p class="seller-meta mb-0">
                                                Verified seller · 24 completed sales
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Costs -->
                                    <div class="d-flex flex-column gap-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="seller-meta">
                                                Item subtotal
                                            </span>
                                            <span class="seller-name">
                                                R 1,200.00
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span class="seller-meta">
                                                Shipping
                                            </span>
                                            <span class="seller-name">
                                                R 80.00
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span class="seller-meta">
                                                Buyer protection
                                            </span>
                                            <span class="seller-name">
                                                R 20.00
                                            </span>
                                        </div>

                                        <hr class="m-0">

                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="seller-name">
                                                Total
                                            </span>

                                            <span class="payment-total">
                                                R 1,300.00
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Actions -->
                            <div class="panel mt-3">
                                <div class="panel__body">
                                    <div class="listing-submit-section">
                                        <p class="section-card-desc mb-0">
                                            <i class="bi bi-shield-check" style="color: var(--accent);"></i>
                                            Payments are securely processed and protected by Lootly Escrow.
                                        </p>

                                        <button
                                            type="submit"
                                            class="btn-platform btn-primary-solid btn-block">
                                            <i class="bi bi-lock-fill"></i>
                                            Complete Payment
                                        </button>

                                        <a
                                            href="index.php"
                                            class="btn-platform btn-outline btn-block">
                                            Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>