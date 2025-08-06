<nav class="appbar">
    <div class="appbar-left">
        <button id="sidebarToggle" class="appbar-menu-btn">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth" class="appbar-logo">
        <span class="appbar-title">Waste2Worth</span>
    </div>
    <div class="appbar-right">
        <span class="appbar-username">
            {{ Auth::user() ? Auth::user()->username : 'Guest' }}
        </span>
    </div>
</nav>

<aside class="sidebar" id="sidebarMenu">
    <nav class="sidebar-nav">
        <div class="nav-section">
            <h3>Account</h3>
            <ul>
                <li><a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                <li><a href="{{ url('volunteer') }}"><i class="fas fa-heart"></i> Volunteer</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <h3>Main</h3>
            <ul>
                <li><a href="{{ url('/event') }}"><i class="fas fa-calendar-alt"></i> Event</a></li>
                <li><a href="{{ url('/community') }}"><i class="fas fa-users"></i> Community</a></li>
                <li><a href="{{ url('/reportWaste') }}"><i class="fas fa-dumpster"></i> Waste Report</a></li>
                <li><a href="{{ url('/reward') }}"><i class="fas fa-gift"></i> Reward</a></li>
                <li>
                    <a href="{{ url('/leaderboard') }}">
                        <i class="fas fa-trophy"></i> Leaderboard
                    </a>
                </li>
            </ul>
        </div>
        <div class="nav-section">
            <h3>Support</h3>
            <ul>
                <li><a href="#"><i class="fas fa-flag"></i> Report</a></li>
                <li><a href="{{ url('/help') }}"><i class="fas fa-circle-question"></i> Help</a></li>
                <li><a href="{{ url('/contact') }}"><i class="fa-solid fa-phone"></i> Contact US</a></li>
            </ul>
        </div>
        <div class="nav-section">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logoutbtn">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>
    </nav>
</aside>