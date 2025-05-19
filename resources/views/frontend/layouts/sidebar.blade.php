<div class="app-sidebar-menu">
            <div class="h-100" data-simplebar>

                <!--- Sidemenu -->
                <div id="sidebar-menu">

                    <div class="logo-box">
                        <a class='logo logo-light' href='index.html'>
                            <span class="logo-sm">
                                <img src="{{ asset('frontend/assets/images/logo-sm.png') }}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('frontend/assets/images/logo-light.png') }}" alt="" height="24">
                            </span>
                        </a>
                        <a class='logo logo-dark' href='index.html'>
                            <span class="logo-sm">
                                <img src="{{ asset('frontend/assets/images/logo-sm.png') }}" alt="" height="22">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('frontend/assets/images/logo-dark.png') }}" alt="" height="24">
                            </span>
                        </a>
                    </div>

                    <ul id="side-menu">

                        <li>
                            <a href="#sidebarProfile" data-bs-toggle="collapse">
                                <i data-feather="user"></i>
                                <span> Profile </span>
                            </a>
                            <div class="collapse" id="sidebarDashboards"> </div>
                        </li>

                        <li>
                            <a href="#sidebarApplication" data-bs-toggle="collapse">
                                <i data-feather="file-text"></i>
                                <span> Application </span>
                            </a>
                            <div class="collapse" id="sidebarDashboards"> </div>
                        </li>

                        <li>
                            <a href="#sidebarDashboards" data-bs-toggle="collapse">
                                <i data-feather="dollar-sign"></i>
                                <span> Payments </span>
                                <span class="menu-arrow"></span>
                            </a>
                            <div class="collapse" id="sidebarDashboards">
                                <ul class="nav-second-level">
                                    <li>
                                        <a class='tp-link' href='index.html'>Registration Payment</a>
                                    </li>
                                    <li>
                                        <a class='tp-link' href='analytics.html'>Course Payments</a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                        <li>
                            <a href="#sidebarDashboards" data-bs-toggle="collapse">
                                <i data-feather="book"></i>
                                <span> Courses </span>
                            </a>
                            <div class="collapse" id="sidebarDashboards"> </div>
                        </li>

                    </ul>
        
                </div>
                <!-- End Sidebar -->

                <div class="clearfix"></div>

            </div>
        </div>