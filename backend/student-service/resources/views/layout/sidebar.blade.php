<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-light bg-white" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"></div>
                            <a class="nav-link {{ request()->routeIs('index') ? 'active' : '' }}" href="#">
                                <img src="{{ asset('assets/home.png') }}" alt="Logo" style="height: 25px; margin-right: 8px;">
                                Dashboard
                            </a>
                            <a class="nav-link" href="#">
                                <img src="{{ asset('assets/News.png') }}" alt="Logo" style="height: 25px; margin-right: 8px;">
                                Course
                            </a>
                            </div>
                    </div>
                </nav>
            </div>
        </div>