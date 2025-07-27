<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>CCD Inventory Management System</title>

  <!-- Fonts & Styles -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet" />
  <link id="pagestyle" href="{{ asset('template/soft-ui-dashboard-main/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />

  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f4f4f4;
    }

    .login-container {
      display: flex;
      height: 100vh;
      overflow: hidden;
      flex-wrap: wrap;
    }

    .left-panel {
      flex: 1;
      min-width: 300px;
      background: linear-gradient(to bottom right, #ff3c3c, #b30000);
      color: white;
      padding: 60px 40px;
      position: relative;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      box-sizing: border-box;
      z-index: 1;
    }

    .left-text-wrapper {
      position: relative;
      z-index: 2;
      max-width: 100%;
      word-break: break-word;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 10px 60px 10px 20px; /* kanan diberi 60px agar menjauh dari curve */
      box-sizing: border-box;
    }

    .curved-text {
      font-size: 42px;
      font-weight: 700;
      font-family: 'Poppins', sans-serif;
      color: white;
      text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.3);
      letter-spacing: 1px;
    }

    .curve {
      position: absolute;
      top: 0;
      right: 0;
      width: 80px;
      height: 100%;
      background-color: white;
      border-top-left-radius: 100% 100%;
      border-bottom-left-radius: 100% 100%;
      z-index: 0;
      pointer-events: none;
      transition: width 0.3s ease;
    }

    .right-panel {
      flex: 1;
      min-width: 300px;
      background-color: white;
      padding: 60px 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      position: relative;
      z-index: 2;
      box-sizing: border-box;
    }

    .card {
      border: none;
      background: transparent;
    }

    .form-control {
      border-radius: 10px;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(255, 60, 60, 0.25);
      border-color: #ff3c3c;
    }

    .btn.bg-gradient-danger {
      background: #ff3c3c;
    }

    .btn.bg-gradient-danger:hover {
      background: #b30000;
    }

    .text-primary {
      color: #b30000 !important;
    }

    .card-header h3 {
      font-size: 28px;
      word-wrap: break-word;
    }

    .card-header p {
      font-size: 16px;
      word-wrap: break-word;
    }

    /* Responsive styles */
    @media (max-width: 1200px) {
      .curve {
        width: 60px;
      }
    }

    @media (max-width: 768px) {
      .login-container {
        flex-direction: column;
      }

      .left-panel {
        padding: 30px 20px;
        min-height: 250px;
      }

      .left-panel h2,
      .curved-text {
        font-size: 24px;
      }

      .card-header h3 {
        font-size: 22px;
        text-align: center;
      }

      .card-header p {
        font-size: 14px;
        text-align: center;
      }

      .curve {
        display: none;
      }

      .left-text-wrapper {
        padding: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="login-container">
    <!-- KIRI -->
    <div class="left-panel">
      <div class="left-text-wrapper">
        <h2 class="curved-text">CCD Inventory Management System</h2>
        <h2 class="curved-text">IAA</h2>
      </div>
      <div class="curve"></div>
    </div>

    <!-- KANAN -->
    <div class="right-panel">
      <div class="card card-plain">
        <div class="card-header pb-0 text-left bg-transparent">
          <h3 class="font-weight-bolder" style="color: #ff3c3c;">Hello there! Welcome Back</h3>
          <p class="mb-0">Enter your email and password to sign in!</p>
        </div>

        <div class="card-body">
          @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          @if (session('loginError'))
            <p style="color: red; font-weight: bold;">
              {{ session('loginError') }}
            </p>
          @endif

          <form method="POST" action="{{ route('login.attempt') }}">
            @csrf

            <label>Email</label>
            <div class="mb-3">
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     placeholder="Email" required autofocus value="{{ old('email') }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <label>Password</label>
            <div class="mb-3 position-relative">
              <input type="password" name="password" id="password"
                     class="form-control @error('password') is-invalid @enderror"
                     placeholder="Password" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <input type="checkbox" id="showPassword" class="mt-2"> Show Password
            </div>

            <script>
              document.getElementById('showPassword').addEventListener('change', function () {
                const passwordInput = document.getElementById('password');
                passwordInput.type = this.checked ? 'text' : 'password';
              });
            </script>

            <div class="text-center">
              <button type="submit" class="btn bg-gradient-danger w-100 mt-4 mb-0">Sign in</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="{{ asset('template/soft-ui-dashboard-main/assets/js/core/popper.min.js') }}"></script>
  <script src="{{ asset('template/soft-ui-dashboard-main/assets/js/core/bootstrap.min.js') }}"></script>
  <script src="{{ asset('template/soft-ui-dashboard-main/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
  <script src="{{ asset('template/soft-ui-dashboard-main/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), { damping: '0.5' });
    }
  </script>
</body>
</html>
