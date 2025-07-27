<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true" style="font-family: sans-serif;">
  <div class="container-fluid py-1 px-3">
    
    <div class="d-flex flex-column">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page" style="font-size: 1.1rem;">
          Hello, {{ Auth::user()->name ?? 'Guest' }}!
        </li>
      </ol>
      <h5 class="font-weight-bolder mb-0" style="font-size: 1.3rem;">@yield('page-title', 'Dashboard')</h5>
    </div>

    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <!-- Optional: search or other tools -->
      </div>
      <ul class="navbar-nav justify-content-end">
        @if(Auth::user() && Auth::user()->role === 'admin')
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-body" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-user-circle"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
            <li><a class="dropdown-item" href="{{ route('admin.personal-profile') }}">My Profile</a></li>
          </ul>
        </li>
        @endif
        <li class="nav-item d-flex align-items-center">
          
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link text-body font-weight-bold px-0 bg-transparent border-0" style="font-size: 1.1rem;">
              <i class="fa fa-sign-out-alt me-sm-1"></i>
              <span class="d-sm-inline d-none">Logout</span>
            </button>
          </form>
        </li>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
