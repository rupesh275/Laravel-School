<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3 sidebar-sticky">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/dashboard*') ? 'active' : '' }}" aria-current="page" href="{{ route('admin.dashboard') }}">
                    <span data-feather="home" class="align-text-bottom"></span>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                    <span data-feather="settings" class="align-text-bottom"></span>
                    Settings (Placeholder)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/students*') ? 'active' : '' }}" href="#">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Students (Placeholder)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/staff*') ? 'active' : '' }}" href="#">
                    <span data-feather="users" class="align-text-bottom"></span>
                    Staff (Placeholder)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/backups*') ? 'active' : '' }}" href="{{ route('admin.backups.index') }}">
                    <span data-feather="hard-drive" class="align-text-bottom"></span>
                    Backups (Placeholder)
                </a>
            </li>
            {{-- More menu items will be added here based on CI sidebar logic --}}
            {{-- This will require dynamic generation based on roles and module settings --}}
        </ul>
    </div>
</nav>
