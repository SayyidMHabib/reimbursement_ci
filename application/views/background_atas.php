<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title><?= $this->session->userdata("username"); ?> | Sistem Informasi Pengajuan Dana</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/dist/css/adminlte.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Select 2 -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/select2/css/select2.css">
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.css">
  <!-- Daterangepicker -->
  <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/daterangepicker/daterangepicker.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    body {
      padding-right: 0px !important;
    }

    /* width */
    ::-webkit-scrollbar {
      width: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
      box-shadow: inset 0 0 5px grey;
      border-radius: 5px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
      background: #e3e3e3;
      border-radius: 5px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
      background: #a1a1a1;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse layout-fixed">
  <input type="hidden" id="base_link" value="<?= base_url(); ?>">
  <!-- jQuery -->
  <script src="<?= base_url("assets"); ?>/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= base_url("assets"); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url("assets"); ?>/dist/js/adminlte.min.js"></script>
  <!-- Custom -->
  <script src="<?= base_url("assets"); ?>/dist/js/ubah_pass.js"></script>
  <script src="<?= base_url("assets"); ?>/dist/js/rch.js"></script>
  <!-- Wysihtml5 -->
  <script src="<?= base_url("assets"); ?>/dist/ckeditor/ckeditor.js"></script>

  <!-- Modal Konfirmasi Ya Tidak -->
  <div class="modal fade" id="frmKonfirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title" id="jdlKonfirm">Konfirmasi Hapus</h4>
        </div>
        <div class="modal-body">
          <div id="isiKonfirm"></div>
          <input type="hidden" name="id" id="id">
          <input type="hidden" name="mode" id="mode">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="yaKonfirm">Ya <b style="font-size:18px;">(نعم)</b></button>
          <button data-dismiss="modal" class="btn btn-danger" id="tidakKonfirm">Tidak <b style="font-size:18px;">(لا)</b></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap modal -->
  <div class="modal fade" id="ubah_pass" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Ubah Password</h3>
        </div>
        <form method="post" id="frm_ubahpass">
          <div class="modal-body form">
            <input type="hidden" name="pgnID" value="<?php $this->session->userdata("id_user"); ?>">
            <div class="form-group">
              <label>Password Lama</label>
              <input type="text" class="form-control infonya" name="log_pass" id="log_pass" placeholder="Password Lama" value="" required>
            </div>
            <div class="form-group">
              <label>Password Baru</label>
              <input type="text" class="form-control infonya" name="log_passBaru" id="log_passBaru" placeholder="Password Baru" value="" required>

            </div>
            <div class="form-group">
              <label>Konfirmasi Password Baru</label>
              <input type="text" class="form-control infonya" name="log_passBaru2" id="log_passBaru2" placeholder="Konfirmasi Password Baru" value="" required>
            </div>
            <div class="alert alert-danger animated fadeInDown" role="alert" id="up_infoalert">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <div id="up_pesan"></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" id="up_simpan" class="btn btn-primary">Simpan</a>
              <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
          </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark navbar-success">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <?php if ($this->session->userdata("id_user")) { ?>
        <ul class="navbar-nav ml-auto">
          <!-- Messages Dropdown Menu -->
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url("Login/logout"); ?>" role="button">
              <i class="fas fa-power-off"></i>
            </a>
          </li>
        </ul>
      <?php } else {
      ?>
        <ul class="navbar-nav ml-auto">
          <!-- Messages Dropdown Menu -->
          <li class="nav-item">
            <a class="nav-link" href="<?= base_url("Login"); ?>" role="button">
              <i class="fas fa-user"></i> Login
            </a>
          </li>
        </ul>
      <?php } ?>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">

      <a href="" class="brand-link">
        <!-- <img src="<?= base_url("assets"); ?>/dist/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light"><i class="fa fa-handshake"></i> <b>Reimbursement</b></span>
      </a>
      <!-- Sidebar -->

      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <?php if ($this->session->userdata("foto")) { ?>
              <img src="<?= base_url("assets/files/foto/{$this->session->userdata("foto")}"); ?>" class="img-circle" alt="User Image" width="50%">
            <?php } else { ?>
              <img src="<?= base_url("assets"); ?>/dist/img/user-blank.png" class="img-circle" alt="User Image">
            <?php } ?>
          </div>
          <div class="info">
            <a href="#" class="d-block"><?= $this->session->userdata("nama"); ?></a>
            <?php
            $level = "";
            switch ($this->session->userdata("level")) {
              case 0:
                $level = "Administrator";
                break;
              case 1:
                $level = "Direktur";
                break;
              case 2:
                $level = "Finance";
                break;
              case 3:
                $level = "Staff";
                break;
            }
            ?>
            <a href="#" class="d-block"><?= $level; ?></a>
          </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= base_url("Dashboard/tampil"); ?>" class="nav-link <?= ($this->uri->segment(1) === 'Dashboard') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <?php if ($this->session->userdata("level") <= 1) { ?>
              <li class="nav-item">
                <a href="" class="nav-link">
                  <i class="nav-icon fas fa-archive"></i>
                  <p>
                    Data Master
                    <i class="right fas fa-angle-left"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item" style="padding-left: 20px;">
                    <a href="<?= base_url("Pegawai/tampil") ?>" class="nav-link">
                      <i class="nav-icon fas fa-id-card"></i>
                      <p>
                        Pegawai
                      </p>
                    </a>
                  </li>
                </ul>
                <ul class="nav nav-treeview">
                  <li class="nav-item" style="padding-left: 20px;">
                    <a href="<?= base_url("Pengguna/tampil") ?>" class="nav-link">
                      <i class="nav-icon fas fa-user-alt"></i>
                      <p>
                        Pengguna
                      </p>
                    </a>
                  </li>
                </ul>
              </li>
            <?php } ?>
            <?php if ($this->session->userdata("level") != 2) { ?>
              <li class="nav-item">
                <a href="<?= base_url("Pengajuan/tampil"); ?>" class="nav-link <?= ($this->uri->segment(1) === 'Pengajuan') ? 'active' : '' ?>">
                  <i class="nav-icon fas fa-file-invoice"></i>
                  <p>
                    Pengajuan
                  </p>
                </a>
              </li>
            <?php } ?>
            <li class="nav-item">
              <a href="<?= base_url("Progress/tampil"); ?>" class="nav-link <?= ($this->uri->segment(1) === 'Progress') ? 'active' : '' ?>">
                <i class="nav-icon fas fa-chart-line"></i>
                <p>
                  Progress Pengajuan
                </p>
              </a>
            </li>
          </ul>
        </nav>

        <nav class="mt-2 pt-3" style="border-top:1px solid #595959;">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="#" data-target="#ubah_pass" data-toggle="modal" class="nav-link">
                <i class="nav-icon fas fa-lock"></i>
                <p>
                  Ubah Password
                </p>
              </a>
            <li class="nav-item">
              <a href="<?= base_url("Login/logout"); ?>" class="nav-link">
                <i class="nav-icon fas fa-power-off"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-12">
              <marquee style="background:white;border-radius:5px; color:black; " scrolldelay="1" scrollamount="3" direction="left">
                <b>Reimbursement - Sistem Informasi Pengajuan Dana</b> &copy; <?= date('Y'); ?>. All Right Reserved.
              </marquee>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content pt-2">