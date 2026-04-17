<?php
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'includes/navbar.php'; ?>
    <main class="flex-grow-1">
        <div class="card-grid">
            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/keyboard.jpg"
                    alt="Mechanical Keyboard">
                <div class="product-card-body">
                    <span class="product-card-category">Electronics</span>
                    <h3 class="product-card-title">Mechanical Keyboard</h3>
                    <p class="product-card-desc">Compact TKL layout with tactile brown switches. Backlit with per-key RGB and a USB-C detachable cable.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£74.99</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(1)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=1" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/headphones.jpg"
                    alt="Studio Headphones">
                <div class="product-card-body">
                    <span class="product-card-category">Audio</span>
                    <h3 class="product-card-title">Studio Headphones</h3>
                    <p class="product-card-desc">Over-ear closed-back design with 40mm drivers. Foldable frame, padded headband, and a 3.5mm jack.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£49.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="rating-count">(88)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=2" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/camera.jpg"
                    alt="Mirrorless Camera">
                <div class="product-card-body">
                    <span class="product-card-category">Photography</span>
                    <h3 class="product-card-title">Mirrorless Camera</h3>
                    <p class="product-card-desc">24MP APS-C sensor, 4K video, and in-body stabilisation. Ships with 18-55mm kit lens and carry case.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£389.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(17)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=3" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/keyboard.jpg"
                    alt="Mechanical Keyboard">
                <div class="product-card-body">
                    <span class="product-card-category">Electronics</span>
                    <h3 class="product-card-title">Mechanical Keyboard</h3>
                    <p class="product-card-desc">Compact TKL layout with tactile brown switches. Backlit with per-key RGB and a USB-C detachable cable.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£74.99</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(42)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=1" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/headphones.jpg"
                    alt="Studio Headphones">
                <div class="product-card-body">
                    <span class="product-card-category">Audio</span>
                    <h3 class="product-card-title">Studio Headphones</h3>
                    <p class="product-card-desc">Over-ear closed-back design with 40mm drivers. Foldable frame, padded headband, and a 3.5mm jack.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£49.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="rating-count">(88)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=2" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/camera.jpg"
                    alt="Mirrorless Camera">
                <div class="product-card-body">
                    <span class="product-card-category">Photography</span>
                    <h3 class="product-card-title">Mirrorless Camera</h3>
                    <p class="product-card-desc">24MP APS-C sensor, 4K video, and in-body stabilisation. Ships with 18-55mm kit lens and carry case.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£389.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(17)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=3" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/camera.jpg"
                    alt="Mirrorless Camera">
                <div class="product-card-body">
                    <span class="product-card-category">Photography</span>
                    <h3 class="product-card-title">Mirrorless Camera</h3>
                    <p class="product-card-desc">24MP APS-C sensor, 4K video, and in-body stabilisation. Ships with 18-55mm kit lens and carry case.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£389.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(17)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=3" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/keyboard.jpg"
                    alt="Mechanical Keyboard">
                <div class="product-card-body">
                    <span class="product-card-category">Electronics</span>
                    <h3 class="product-card-title">Mechanical Keyboard</h3>
                    <p class="product-card-desc">Compact TKL layout with tactile brown switches. Backlit with per-key RGB and a USB-C detachable cable.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£74.99</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(42)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=1" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/headphones.jpg"
                    alt="Studio Headphones">
                <div class="product-card-body">
                    <span class="product-card-category">Audio</span>
                    <h3 class="product-card-title">Studio Headphones</h3>
                    <p class="product-card-desc">Over-ear closed-back design with 40mm drivers. Foldable frame, padded headband, and a 3.5mm jack.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£49.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="rating-count">(88)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=2" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

            <div class="product-card">
                <img class="product-card-img"
                    src="assets/img/camera.jpg"
                    alt="Mirrorless Camera">
                <div class="product-card-body">
                    <span class="product-card-category">Photography</span>
                    <h3 class="product-card-title">Mirrorless Camera</h3>
                    <p class="product-card-desc">24MP APS-C sensor, 4K video, and in-body stabilisation. Ships with 18-55mm kit lens and carry case.</p>
                    <div class="product-card-footer">
                        <span class="product-card-price">£389.00</span>
                        <div class="product-card-stars">
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star">&#9733;</span>
                            <span class="star star-empty">&#9733;</span>
                            <span class="rating-count">(17)</span>
                        </div>
                    </div>
                    <a href="listing.php?id=3" class="btn btn-add-cart">Add to cart</a>
                </div>
            </div>

        </div>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>

</html>