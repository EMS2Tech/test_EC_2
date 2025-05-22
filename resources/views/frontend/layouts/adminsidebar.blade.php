<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                        <a class='logo logo-dark' href='/profile'>
                            <span class="logo-sm">
                                <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}" alt="" height="30">
                            </span>
                            <span class="logo-lg">
                                <img src="{{ asset('frontend/assets/images/ec_logo.webp') }}" alt="" height="40">
                            </span>
                        </a>
                    </div><br><br>

            <ul id="side-menu">
                <li>
                    <a href="#sidebarDashbord" data-bs-toggle="collapse">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                    <div class="collapse" id="sidebarDashboards"> </div>
                </li>

                <li>
                    <a href="#sidebarCourse" data-bs-toggle="collapse">
                        <i data-feather="book"></i>
                        <span> Courses </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCourse">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='analytics.html'>Course Details</a>
                            </li>
                            <li>
                                <a class='tp-link' href='index.html'>Add New Course</a>
                            </li>
                            <li>
                                <a class='tp-link' href='analytics.html'>Add New Batch</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="#sidebarApplication" data-bs-toggle="collapse">
                        <i data-feather="file-text"></i>
                        <span> Application </span>
                    </a>
                    <div class="collapse" id="sidebarDashboards"> </div>
                </li>

                <li>
                    <a href="#sidebarUsers" data-bs-toggle="collapse">
                        <i data-feather="users"></i>
                        <span> Students </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarUsers">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link' href='index.html'>Students</a>
                            </li>
                            <li>
                                <a class='tp-link' href='analytics.html'>Students</a>
                            </li>
                        </ul>
                    </div>
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
            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>