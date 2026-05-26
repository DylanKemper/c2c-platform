<?php
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/config/db.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — Lootly</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include 'partials/navbar.php'; ?>

    <div class="container py-4">
        <div class="row g-4 align-items-start">

            <!-- ══════════════════════════════════
                 LEFT COLUMN — identity + stats
            ══════════════════════════════════ -->
            <div class="col-md-4 col-lg-3 d-flex flex-column gap-3">

                <!-- Identity card — reuses .seller-card internals -->
                <div class="panel">
                    <div class="panel__body text-center d-flex flex-column align-items-center gap-2">
                        <div class="user-avatar">JS</div>
                        <div>
                            <div class="user-name">@jsmith92</div>
                            <div class="user-joined-date">Member since January 2024</div>
                        </div>
                        <a href="edit-profile.php" class="btn-platform btn-outline w-100 mt-1">
                            <i class="bi bi-pencil"></i> Edit profile
                        </a>
                    </div>
                </div>

                <!-- Stats card -->
                <div class="panel">
                    <div class="panel__header">
                        <span class="panel__title">Stats</span>
                    </div>
                    <div class="panel__body d-flex flex-column gap-3">

                        <!-- Seller rating -->
                        <div>
                            <div class="seller-meta mb-1">Seller rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="product-card-stars">
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-half star"></i>
                                </div>
                                <span class="seller-rating">4.5</span>
                                <span class="rating-count">(8)</span>
                            </div>
                        </div>

                        <!-- Buyer rating -->
                        <div>
                            <div class="seller-meta mb-1">Buyer rating</div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="product-card-stars">
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                    <i class="bi bi-star-fill star"></i>
                                </div>
                                <span class="seller-rating">5.0</span>
                                <span class="rating-count">(6)</span>
                            </div>
                        </div>

                        <hr class="m-0">

                        <!-- Counts — reuse seller-meta / seller-name pairing -->
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-arrow-left-right me-1"></i>Completed transactions</span>
                            <span class="seller-name">14</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-tag me-1"></i>Active listings</span>
                            <span class="seller-name">3</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-bag-check me-1"></i>Items sold</span>
                            <span class="seller-name">8</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-bag me-1"></i>Items bought</span>
                            <span class="seller-name">6</span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════
                 RIGHT COLUMN — tabbed content
            ══════════════════════════════════ -->
            <div class="col-md-8 col-lg-9 d-flex flex-column gap-3">

                <div class="profile-tabs">
                    <button class="profile-tab active" data-tab="selling">
                        <i class="bi bi-tag"></i> Selling
                        <span class="badge-status badge-neutral ms-1">3</span>
                    </button>
                    <button class="profile-tab" data-tab="sold">
                        <i class="bi bi-bag-check"></i> Sold
                        <span class="badge-status badge-neutral ms-1">8</span>
                    </button>
                    <button class="profile-tab" data-tab="bought">
                        <i class="bi bi-bag"></i> Bought
                        <span class="badge-status badge-neutral ms-1">6</span>
                    </button>
                    <button class="profile-tab" data-tab="reviews-received">
                        <i class="bi bi-star"></i> Reviews received
                        <span class="badge-status badge-neutral ms-1">14</span>
                    </button>
                    <button class="profile-tab" data-tab="reviews-left">
                        <i class="bi bi-star-half"></i> Reviews left
                        <span class="badge-status badge-neutral ms-1">6</span>
                    </button>
                </div>

                <!-- ── SELLING TAB ── -->
                <div class="tab-panel active" id="tab-selling">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Active listings</span>
                            <a href="listing-form.php" class="btn-platform btn-primary-solid btn-sm">
                                <i class="bi bi-plus"></i> New listing
                            </a>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Jordan 1 Retro High OG &mdash; Size 9</p>
                                    <p class="seller-meta mb-0">R 1 200 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Like new &nbsp;&middot;&nbsp; Posted 5 days ago</p>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=22" class="btn-platform btn-outline btn-sm">View</a>
                                    <a href="listing-form.php?edit=22" class="btn-platform btn-outline btn-sm">Edit</a>
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Adidas Ultraboost 22 &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 650 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Good &nbsp;&middot;&nbsp; Posted 12 days ago</p>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=19" class="btn-platform btn-outline btn-sm">View</a>
                                    <a href="listing-form.php?edit=19" class="btn-platform btn-outline btn-sm">Edit</a>
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">New Balance 550 &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 480 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Good &nbsp;&middot;&nbsp; Posted 20 days ago</p>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=14" class="btn-platform btn-outline btn-sm">View</a>
                                    <a href="listing-form.php?edit=14" class="btn-platform btn-outline btn-sm">Edit</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── SOLD TAB ── -->
                <div class="tab-panel" id="tab-sold">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Sold items</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Nike Dunk Low &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 900 &nbsp;&middot;&nbsp; Sold to <a href="#">@buyer_cape99</a> &nbsp;&middot;&nbsp; 2 weeks ago</p>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=11" class="btn-platform btn-outline btn-sm">View</a>
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Puma RS-X &mdash; Size 9</p>
                                    <p class="seller-meta mb-0">R 420 &nbsp;&middot;&nbsp; Sold to <a href="#">@runner_za</a> &nbsp;&middot;&nbsp; 1 month ago</p>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=8" class="btn-platform btn-outline btn-sm">View</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── BOUGHT TAB ── -->
                <div class="tab-panel" id="tab-bought">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Purchased items</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <!-- Reviewed -->
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Converse Chuck 70 &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 350 &nbsp;&middot;&nbsp; From <a href="#">@ct_kicks</a> &nbsp;&middot;&nbsp; 3 weeks ago</p>
                                    <span class="badge badge--md badge--success">
                                        <i class="bi bi-star-fill"></i>&nbsp; Reviewed
                                    </span>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=5" class="btn-platform btn-outline btn-sm">View</a>
                                </div>
                            </div>

                            <hr class="m-0">

                            <!-- Unreviewed -->
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Vans Old Skool &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 280 &nbsp;&middot;&nbsp; From <a href="#">@sneakerhead_ct</a> &nbsp;&middot;&nbsp; 1 week ago</p>
                                    <span class="badge badge--md badge--warning d-inline-flex">
                                        <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Review pending
                                    </span>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=3" class="btn-platform btn-outline btn-sm">View</a>
                                    <button
                                        class="btn-platform btn-primary-solid btn-sm"
                                        onclick="openReviewModal(3, 'Vans Old Skool', 'sneakerhead_ct')">
                                        <i class="bi bi-star"></i> Review
                                    </button>
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Reebok Classic Leather &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 310 &nbsp;&middot;&nbsp; From <a href="#">@runner_za</a> &nbsp;&middot;&nbsp; 2 days ago</p>
                                    <span class="badge badge--md badge--warning d-inline-flex">
                                        <i class="bi bi-clock" style="font-size:8px"></i>&nbsp; Review pending
                                    </span>
                                </div>
                                <div class="d-flex gap-2 flex-shrink-0">
                                    <a href="listing.php?id=6" class="btn-platform btn-outline btn-sm">View</a>
                                    <button
                                        class="btn-platform btn-primary-solid btn-sm"
                                        onclick="openReviewModal(6, 'Reebok Classic Leather', 'runner_za')">
                                        <i class="bi bi-star"></i> Review
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── REVIEWS RECEIVED TAB ── -->
                <div class="tab-panel" id="tab-reviews-received">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reviews received</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex flex-column gap-2">
                                <span class="listing-category-badge">Nike Dunk Low</span>
                                <div class="seller-card">
                                    <div class="seller-avatar">BC</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@buyer_cape99</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">2 weeks ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Great seller, item exactly as described. Fast communication and well packaged.
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex flex-column gap-2">
                                <span class="listing-category-badge">Puma RS-X</span>
                                <div class="seller-card">
                                    <div class="seller-avatar">RZ</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@runner_za</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-empty star-empty"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">1 month ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Shoes were as described but took a few extra days to dispatch. Would still buy again.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── REVIEWS LEFT TAB ── -->
                <div class="tab-panel" id="tab-reviews-left">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Reviews left</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex flex-column gap-2">
                                <span class="listing-category-badge">Converse Chuck 70</span>
                                <div class="seller-card">
                                    <div class="seller-avatar">CK</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@ct_kicks</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">3 weeks ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Perfect condition, exactly as listed. Seller was responsive and shipped quickly.
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.profile-tab').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.profile-tab').forEach(b => b.classList.remove('active'));
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('tab-' + btn.dataset.tab).classList.add('active');
            });
        });
    </script>

    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>