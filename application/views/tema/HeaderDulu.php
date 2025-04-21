<!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?= $title; ?></title>
  
  
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>loading.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/daterangepicker/daterangepicker.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>ambil/apis.css">
  <link rel="stylesheet" href="<?= base_url('asset/'); ?>popover.js">
  <link rel="icon" type="image/png" href="<?= base_url('asset/');  ?>orchard_small.png"/>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.2/css/fixedHeader.dataTables.min.css">


  <script>
    var base_url = '<?= base_url() ?>' // Buat variabel base_url agar bisa di akses di semua file js
  </script>
  
</head>

<style type="text/css">
  .tombolmenu:hover{
    background-color: white;
    color: blue;

  }

  .bg-gradient
  {
    background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%);
    color: white;
  }

  table {
    text-align: left;
    position: relative;
    border-collapse: collapse;

  }
  th, td {
    padding: 0.25rem;
  }

  th {
    background:#F78CA0;
    position: sticky;
    color: white;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
    border: #F78CA0;
  }
  td {
    text-transform: lowercase;
  }


  #loading{
    width: 60px;
    height: 60px;
    border: solid 5px #ccc;
    border-top-color: #ff6a00;
    border-radius: 100%;

    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    margin: auto;


    animation: putar 1s linear infinite;
  }

  @keyframes putar{
    from{transform: rotate(0deg);}
    to{transform: rotate(360deg);}
  }
  


</style>

<?php 
$beda = $this->session->userdata('salon');
if(!$beda){
  header("Location: Login");
  exit;
}

?>

<!-- <body class="hold-transition sidebar-mini layout-navbar-fixed" style="padding: -5px;"> -->
  <body class="hold-transition layout-top-nav" style=" font-family: sans-serif;">
    <div id="loading"></div>
    <div class="wrapper">

      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow" style=" background-image: linear-gradient(to right, #f78ca0 0%, #f9748f 19%, #fd868c 60%, #fe9a8b 100%); color: white;">

        <!-- <nav class="main-header navbar navbar-expand-md navbar-light navbar-white"> -->
          <div class="container">
            <img src="<?= base_url('asset/'); ?>/orchard_small.png" style="width:50px;" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            data-toggle="modal" data-target="#modal-user">
            <span class="brand-text font-weight-light family-Centaur style-bold"><b>ORCHARD </b></span>

            <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse order-3" id="navbarCollapse">
              <!-- Left navbar links -->
              <ul class="navbar-nav ">
                <li class="nav-item ">
                  <a class="nav-link" data-toggle="modal" data-target="#modal-menu"  href=""><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Match/app'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;">Appointment</a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Match/appoiment'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;">App</a>
                </li>
                
                
                <li class="nav-item">
                  <a href="<?= base_url('Match/cancel'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;">Cancel</a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Match/customer'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;">Customer</a>
                </li>
                <li class="nav-item">
                  <a href="<?= base_url('Match/dt_servis'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;">Servis</a>
                </li>
                <li class="nav-item dropdown">
                  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link shadow dropdown-toggle" style="color: white;">Produk</a>
                  <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="<?= base_url('Match/kategori'); ?>" class="dropdown-item">Kategori</a></li>
                    <li><a href="<?= base_url('Match/produk'); ?>" class="dropdown-item">Produk</a></li>
                    <li><a href="<?= base_url('Match/kelolaProduk'); ?>" class="dropdown-item">Kelola Produk</a></li>
                    <li><a href="<?= base_url('Match/produk_masuk'); ?>" class="dropdown-item">Produk Masuk</a></li>
                  </ul>
                </li>
                <li class="nav-item dropdown">
                  <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link shadow dropdown-toggle" style="color: white;">Catatan</a>
                  <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <!-- <li><a href="<?= base_url('Match/absen'); ?>" class="dropdown-item">Absen Harian</a></li>
                    <li><a href="<?= base_url('Match/denda'); ?>" class="dropdown-item">Denda</a></li>
                    <li><a href="<?= base_url('Match/kasbon'); ?>" class="dropdown-item">Kasbon</a></li>
                    <li><a href="<?= base_url('Match/tips'); ?>" class="dropdown-item">Tips</a></li> -->
                  </ul>
                </li>
                <?php if ($this->session->userdata('id_role')=='1'): ?>
                  <li class="nav-item dropdown">
                    <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link shadow dropdown-toggle" style="color: white;">More</a>
                    <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                      <li><a href="<?= base_url('Match/dt_anak'); ?>" class="dropdown-item">Tb Karyawan</a></li>
                      <?php if ($this->session->userdata('id_role')=='1'): ?>
                        <li><a href="<?= base_url('Match/dt_user'); ?>" class="dropdown-item">Tb User</a></li>
                        <li><a href="<?= base_url('Match/trash'); ?>" class="dropdown-item">Clear-Data</a></li>
                      <?php endif ?>
                    </ul>
                  </li>
                <?php endif ?>
                <li class="nav-item">
                <a href="<?= base_url('Login/logout'); ?>" id="tombolmenu" class="nav-link shadow" style="color: white;"><i class="fas fa-sign-out-alt"></i></a>
              </li>
              </ul>
            </div>
          </div>
          <!-- <ul class="navbar-nav">
            <li class="nav-item">
              <a href="<?= base_url('Login/logout'); ?>" id="tombolmenu" class="nav-link shadow">Log-Out</a>
            </li>
          </ul> -->
        </nav>


        <div class="modal fade" id="modal-menu">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header bg-info">
                <h4 class="modal-title">Menu Bar</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style=" font-size: 20px;">

                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a href="<?= base_url('Match/pembelian'); ?>" id="tombolmenu" class="nav-link">Pembelian</a>
                  </li>
                  <hr>

                </ul>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modal-user">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header" style="background-color: #FFA07A;">
                <h4 class="modal-title">Data User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style=" font-size: 20px;">
                <?= $this->session->userdata('nm_user'); ?><hr>
                <?= $this->session->userdata('username'); ?><hr>
                <?= $this->session->userdata('password'); ?><hr>
                <?= $this->session->userdata('email'); ?><hr>
                <?= $this->session->userdata('id_role'); ?><hr>
              </div>
            </div>
          </div>
        </div>



        <script>
          var loading = document.getElementById('loading');

          window.addEventListener('load', function(){
            loading.style.display = "none";
          })
        </script>