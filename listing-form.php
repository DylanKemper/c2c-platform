<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Listing — Lootly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <?php include 'partials/navbar.php'; ?>
    <!-- Breadcrumb -->
    <nav class="custom-breadcrumb" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="search.php?category=1">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create Listing</li>
        </ol>
    </nav>

    <main class="listing-form-page flex-grow-1">
        <div class="listing-form-container">

            <div class="listing-form-header">
                <h1 class="listing-form-title">Create a Listing</h1>
                <p class="listing-form-subtitle">Fill in the details below to list your item for sale.</p>
            </div>

            <form action="listing-submit.php" method="POST" enctype="multipart/form-data">

                <div class="listing-form-layout">

                    <!-- ================================
                         LEFT COLUMN — Form Sections
                    ================================ -->
                    <div class="listing-form-col">

                        <!-- Basic Info -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-card-text"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Basic Information</h2>
                                    <p class="section-card-desc">Give your listing a clear, descriptive title.</p>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="form-label-upper" for="listing-title">
                                    Listing Title <span class="form-required">*</span>
                                </label>
                                <input
                                    class="form-input"
                                    type="text"
                                    id="listing-title"
                                    name="title"
                                    placeholder="e.g. Sony WH-1000XM5 Wireless Headphones"
                                    maxlength="100"
                                    required>
                            </div>

                            <div class="form-field-row">
                                <div class="form-field">
                                    <label class="form-label-upper" for="listing-category">
                                        Category <span class="form-required">*</span>
                                    </label>
                                    <div class="form-select-wrapper">
                                        <select class="form-select" id="listing-category" name="category_id" required>
                                            <option value="" disabled selected>Select a category</option>
                                            <?php
                                            /*
                                             * TODO: Replace this placeholder array with a real DB query, e.g.:
                                             * $stmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name");
                                             * $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                             */
                                            $categories = [
                                                ['id' => 1, 'name' => 'Electronics'],
                                                ['id' => 2, 'name' => 'Audio'],
                                                ['id' => 3, 'name' => 'Photography'],
                                                ['id' => 4, 'name' => 'Clothing'],
                                                ['id' => 5, 'name' => 'Sports'],
                                                ['id' => 6, 'name' => 'Home & Garden'],
                                                ['id' => 7, 'name' => 'Books'],
                                                ['id' => 8, 'name' => 'Toys'],
                                            ];
                                            foreach ($categories as $cat) {
                                                echo '<option value="' . htmlspecialchars($cat['id']) . '">'
                                                    . htmlspecialchars($cat['name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                        <i class="bi bi-chevron-down form-select-icon"></i>
                                    </div>
                                </div>

                                <div class="form-field">
                                    <label class="form-label-upper" for="listing-condition">
                                        Condition <span class="form-required">*</span>
                                    </label>
                                    <div class="form-select-wrapper">
                                        <select class="form-select" id="listing-condition" name="condition" required>
                                            <option value="" disabled selected>Select condition</option>
                                            <option value="new">New</option>
                                            <option value="like_new">Like New</option>
                                            <option value="good">Good</option>
                                            <option value="fair">Fair</option>
                                            <option value="poor">Poor</option>
                                        </select>
                                        <i class="bi bi-chevron-down form-select-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="form-label-upper" for="listing-description">
                                    Description <span class="form-required">*</span>
                                </label>
                                <textarea
                                    class="form-textarea"
                                    id="listing-description"
                                    name="description"
                                    rows="6"
                                    placeholder="Describe your item — include brand, model, any defects, what's included in the sale, etc."
                                    maxlength="2000"
                                    required></textarea>
                                <span class="form-hint">Max 2000 characters.</span>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-tag"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Pricing</h2>
                                    <p class="section-card-desc">Set a price for your item.</p>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="form-label-upper" for="listing-price">
                                    Price <span class="form-required">*</span>
                                </label>
                                <div class="form-price-wrapper">
                                    <span class="form-price-prefix">£</span>
                                    <input
                                        class="form-input form-input form-input-price"
                                        type="number"
                                        id="listing-price"
                                        name="price"
                                        placeholder="0.00"
                                        min="0.01"
                                        step="0.01"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-images"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Photos</h2>
                                    <p class="section-card-desc">Upload up to 5 photos. The first photo will be your cover image.</p>
                                </div>
                            </div>

                            <label class="listing-upload-zone" for="listing-images">
                                <i class="bi bi-cloud-arrow-up listing-upload-icon"></i>
                                <span class="listing-upload-primary">Click to upload photos</span>
                                <span class="listing-upload-secondary">PNG, JPG or WEBP &mdash; max 5 MB each</span>
                                <input
                                    class="listing-upload-input"
                                    type="file"
                                    id="listing-images"
                                    name="images[]"
                                    accept="image/png, image/jpeg, image/webp"
                                    multiple>
                            </label>

                            <!-- JS will populate this with thumbnails -->
                            <div class="listing-image-previews" id="image-previews"></div>
                        </div>

                        <!-- Location -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Location</h2>
                                    <p class="section-card-desc">Provide the location where the item is available for pickup or delivery.</p>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="form-label-upper" for="listing-location">
                                    Town / City <span class="form-required">*</span>
                                </label>
                                <div class="form-input-icon-wrapper">
                                    <i class="bi bi-geo-alt form-input-icon-left"></i>
                                    <input
                                        class="form-input form-input-has-icon"
                                        type="text"
                                        id="listing-location"
                                        name="location"
                                        placeholder="e.g. Cape Town"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Delivery Method -->
                        <div class="section-card">
                            <div class="section-card-header">
                                <div class="section-card-icon">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div>
                                    <h2 class="section-card-title">Delivery Method</h2>
                                    <p class="section-card-desc">How will the buyer receive the item?</p>
                                </div>
                            </div>

                            <div class="listing-delivery-options">

                                <label class="listing-delivery-option">
                                    <input type="radio" name="delivery_method" value="meetup" class="listing-radio-input" required>
                                    <span class="listing-delivery-card">
                                        <i class="bi bi-people listing-delivery-icon"></i>
                                        <span class="listing-delivery-title">Meet-up only</span>
                                        <span class="listing-delivery-desc">Buyer collects in person</span>
                                    </span>
                                </label>

                                <label class="listing-delivery-option">
                                    <input type="radio" name="delivery_method" value="post" class="listing-radio-input">
                                    <span class="listing-delivery-card">
                                        <i class="bi bi-envelope listing-delivery-icon"></i>
                                        <span class="listing-delivery-title">Post only</span>
                                        <span class="listing-delivery-desc">Shipped to buyer's address</span>
                                    </span>
                                </label>

                                <label class="listing-delivery-option">
                                    <input type="radio" name="delivery_method" value="both" class="listing-radio-input">
                                    <span class="listing-delivery-card">
                                        <i class="bi bi-arrow-left-right listing-delivery-icon"></i>
                                        <span class="listing-delivery-title">Both</span>
                                        <span class="listing-delivery-desc">Buyer's choice</span>
                                    </span>
                                </label>

                            </div>
                        </div>

                    </div>

                    <!-- ================================
                         RIGHT COLUMN — Preview + Submit
                    ================================ -->
                    <div class="listing-preview-col">
                        <div class="listing-preview-sticky">
                            <!-- Live Preview -->
                            <div class="section-card listing-preview-section">
                                <h2 class="section-card-title" style="margin-bottom: 4px;">Preview</h2>
                                <p class="section-card-desc" style="margin-bottom: 16px;">This is how your listing will look to buyers.</p>

                                <div class="preview-card">
                                    <div class="preview-card-img-wrapper">
                                        <div class="preview-card-no-img" id="preview-no-img">
                                            <i class="bi bi-image"></i>
                                            <span>No photo yet</span>
                                        </div>
                                        <img
                                            class="preview-card-img"
                                            id="preview-img"
                                            src=""
                                            alt="Preview"
                                            style="display: none;">
                                    </div>
                                    <div class="preview-card-body">
                                        <span class="preview-card-category" id="preview-category">Category</span>
                                        <h3 class="preview-card-title" id="preview-title">Your listing title</h3>
                                        <p class="preview-card-desc" id="preview-desc">Your description will appear here…</p>
                                        <div class="preview-card-footer">
                                            <span class="preview-card-price" id="preview-price">£0.00</span>
                                            <span class="preview-card-condition" id="preview-condition">—</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="section-card listing-submit-section">
                                <p class="section-card-desc" style="margin-bottom: 14px;">
                                    <i class="bi bi-shield-check" style="color: var(--accent);"></i>
                                    All transactions are protected by Lootly Escrow.
                                </p>
                                <button type="submit" name="action" value="publish" class="btn-platform btn-primary-solid btn-block">
                                    <i class="bi bi-check-circle"></i>
                                    Publish Listing
                                </button>
                                <a href="dashboard.php" class="btn-platform btn-outline btn-block">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </form>

        </div>
    </main>
    <?php include 'partials/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>