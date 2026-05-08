<?php
// Determine the current page for active state highlighting
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="admin-sidebar d-flex flex-column flex-shrink-0 p-3" style="width: 260px; min-height: 100vh;">

    <!-- Brand -->
    <a href="dashboard.php" class="sidebar-brand d-flex align-items-center mb-4 text-decoration-none">
        <svg class="navbar-logo me-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M18,12c0,.552-.448,1-1,1s-1-.448-1-1,.448-1,1-1,1,.448,1,1ZM7,5c.552,0,1-.447,1-1,0-1.103,.897-2,2-2s2,.897,2,2c0,.553,.448,1,1,1s1-.447,1-1c0-2.206-1.794-4-4-4S6,1.794,6,4c0,.553,.448,1,1,1ZM24,13v2c0,1.106-.748,1.779-1.568,1.954-.661,1.654-1.872,3.051-3.432,3.954v.092c0,1.654-1.346,3-3,3-1.304,0-2.416-.836-2.829-2h-2.343c-.413,1.164-1.525,2-2.829,2-1.654,0-3-1.346-3-3v-.079c-2.635-1.519-4.182-4.377-3.983-7.451,.063-.978,.31-1.907,.705-2.756-1.017-.481-1.723-1.516-1.723-2.714,0-1.654,1.346-3,3-3,.552,0,1,.447,1,1s-.448,1-1,1-1,.448-1,1c0,.491,.356,.9,.823,.984,1.521-1.823,3.853-2.984,6.443-2.984h5.363c.733-1.478,2.102-2.512,3.927-2.946,.591-.139,1.203-.003,1.681,.374,.485,.384,.764,.958,.764,1.576v3.715c.608,.691,1.094,1.481,1.432,2.327,.821,.175,1.568,.848,1.568,1.954Zm-2,0h-.294c-.442,0-.832-.291-.958-.715-.274-.92-.78-1.776-1.463-2.477-.183-.187-.285-.438-.285-.698V5.004c-.975,.232-2.265,.83-2.764,2.335-.141,.423-.553,.714-.99,.684-.068-.003-5.98-.022-5.98-.022-3.303,0-6.05,2.459-6.253,5.599-.16,2.469,1.181,4.752,3.417,5.815,.349,.166,.57,.518,.57,.903v.683c0,.552,.449,1,1,1s1-.448,1-1,.448-1,1-1h4c.552,0,1,.447,1,1s.449,1,1,1,1-.448,1-1v-.694c0-.385,.221-.736,.569-.902,1.542-.736,2.7-2.081,3.179-3.688,.126-.424,.516-.715,.958-.715h.294v-2Z" />
        </svg>
        <span class="sidebar-brand-text">Lootly <span class="sidebar-brand-sub">Admin</span></span>
    </a>

    <hr class="sidebar-divider">

    <!-- Nav label -->
    <p class="sidebar-nav-label">Management</p>

    <!-- Navigation -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">

        <li class="nav-item">
            <a href="dashboard.php" class="sidebar-link <?= $current_page === 'dashboard.php' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="users.php" class="sidebar-link <?= $current_page === 'users.php' ? 'active' : '' ?>">
                <i class="bi bi-people me-2"></i>
                Users
            </a>
        </li>

        <li class="nav-item">
            <a href="listings.php" class="sidebar-link <?= $current_page === 'listings.php' ? 'active' : '' ?>">
                <i class="bi bi-tag me-2"></i>
                Listings
            </a>
        </li>

        <li class="nav-item">
            <a href="reports.php" class="sidebar-link <?= $current_page === 'reports.php' || $current_page === 'report-detail.php' ? 'active' : '' ?>">
                <i class="bi bi-flag me-2"></i>
                Reports
                <!-- Badge — swap 0 for a real DB count later -->
                <?php $open_reports = 7; ?>
                <?php if ($open_reports > 0): ?>
                    <span class="sidebar-badge ms-auto"><?= $open_reports ?></span>
                <?php endif; ?>
            </a>
        </li>

        <li class="nav-item">
            <a href="transactions.php" class="sidebar-link <?= $current_page === 'transactions.php' ? 'active' : '' ?>">
                <i class="bi bi-cash-stack me-2"></i>
                Transactions
            </a>
        </li>

        <li class="nav-item">
            <a href="disputes.php" class="sidebar-link <?= $current_page === 'disputes.php' ? 'active' : '' ?>">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Escrow Disputes
            </a>
        </li>

    </ul>

    <div class="mt-auto">
        <hr class="sidebar-divider">
        <a href="../index.php" class="btn-platform btn-danger w-100">
            <i class="bi bi-box-arrow-left"></i> Logout
        </a>
    </div>
</div>