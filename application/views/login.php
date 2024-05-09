<!DOCTYPE html>

<!-- =========================================================
* Sneat - Bootstrap 5 HTML Admin Template - Pro | v1.0.0
==============================================================

* Product Page: https://themeselection.com/products/sneat-bootstrap-html-admin-template/
* Created by: ThemeSelection
* License: You must have a valid license purchased in order to legally use the theme for your project.
* Copyright ThemeSelection (https://themeselection.com)

=========================================================
 -->
<!-- beautify ignore:start -->
<html lang="en" class="light-style customizer-hide" dir="ltr"  data-theme="theme-default" data-assets-path="<?= base_url("assets"); ?>/login-assets/"
  data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta  name="viewport"   content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | Reimbursement</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"/>

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/login-assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="<?= base_url("assets"); ?>/login-assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="<?= base_url("assets"); ?>/login-assets/js/config.js"></script>
    <!-- Toastr -->
    <link rel="stylesheet" href="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.css">
  </head>

  <body style="background-image: linear-gradient(to right,#770000,#D83F31,#EE9322);">
    <!-- Content -->
    <input type="hidden" id="base_link" value="<?= base_url(); ?>">
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="justify-content-center align-content-center text-center">
                <h1 class="">Reimbursement</h1>
                <h5 class="">Sistem Informasi Pengajuan Dana</h5>
              </div>
              <!-- /Logo -->

              <form id="frm_login" class="mb-3" action="<?= base_url("Login/proses"); ?>" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">NIP</label>
                  <input type="text" class="form-control" name="nip" id="nip" placeholder="Enter NIP" autofocus required>
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" class="form-control" id="password"  name="password" placeholder="Enter Password" aria-describedby="password" required>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-danger d-grid w-100" type="submit">Login</button>
                </div>
              </form>

            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="<?= base_url("assets"); ?>/login-assets/vendor/libs/jquery/jquery.js"></script>
    <script src="<?= base_url("assets"); ?>/login-assets/vendor/libs/popper/popper.js"></script>
    <script src="<?= base_url("assets"); ?>/login-assets/vendor/js/bootstrap.js"></script>
    <script src="<?= base_url("assets"); ?>/login-assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="<?= base_url("assets"); ?>/login-assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="<?= base_url("assets"); ?>/login-assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
    <!-- Toastr -->
	<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

    <script>
		var base_link = $("#base_link").val();

		$("#frm_login").submit(function(e) {
			e.preventDefault();
			$(".btn").attr("disabled", true);
			$.ajax({
				type: "POST",
				url: base_link + "Login/proses",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function(d) {
					var res = JSON.parse(d);
					// alert(d+ " - " + res.status);
					if (res.status == 1) {
						toastr.success('Login Berhasil!<br/>' + res.desc);
						setTimeout(function() {
							document.location.href = "";
						}, 1000);
					} else {
						$(".btn").attr("disabled", false);
						toastr.error('Login Gagal!<br/>' + res.desc);
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$(".btn").attr("disabled", false);
					toastr.error('Error! ' + errorThrown);
				}
			});

		});
	</script>
  </body>
</html>