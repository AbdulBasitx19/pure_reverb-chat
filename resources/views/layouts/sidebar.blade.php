<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">
            
            {{-- <!-- TODO: Baad mein jab routes ban jayein, toh yeh PHP logic wapis activate kar lena -->
            {{-- php
                $isActiveDashboard = request()->routeIs('dashboard');
                $isActiveUsersMenu = request()->routeIs('admin.roles.*') || request()->routeIs('admin.users.*');
            endphp --}} 

            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>
                
                <!-- ========================================== -->
                <!-- 0. DASHBOARD (Added for Templating Test) -->
                <!-- ========================================== -->
                <li class="nav-item">
                    <!-- TODO: Jab route ban jaye toh href="{{ route('dashboard') }}" kar dena -->
                    <a href="#" class="nav-link menu-link active"> 
                        <i class="ri-dashboard-2-line"></i> <span data-key="t-dashboards">Dashboard</span>
                    </a>
                </li>

                {{-- <!-- ========================================== -->
                <!-- 1. USER MODULE (Temporarily Safe for Templating) -->
                <!-- ========================================== -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarUsers" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarUsers">
                        <i class="ri-group-line"></i> <span data-key="t-user">User Management</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarUsers">
                        <ul class="nav nav-sm flex-column">
                            <!-- TODO: Baad mein can('view-roles') aur route('admin.roles.index') yahan lagana -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="ri-shield-user-line align-middle me-1"></i> Roles 
                                </a>
                            </li>
                            <!-- TODO: Baad mein can('view-users') aur route('admin.users.index') yahan lagana -->
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="ri-user-line align-middle me-1"></i> Users 
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                {{-- <!-- ========================================== -->
                <!-- 2. TASKS MODULE -->
                <!-- ========================================== -->
                <li class="nav-item">
                    <!-- TODO: Baad mein route('admin.tasks.index') lagana -->
                    <a href="#" class="nav-link menu-link">
                        <i class="ri-task-line"></i> <span>Tasks</span>
                    </a>
                </li> --}}

                {{-- <!-- ========================================== -->
                <!-- 3. PROFILE MODULE -->
                <!-- ========================================== -->
                <li class="nav-item">
                    <!-- TODO: Baad mein route('profile.index') lagana -->
                    <a href="#" class="nav-link menu-link">
                        <i class="ri-user-settings-line"></i> <span>Profile</span>
                    </a>
                </li> --}}
                
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->