<?php
// File Size 
function file_size($size)
{
	$ms = "B";
	$sz = number_format($size, 2, ",", ".");
	if ($size > 1024) {
		$sz = number_format($size / 1024, 2, ",", ".");
		$ms = "KB";
	}
	if ($size > 1048576) {
		$sz = number_format($size / 1048576, 2, ",", ".");
		$ms = "MB";
	}
	if ($size > 1073741824) {
		$sz = number_format($size / 1073741824, 2, ",", ".");
		$ms = "GB";
	}
	if ($size > 1099511627776) {
		$sz = number_format($size / 1099511627776, 2, ",", ".");
		$ms = "TB";
	}
	return "{$sz} {$ms}";
}
?>

<style>
	.thumb1 {
		background: 50% 50% no-repeat;
		/* 50% 50% centers image in div */
		width: 100px;
		height: 100px;
		object-fit: cover;
	}
</style>

<!--  -->

<div class="container">
	<div class="row">
		<div class="col-md-4 col-sm-6 col-12">
			<div class="info-box">
				<span class="info-box-icon bg-primary"><i class="fas fa-file-invoice"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Jumlah Pengajuan</span>
					<span class="info-box-number"><?= number_format($pengajuan, 0, ",", "."); ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-12">
			<div class="info-box">
				<span class="info-box-icon bg-success"><i class="fas fa-check-square"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Jumlah Disetujui Direktur</span>
					<span class="info-box-number"><?= number_format($approve_direktur, 0, ",", "."); ?></span>
				</div>
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-12">
			<div class="info-box">
				<span class="info-box-icon bg-info"><i class="fas fa-hand-holding-usd"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Jumlah Dicairkan Finance</span>
					<span class="info-box-number"><?= number_format($cair_finance, 0, ",", "."); ?></span>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- date-range-picker -->
<script src="<?= base_url("assets"); ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Custom Java Script -->
<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<script>
	$('.range').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		"opens": "left",
		// showDropdowns: true,
		"autoApply": true
	});
</script>

<script>
	$(document).ready(function() {})
</script>