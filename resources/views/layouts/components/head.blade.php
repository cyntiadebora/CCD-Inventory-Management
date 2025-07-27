<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Favicon dan Apple Touch Icon -->
  <link rel="apple-touch-icon" sizes="76x76" href="/template/soft-ui-dashboard-main/assets/img/apple-icon.png" />
  <link rel="icon" type="image/png" href="/template/soft-ui-dashboard-main/assets/img/favicon.png" />

  <title>CCD Inventory Management System</title>

  <!-- Fonts and Icons -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- Nucleo Icons CSS -->
  <link href="/template/soft-ui-dashboard-main/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="/template/soft-ui-dashboard-main/assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome CSS dan JS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  
  <!-- Untuk pop-up di dashboard -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Untuk centang hijau di List Item Non Size-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Soft UI Dashboard CSS -->
  <link id="pagestyle" href="/template/soft-ui-dashboard-main/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />

  <!-- Custom Styles -->
  <style>
  /* Style untuk nav-link aktif */
  .nav-link.active {
    background-color: #ff0000 !important;
    color: white !important;
  }

  .nav-link.active .icon {
    background-color: #ff0000 !important;
    color: white !important;
  }

  /* Font untuk sidenav */
  #sidenav-main {
    font-family: 'Inter', sans-serif;
  }

  #sidenav-main .nav-link-text {
    font-family: 'Inter', sans-serif;
    font-size: 16px;
  }

  /* Font untuk navbar kecuali icon FontAwesome */
  .navbar {
    font-family: 'Inter', sans-serif;
  }

  .navbar .fa,
  .navbar .fas,
  .navbar .far,
  .navbar .fal,
  .navbar .fab {
    font-family: "Font Awesome 5 Free", "Font Awesome 5 Brands", "Font Awesome 5 Pro" !important;
  }

  thead th {
    color: #001f3f !important; /* biru dongker */
  }

  .border-merah-cerah {
    border-right: 3px solid #ff0000; /* merah cerah */
    border-bottom: 3px solid #ff0000;
    border-top: none;
    border-left: none;
    border-radius: 0.5rem; /* opsional, biar sudutnya lembut */
  }

  /* Mask harus full cover dan di bawah isi card */
  .mask {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: inherit; /* ikut border radius card */
    z-index: 0;
  }

  /* Card harus relative supaya mask bisa absolute */
  .card {
    position: relative;
    overflow: hidden; /* agar mask tidak keluar dari card */
  }

  /* Isi card di atas mask */
  .card-body {
    position: relative;
    z-index: 1;
  }
</style>


  <!-- Nepcha Analytics -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
</head>
