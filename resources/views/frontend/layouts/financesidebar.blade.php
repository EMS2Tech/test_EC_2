<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar>

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-dark'>
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
                    <a href="/profile">
                        <i data-feather="user"></i>
                        <span> Profile </span>
                    </a>
                    <div class="collapse"> </div>
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
                                <a class='tp-link' href='/admin/payment/manage'>Payment Details</a>
                            </li>
                            <li>
                                <a class='tp-link' href='/admin/payment/request'>Open Payments</a>
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