<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <div style="text-align: center;">
            <img src="{{ asset('backend/AdminAssets/backend/dist/img/logo2.png') }}"
                 alt="Logo"
                 class="brand-image img-circle elevation-3"
                 style=" width: 150px; height: 150px; object-fit: contain;">
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Main Menu: Dashboard -->
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Main Menu: CMS -->
                <li class="nav-item {{ request()->routeIs([
                        'categories.index',
                        'category.index',
                        'subcategories.index',
                        'online-courses.index',
                        'top-course.index',
                        'about-us.index',
                        'subscriptions.index'
                    ]) || request()->is('admin/categories/*')
                      || request()->is('admin/category/*')
                      || request()->is('admin/subcategories/*')
                      || request()->is('admin/online-courses/*')
                      || request()->is('admin/top-course/*')
                      || request()->is('admin/about-us/*')
                      || request()->is('admin/subscriptions/*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->routeIs([
                            'categories.index',
                            'category.index',
                            'subcategories.index',
                            'online-courses.index',
                            'top-course.index',
                            'about-us.index',
                            'subscriptions.index'
                        ]) || request()->is('admin/categories/*')
                          || request()->is('admin/category/*')
                          || request()->is('admin/subcategories/*')
                          || request()->is('admin/online-courses/*')
                          || request()->is('admin/top-course/*')
                          || request()->is('admin/about-us/*')
                          || request()->is('admin/subscriptions/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-folder"></i>
                        <p>
                            CMS
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <!-- Sub-menu items under CMS -->
                    <ul class="nav nav-treeview">
                        <!-- Sub-menu: Category -->
                        <li class="nav-item">
                            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Category</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Online Courses -->
                        <li class="nav-item">
                            <a href="{{ route('online-courses.index') }}" class="nav-link {{ request()->routeIs('online-courses.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Online Courses</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Top Course -->
                        <li class="nav-item">
                            <a href="{{ route('top-course.index') }}" class="nav-link {{ request()->routeIs('top-course.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Top Course</p>
                            </a>
                        </li>
                        <!-- Sub-menu: About Us -->
                        <li class="nav-item">
                            <a href="{{ route('about-us.index') }}" class="nav-link {{ request()->routeIs('about-us.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>About Us</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Subscription -->
                        <li class="nav-item">
                            <a href="{{ route('subscriptions.index') }}" class="nav-link {{ request()->routeIs('subscriptions.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Subscription</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Hero Images -->
                        <li class="nav-item">
                            <a href="{{ route('hero-images.index') }}" class="nav-link {{ request()->routeIs('hero-images.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hero Images</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Hero sections -->
                        <li class="nav-item">
                            <a href="{{ route('hero-sections.index') }}" class="nav-link {{ request()->routeIs('hero-images.*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hero Sections</p>
                            </a>
                        </li>
                        <!-- Sub-menu: Contact Us -->
                        <li class="nav-item">
                            <a href="{{ route('contactus.index') }}" class="nav-link {{ request()->routeIs('contact-us.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contact Us</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Main Menu: Student Review -->
                <li class="nav-item {{ request()->routeIs('share.experiance.index') ? 'menu-open' : '' }}">
                    <a href="{{ route('share.experiance.index') }}" class="nav-link {{ request()->routeIs('share.experiance.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Share Experience</p>
                    </a>
                </li>

                <!-- Main Menu: Student Enrollment -->
                <li class="nav-item">
                    <a href="{{route('enrollments.index')}}" class="nav-link {{ request()->routeIs('courses.index') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate"></i>
                        <p>Enrollments</p>
                    </a>
                </li>

                <!-- Main Menu: Earning -->
                <li class="nav-item {{ request()->routeIs('order.index') || request()->is('admin/order/*') ? 'menu-open' : '' }}">
                    <a href="" class="nav-link {{ request()->routeIs('earning.index') || request()->is('admin/earning/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coins"></i>
                        <p>Earning</p>
                    </a>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
