<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@sneakerhead_ct — Lootly</title>
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

                <!-- Identity card -->
                <div class="panel">
                    <div class="panel__body text-center d-flex flex-column align-items-center gap-2">
                        <div class="user-avatar">SC</div>
                        <div>
                            <div class="user-name">@sneakerhead_ct</div>
                            <div class="user-joined-date">Member since March 2023</div>
                        </div>
                        <a href="report.php?type=user&id=88" class="btn-platform btn-outline w-100 mt-1" style="color:var(--danger,#ef4444); border-color:var(--danger,#ef4444)">
                            <i class="bi bi-flag"></i> Report user
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
                                <span class="seller-rating">4.6</span>
                                <span class="rating-count">(23)</span>
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
                                <span class="rating-count">(8)</span>
                            </div>
                        </div>

                        <hr class="m-0">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-arrow-left-right me-1"></i>Completed transactions</span>
                            <span class="seller-name">31</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-tag me-1"></i>Active listings</span>
                            <span class="seller-name">4</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="seller-meta"><i class="bi bi-bag-check me-1"></i>Items sold</span>
                            <span class="seller-name">23</span>
                        </div>

                    </div>
                </div>

            </div>

            <!-- ══════════════════════════════════
                 RIGHT COLUMN — listings + reviews
            ══════════════════════════════════ -->
            <div class="col-md-8 col-lg-9 d-flex flex-column gap-3">

                <div class="profile-tabs">
                    <button class="profile-tab active" data-tab="listings">
                        <i class="bi bi-tag"></i> Listings
                        <span class="badge-status badge-neutral ms-1">4</span>
                    </button>
                    <button class="profile-tab" data-tab="reviews">
                        <i class="bi bi-star"></i> Reviews
                        <span class="badge-status badge-neutral ms-1">23</span>
                    </button>
                </div>

                <!-- ── LISTINGS TAB ── -->
                <div class="tab-panel active" id="tab-listings">
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">Active listings</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Nike Air Max 90 &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 850 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Like new &nbsp;&middot;&nbsp; Posted 3 days ago</p>
                                </div>
                                <a href="listing.php?id=17" class="btn-platform btn-outline btn-sm flex-shrink-0">View</a>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Air Jordan 4 Retro &mdash; Size 9</p>
                                    <p class="seller-meta mb-0">R 2 100 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Good &nbsp;&middot;&nbsp; Posted 1 week ago</p>
                                </div>
                                <a href="listing.php?id=21" class="btn-platform btn-outline btn-sm flex-shrink-0">View</a>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Asics Gel-Kayano 29 &mdash; Size 10</p>
                                    <p class="seller-meta mb-0">R 720 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Good &nbsp;&middot;&nbsp; Posted 2 weeks ago</p>
                                </div>
                                <a href="listing.php?id=16" class="btn-platform btn-outline btn-sm flex-shrink-0">View</a>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="seller-info">
                                    <p class="seller-name mb-0">Saucony Shadow 6000 &mdash; Size 9</p>
                                    <p class="seller-meta mb-0">R 540 &nbsp;&middot;&nbsp; Footwear &nbsp;&middot;&nbsp; Like new &nbsp;&middot;&nbsp; Posted 3 weeks ago</p>
                                </div>
                                <a href="listing.php?id=12" class="btn-platform btn-outline btn-sm flex-shrink-0">View</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ── REVIEWS TAB ── -->
                <div class="tab-panel" id="tab-reviews">

                    <!-- Rating summary -->
                    <div class="panel mb-3">
                        <div class="panel__header">
                            <span class="panel__title">Rating summary</span>
                        </div>
                        <div class="panel__body">
                            <div class="row g-3">

                                <div class="col-6">
                                    <div class="seller-meta mb-1">As a seller</div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-half star"></i>
                                        </div>
                                        <span class="seller-rating">4.6</span>
                                        <span class="rating-count">(23 reviews)</span>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="seller-meta mb-1">As a buyer</div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                        </div>
                                        <span class="seller-rating">5.0</span>
                                        <span class="rating-count">(8 reviews)</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Individual reviews -->
                    <div class="panel">
                        <div class="panel__header">
                            <span class="panel__title">All reviews</span>
                        </div>
                        <div class="panel__body d-flex flex-column gap-3">

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="listing-category-badge">Nike Dunk Low &mdash; Size 10</span>
                                    <span class="badge badge--sm badge--user" style="font-size:10px">Seller review</span>
                                </div>
                                <div class="seller-card">
                                    <div class="seller-avatar">JS</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@jsmith92</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">1 week ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Exactly as described, packaged with care. One of the smoothest transactions
                                    I&rsquo;ve had on here. Highly recommend.
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="listing-category-badge">Converse Chuck 70 &mdash; Size 9</span>
                                    <span class="badge badge--sm badge--user" style="font-size:10px">Seller review</span>
                                </div>
                                <div class="seller-card">
                                    <div class="seller-avatar">CK</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@ct_kicks</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-empty star-empty"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">3 weeks ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Item was good but condition was slightly worse than listed. Communication was
                                    fine, just be more accurate with descriptions.
                                </div>
                            </div>

                            <hr class="m-0">

                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex align-items-center gap-2">
                                    <span class="listing-category-badge">Puma RS-X &mdash; Size 10</span>
                                    <span class="badge badge--sm badge--transaction" style="font-size:10px">Buyer review</span>
                                </div>
                                <div class="seller-card">
                                    <div class="seller-avatar">RZ</div>
                                    <div class="seller-info">
                                        <p class="seller-name mb-0">@runner_za</p>
                                        <div class="product-card-stars">
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                            <i class="bi bi-star-fill star"></i>
                                        </div>
                                    </div>
                                    <span class="seller-meta ms-auto">1 month ago</span>
                                </div>
                                <div class="report-reason-box">
                                    Great buyer, paid quickly and was easy to deal with. Would sell to again.
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

</body>

</html>