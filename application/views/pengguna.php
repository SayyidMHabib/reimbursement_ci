<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:log_tambah()" class="btn btn-success btn-block"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah</a>
			</div>
		</div>
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:drawTable()" class="btn btn-success btn-block"><i class="fa fa-sync-alt"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>
	</div>
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					Data Pengguna
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-bordered table-hover" id="tabel-pengguna" width="100%" style="font-size:120%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Username</th>
								<th>Nama</th>
								<th>Level</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="3" align="center">Tidak ada data</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_pengguna">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Pengguna</h3>
			</div>
			<form role="form  col-lg-6" name="Pengguna" id="frm_pengguna">
				<div class="modal-body form">
					<div class="row">
						<input type="hidden" id="log_id" name="log_id" value="">
						<!-- <input type="hidden" id="log_ins_id" name="log_ins_id" value=1> -->
						<!-- Nama Pengguna -->
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nama Pengguna</label>
								<select class="form-control select2bs4" name="log_peg_id" id="log_peg_id">
									<option value="">== Pilih ==</option>
									<?php foreach ($pegawai as $p) { ?>
										<option value="<?= $p->peg_id ?>"><?= $p->peg_nama ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<!-- Username -->
						<div class="col-lg-6">
							<div class="form-group">
								<label>Username</label>
								<input type="text" class="form-control" name="log_user" id="log_user" placeholder="username">
							</div>
						</div>
						<div class="col-lg-6">
							<!-- Input Level User -->
							<div class="form-group">
								<label>Level</label>
								<select class="form-control select2bs4" name="log_level" id="log_level">
									<option value="">== Pilih Level ==</option>
									<option value="1">Direktur</option>
									<option value="2">Finance</option>
									<option value="3">Staff</option>
								</select>
							</div>
						</div>
						<!-- Password -->
						<div class="col-lg-6">
							<div class="form-group">
								<label>Password</label>
								<input type="password" class="form-control" name="log_pass" id="log_password" placeholder="Password">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="log_simpan" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="reset_form()">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- DataTables -->
<script src="<?= base_url("assets"); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.flash.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.colVis.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/pdfmake.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/vfs_fonts.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/datatables-buttons/js/jszip.min.js"></script>
<!-- date-range-picker -->
<script src="<?= base_url("assets"); ?>/plugins/moment/moment.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/inputmask/min/jquery.inputmask.bundle.min.js"></script>
<script src="<?= base_url("assets"); ?>/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?= base_url("assets"); ?>/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Select 2 -->
<script src="<?= base_url("assets"); ?>/plugins/select2/js/select2.full.min.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<!-- Custom Java Script -->
<!-- <script src="<?= base_url("assets/"); ?>dist/js/pengguna.js"></script> -->
<script>
	var save_method; //for save method string
	var table;

	function drawTable() {
		$('#tabel-pengguna').DataTable({
			"destroy": true,
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 25, 50, -1],
				['10 rows', '25 rows', '50 rows', 'Show all']
			],
			buttons: [
				'copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'
			],
			// "oLanguage": {
			// "sProcessing": '<center><img src="<?= base_url("assets/"); ?>assets/img/fb.gif" style="width:2%;"> Loading Data</center>',
			// },
			"responsive": true,
			"sort": true,
			"processing": true, //Feature control the processing indicator.
			"serverSide": true, //Feature control DataTables' server-side processing mode.
			"order": [], //Initial no order.
			// Load data for the table's content from an Ajax source
			"ajax": {
				"url": "ajax_list_pengguna/",
				"type": "POST"
			},
			//Set column definition initialisation properties.
			"columnDefs": [{
				"targets": [-1], //last column
				"orderable": false, //set not orderable
			}, ],
			"initComplete": function(settings, json) {
				$("#process").html("<i class='glyphicon glyphicon-search'></i> Process")
				$(".btn").attr("disabled", false);
				$("#isidata").fadeIn();
			}
		});
	}

	function reset_form() {
		$("#log_id").val(0);
		$("#log_peg_id").val("").trigger("change");
		$("#log_level").val("").trigger("change");
		$("#frm_pengguna")[0].reset();

	}

	function log_tambah() {
		reset_form();
		$("#log_id").val(0);
		$('#modal_pengguna').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	$("#frm_pengguna").submit(function(e) {
		e.preventDefault();
		$("#log_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "simpan",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var res = JSON.parse(d);
				var msg = "";
				if (res.status == 1) {
					toastr.success(res.desc);
					drawTable();
					reset_form();
					$("#modal_pengguna").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#log_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#log_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});

	});

	function hapus_pengguna(id) {
		event.preventDefault();
		$("#log_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_pengguna(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "log_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					if (dt[0] == "log_level") {
						$("#" + dt[0]).val(dt[1]).change();
					} else if (dt[0] == "log_peg_id") {
						$("#log_peg_id").val(dt[1]).change();
					} else {
						$("#" + dt[0]).val(dt[1]);
					}
				})
				$(".inputan").attr("disabled", false);
				$("#modal_pengguna").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	$("#showPass").click(function() {
		var st = $(this).attr("st");
		if (st == 0) {
			$("#log_passnya").attr("type", "text");
			$("#matanya").removeClass("fa-eye");
			$("#matanya").addClass("fa-eye-slash");
			$(this).attr("st", 1);
		} else {
			$("#log_passnya").attr("type", "password");
			$("#matanya").removeClass("fa-eye-slash");
			$("#matanya").addClass("fa-eye");
			$(this).attr("st", 0);
		}
	});

	$("#yaKonfirm").click(function() {
		var id = $("#log_id").val();

		$("#isiKonfirm").html("Sedang menghapus data...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "GET",
			url: "hapus/" + id,
			success: function(d) {
				var res = JSON.parse(d);
				var msg = "";
				if (res.status == 1) {
					toastr.success(res.desc);
					$("#frmKonfirm").modal("hide");
					drawTable();
				} else {
					toastr.error(res.desc + "[" + res.err + "]");
				}
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	});


	$('.tgl').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		showDropdowns: true,
		singleDatePicker: true,
		"autoAplog": true,
		opens: 'left'
	});

	$(document).ready(function() {
		drawTable();

		$('.select2bs4').select2({
			// dropdownParent: $('.modal-body'),
			theme: 'bootstrap4'
		})

	});
</script>