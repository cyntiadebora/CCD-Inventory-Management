<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3" id="sidenav-main">
  <div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0">
      <img src="/images/logo.png" class="navbar-brand-img h-100" alt="main_logo">
      <span class="ms-1 font-weight-bold">IAA CCD Inventory</span>
    </a>
  </div>

  <hr class="horizontal dark mt-0">

  <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
    <ul class="navbar-nav">
      <!-- Dashboard -->
      <li class="nav-item">
        @php
          $dashboardRoute = auth()->user()->role == 'admin' ? route('dashboard') : route('cabin.crew');
        @endphp
        <a class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('cabin.crew') ? 'active' : '' }}" href="{{ $dashboardRoute }}">
          <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
            <svg width="12px" height="12px" viewBox="0 0 45 40" xmlns="http://www.w3.org/2000/svg">
              <g fill="none" fill-rule="evenodd">
                <g fill="#FFFFFF" fill-rule="nonzero">
                  <g>
                    <path class="color-background opacity-6" d="M46.72,10.74L40.84,0.95C40.49,0.36,39.85,0,39.17,0H7.83C7.15,0,6.51,0.36,6.16,0.95L0.28,10.74C0.1,11.05,0,11.39,0,11.75C-0.01,16.07,3.48,19.57,7.8,19.58H7.82C9.75,19.59,11.62,18.87,13.05,17.58C16.02,20.26,20.53,20.26,23.49,17.58C26.46,20.26,30.98,20.26,33.95,17.58C36.24,19.65,39.54,20.17,42.37,18.91C45.19,17.65,47.01,14.84,47,11.75C47,11.39,46.9,11.05,46.72,10.74Z"/>
                    <path class="color-background" d="M39.2,22.49C37.38,22.49,35.58,22.01,33.95,21.1L33.92,21.11C31.14,22.68,27.93,22.93,24.98,21.8C24.48,21.61,23.98,21.37,23.5,21.1L23.47,21.11C20.7,22.69,17.48,22.93,14.54,21.8C14.03,21.61,13.53,21.37,13.05,21.1C11.43,22.02,9.63,22.49,7.82,22.49C7.17,22.48,6.52,22.42,5.88,22.29V44.72C5.88,45.95,6.75,46.95,7.83,46.95H19.58V33.61H27.42V46.95H39.17C40.25,46.95,41.13,45.95,41.13,44.72V22.28C40.49,22.41,39.84,22.48,39.2,22.49Z"/>
                  </g>
                </g>
              </g>
            </svg>
          </div>
          <span class="nav-link-text ms-1">Dashboard</span>
        </a>
      </li>

      <!-- Cabin Crew: Request Item -->
      @if (auth()->user()->role == 'cabin_crew')
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('requests.create') ? 'active' : '' }}" href="{{ route('requests.create') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 7L12 12L21 7" stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M21 10V17C21 18.1046 20.1046 19 19 19H5C3.89543 19 3 18.1046 3 17V10" stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M3 7L12 2L21 7" stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <span class="nav-link-text ms-1">Request Item</span>
          </a>
        </li>
      @endif
       @if(Auth::user() && Auth::user()->role === 'cabin_crew')
      <!-- Cabin Crew : Request Other Item -->
    <li class="nav-item">
      <a class="nav-link {{ request()->routeIs('requests.createOtherSize') ? 'active' : '' }}" href="{{ route('requests.createOtherSize') }}">
        <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
          <svg width="16px" height="16px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4v16M4 12h16" stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <span class="nav-link-text ms-1">Request New Item Size</span>
      </a>
    </li>
    @endif
    <!-- Admin: Item Management -->
@if (auth()->user()->role == 'admin')
  <li class="nav-item">
    <a class="nav-link {{ request()->routeIs('admin.transactions.index') ? 'active' : '' }}" href="{{ route('admin.transactions.index') }}">
      <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4 6H20M4 12H20M4 18H11" stroke="#344767" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <span class="nav-link-text ms-1">Stock Reports</span>
    </a>
  </li>

      <!-- Admin: User Management -->
      
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
              <svg width="20px" height="20px" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                <g fill="#344767">
                  <circle cx="32" cy="20" r="12"/>
                  <path d="M32 36c-12.15 0-22 5.37-22 12v4h44v-4c0-6.63-9.85-12-22-12z"/>
                </g>
              </svg>
            </div>
            <span class="nav-link-text ms-1">User Management</span>
          </a>
        </li>
      @endif

    </ul>
  </div>
</aside>
