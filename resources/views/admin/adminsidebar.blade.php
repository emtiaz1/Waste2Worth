<div class="sidebar bg-dark" style="min-height: 100vh; width: 250px; position: fixed; left: 0; top: 0;">
    <div class="d-flex flex-column p-3">
        <h3 class="text-white mb-4 text-center">Admin Panel</h3>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item mb-2">
                <a href="{{ route('admin.home') }}"
                    class="nav-link text-white {{ request()->routeIs('admin.home') ? 'active bg-secondary' : '' }}">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            <!-- User Management -->
            <li class="nav-item mb-2">
                <a href="{{ route('admin.user.details') }}"
                    class="nav-link text-white {{ request()->routeIs('admin.user.details') || request()->routeIs('admin.user.detail') ? 'active bg-secondary' : '' }}">
                    <i class="fas fa-users me-2"></i> User Details
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.volunteers') }}" class="nav-link text-white">
                    <i class="fas fa-hands-helping me-2"></i> Volunteer Details
                </a>
            </li>

            <!-- Content Management -->
            <li class="nav-item mb-2">
                <a href="{{ route('admin.events') }}" class="nav-link text-white">
                    <i class="fas fa-calendar me-2"></i> Events
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-recycle me-2"></i> Waste Reports
                </a>
            </li>

            
            <li class="nav-item mb-2">
                <a href="{{ route('admin.products') }}" class="nav-link text-white">
                    <i class="fas fa-box me-2"></i> Products
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.purchases') }}" class="nav-link text-white">
                    <i class="fas fa-shopping-cart me-2"></i> Purchases
                </a>
            </li>

            <!-- Engagement -->
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-trophy me-2"></i> Leaderboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="#" class="nav-link text-white">
                    <i class="fas fa-envelope me-2"></i> Contact Messages
                </a>
            </li>

            <li class="nav-item mt-4">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
    .sidebar {
        background-color: #1a1d20 !important;
    }

    .nav-link {
        color: #ffffff !important;
        transition: all 0.3s ease;
        padding: 0.5rem 1rem;
        border-radius: 5px;
        margin-bottom: 2px;
    }

    .nav-link:hover {
        background-color: #2c3338 !important;
        color: #ffffff !important;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: #3a4147 !important;
        border-left: 4px solid #ffffff;
    }

    .btn-outline-light:hover {
        background-color: #2c3338 !important;
    }

    .nav-item {
        position: relative;
    }

    .nav-item:not(:last-child)::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(255, 255, 255, 0.1);
    }

    /* Category spacing */
    .nav-item+.nav-item {
        margin-top: 2px;
    }

    /* Icon styling */
    .nav-link i {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }
</style>