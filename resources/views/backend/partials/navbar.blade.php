<nav class="main-header navbar navbar-expand navbar-white">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="{{asset('backend')}}/AdminAssets/backend/dist/img/avatar.png" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item nav-profile ">
            <!-- Dropdown toggle -->
            <a class="nav-link  d-flex align-items-center" href="#" data-toggle="dropdown" id="profileDropdown"
                style="display:flex; align-items:center; gap:10px;">
                <!-- User avatar -->
                <div class="rounded-circle bg-primary text-white d-flex justify-content-center align-items-center"
                     style="width:35px; height:35px; font-weight:bold;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ms-4 text-dark"><span>{{ Auth::user()->name }}</span></div>
                <!-- Gap + User name -->

                <!-- Dropdown icon -->
                <i class="fa-solid fa-angle-down text-dark ms-2"></i>
            </a>

            <!-- Dropdown menu -->
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
                <div style="display:flex; align-items:center; padding:0.5rem 1rem; border-bottom:1px solid #dee2e6; gap:10px;">
                    <!-- Avatar -->
                    <div style="width:35px; height:35px; border-radius:50%;
                background-color:#0d6efd; color:white;
                display:flex; justify-content:center; align-items:center; font-weight:bold;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <!-- Name and Email -->
                    <div style="display:flex; flex-direction:column;">
                        <span style="font-weight:bold; color:black;">{{ Auth::user()->name }}</span>
                        <small style="color:gray;">{{ Auth::user()->email }}</small>
                    </div>
                </div>

                <!-- Menu links -->
                <a class="dropdown-item fw-bold text-dark" href="{{ route('dashboard') }}">
                    <i class="fa-solid fa-home me-2 text-dark"></i> Dashboard
                </a>
                <a class="dropdown-item fw-bold text-dark" href="{{ route('profile.update') }}">
                    <i class="fa-solid fa-user me-2 text-dark"></i> My Account
                </a>
                <a class="dropdown-item fw-bold text-dark" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2 text-dark"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </li>

    </ul>
</nav>
