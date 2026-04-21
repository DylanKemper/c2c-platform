<nav class="navbar custom-navbar">
    <div class="navbar-inner">
        <!-- Logo -->
        <a class="navbar-brand" href="index.php">
            <svg class="navbar-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M18,12c0,.552-.448,1-1,1s-1-.448-1-1,.448-1,1-1,1,.448,1,1ZM7,5c.552,0,1-.447,1-1,0-1.103,.897-2,2-2s2,.897,2,2c0,.553,.448,1,1,1s1-.447,1-1c0-2.206-1.794-4-4-4S6,1.794,6,4c0,.553,.448,1,1,1ZM24,13v2c0,1.106-.748,1.779-1.568,1.954-.661,1.654-1.872,3.051-3.432,3.954v.092c0,1.654-1.346,3-3,3-1.304,0-2.416-.836-2.829-2h-2.343c-.413,1.164-1.525,2-2.829,2-1.654,0-3-1.346-3-3v-.079c-2.635-1.519-4.182-4.377-3.983-7.451,.063-.978,.31-1.907,.705-2.756-1.017-.481-1.723-1.516-1.723-2.714,0-1.654,1.346-3,3-3,.552,0,1,.447,1,1s-.448,1-1,1-1,.448-1,1c0,.491,.356,.9,.823,.984,1.521-1.823,3.853-2.984,6.443-2.984h5.363c.733-1.478,2.102-2.512,3.927-2.946,.591-.139,1.203-.003,1.681,.374,.485,.384,.764,.958,.764,1.576v3.715c.608,.691,1.094,1.481,1.432,2.327,.821,.175,1.568,.848,1.568,1.954Zm-2,0h-.294c-.442,0-.832-.291-.958-.715-.274-.92-.78-1.776-1.463-2.477-.183-.187-.285-.438-.285-.698V5.004c-.975,.232-2.265,.83-2.764,2.335-.141,.423-.553,.714-.99,.684-.068-.003-5.98-.022-5.98-.022-3.303,0-6.05,2.459-6.253,5.599-.16,2.469,1.181,4.752,3.417,5.815,.349,.166,.57,.518,.57,.903v.683c0,.552,.449,1,1,1s1-.448,1-1,.448-1,1-1h4c.552,0,1,.447,1,1s.449,1,1,1,1-.448,1-1v-.694c0-.385,.221-.736,.569-.902,1.542-.736,2.7-2.081,3.179-3.688,.126-.424,.516-.715,.958-.715h.294v-2Z" />
            </svg>
            <span class="navbar-brand-name">Lootly</span>
        </a>

        <!-- Categories -->
        <div class="dropdown">
            <a class="nav-action-btn dropdown-toggle"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="m8,0h-3C2.243,0,0,2.243,0,5v3c0,1.103.897,2,2,2h6c1.103,0,2-.897,2-2V2c0-1.103-.897-2-2-2ZM2,8v-3c0-1.654,1.346-3,3-3h3v6s-6,0-6,0Zm12,2h6c1.103,0,2-.897,2-2v-3c0-2.757-2.243-5-5-5h-3c-1.103,0-2,.897-2,2v6c0,1.103.897,2,2,2Zm0-8h3c1.654,0,3,1.346,3,3v3s-6,0-6,0V2Zm-6,10H2c-1.103,0-2,.897-2,2v3c0,2.757,2.243,5,5,5h3c1.103,0,2-.897,2-2v-6c0-1.103-.897-2-2-2Zm-3,8c-1.654,0-3-1.346-3-3v-3h6v6s-3,0-3,0Zm18.707,2.293l-2.54-2.54c.524-.791.833-1.736.833-2.753,0-2.757-2.243-5-5-5s-5,2.243-5,5,2.243,5,5,5c1.017,0,1.962-.309,2.753-.833l2.54,2.54c.195.195.451.293.707.293s.512-.098.707-.293c.391-.391.391-1.023,0-1.414Zm-9.707-5.293c0-1.654,1.346-3,3-3s3,1.346,3,3-1.346,3-3,3-3-1.346-3-3Z" />
                </svg>
                <span class="nav-action-label d-none d-lg-inline">Categories</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="index.php?category=electronics">Electronics</a></li>
                <li><a class="dropdown-item" href="index.php?category=audio">Audio</a></li>
                <li><a class="dropdown-item" href="index.php?category=photography">Photography</a></li>
            </ul>
        </div>

        <!-- Search -->
        <form class="search-wrapper" action="search.php" method="GET">
            <div class="search-group">
                <i class="bi bi-search search-icon"></i>
                <input
                    class="form-control search-form"
                    type="search"
                    name="q"
                    placeholder="Search for anything..."
                    aria-label="Search">
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