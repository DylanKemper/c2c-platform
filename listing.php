<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'partials/navbar.php'; ?>
    <nav class="custom-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="search.php?category=1">Electronics</a></li>
            <li class="breadcrumb-item active" aria-current="page">Wireless Headphones</li>
        </ol>
    </nav>

    <main class="flex-grow-1">
        <div class="listing-page">
            <div class="product-listing-layout">

                <div class="image-panel">
                    <img
                        src="Sample-Images/Sample-Image.jpg"
                        alt="Mechanical Keyboard"
                        class="listing-image-main">
                </div>

                <div class="listing-details-panel">
                    <span class="listing-category-badge">Electronics</span>
                    <h1 class="listing-title">Wireless Headphones</h1>
                    <div class="listing-price-row">
                        <span class="listing-price">£74.99</span>
                    </div>
                    <p class="listing-description-text">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Non, labore accusamus. Obcaecati quos necessitatibus odit repudiandae?
                        Impedit sed odit ipsum ratione culpa nisi mollitia, alias modi itaque iste aut dolores?
                    </p>

                    <a href="profile.php?id=1" class="seller-card">
                        <div class="seller-avatar">JD</div>
                        <div class="seller-info">
                            <p class="seller-name">john_doe</p>
                            <p class="seller-meta">Member since 2024 &nbsp;·&nbsp; 12 listings</p>
                        </div>
                        <span class="seller-rating">
                            <i class="bi bi-star-fill"></i> 4.8
                        </span>
                    </a>

                    <div class="listing-actions">
                        <button class="btn-platform btn-accent-solid btn-block">
                            <i class="bi bi-bag-check me-2"></i>Buy Now
                        </button>
                        <button class="btn-platform btn-outline btn-block">
                            <i class="bi bi-chat-dots me-2"></i>Contact Seller
                        </button>
                        <div class="escrow-notice">
                            <i class="bi bi-shield-check"></i>
                            <span>All transactions are escrow-protected. Payment is only
                                released to the seller once you confirm receipt.</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
    <?php include 'partials/footer.php'; ?>
</body>