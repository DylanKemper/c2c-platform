<nav class="navbar custom-navbar">
    <div class="navbar-inner">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">Lootly</a>
        <!-- Search -->
        <form class="search-wrapper" action="search.php" method="GET">
            <div class="search-group">
                <i class="bi bi-search search-icon"></i>
                <input
                    class="form-control search-form"
                    type="search"
                    name="q"
                    placeholder="Search for anything..."
                    aria-label="Search"
                >
                <!-- Mobile -->
                <button class="btn search-btn-mobile d-lg-none" type="submit" aria-label="Search">
                    <i class="bi bi-arrow-right-short"></i>
                </button>
            </div>
            <!-- Desktop -->
            <button class="btn btn-search d-none d-lg-flex" type="submit">
                Search
            </button>
        </form>

        <div class="nav-actions">
            <!-- Categories -->
            <a class="nav-action-btn" href="categories.php" aria-label="Categories">
                <i class="bi bi-grid-3x3-gap"></i>
                <span class="nav-action-label d-none d-lg-inline">Categories</span>
            </a>
            <!-- Sign In -->
            <a class="nav-action-btn" href="login.php" aria-label="Sign in">
                <i class="bi bi-person"></i>
                <span class="nav-action-label d-none d-lg-inline">Sign in</span>
            </a>
            <!-- Cart -->
            <a class="nav-action-btn cart-btn" href="cart.php" aria-label="Cart">
                <i class="bi bi-bag"></i>
                <span class="cart-badge">3</span>
            </a>
        </div>
    </div>
</nav>