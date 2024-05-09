<div class="inner">
	<div class="row">

		<?php if ($this->session->userdata("level") <= 1) : ?>
			<div class="col-md-2 col-xs-12">
				<div class="form-group">
					<a href="javascript:tambah()" class="btn btn-success btn-block"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah</a>
				</div>
			</div>
		<?php endif; ?>
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
					Data Pegawai
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-bordered table-hover" id="tabel-pegawai" width="100%" style="font-size:120%;">
						<thead class="text-center">
							<tr>
								<th>No</th>
								<th>Foto</th>
								<th>Nama Pegawai</th>
								<th>Jenis Kelamin</th>
								<th>Tanggal Lahir</th>
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

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_pegawai" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Pegawai</h3>
			</div>
			<form role="form col-lg" name="Pegawai" enctype="multipart/form-data" id="frm_pegawai">
				<div class="modal-body form">
					<div class="row">
						<input type="hidden" id="peg_id" name="peg_id" value="">
						<div class="col-lg-6">
							<div class="form-group">
								<label>NIP</label>
								<input type="text" class="form-control" name="peg_nip" id="peg_nip" placeholder="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Nama Pegawai</label>
								<input type="text" class="form-control" name="peg_nama" id="peg_nama" placeholder="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Jenis Kelamin</label>
								<select class="form-control" name="peg_jk" id="peg_jk" placeholder="">
									<option value="1">Laki-laki</option>
									<option value="2">Perempuan</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tempat Lahir</label>
								<input type="text" class="form-control" name="peg_tpt_lahir" id="peg_tpt_lahir" placeholder="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Lahir</label>
								<input type="text" class="form-control tgl" name="peg_tgl_lahir" id="peg_tgl_lahir" value="<?= date("d/m/Y"); ?>" placeholder="">
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Foto</label>
								<div class="row">
									<div class="col-md-4">
										<img class="img-thumbnail img-preview" src="<?= base_url("assets/dist/img/no-image.jpg"); ?>">
									</div>
									<div class="col-md-2">
										<input type="file" accept=".jpg, .jpeg, .png" class="filestyle" id="peg_foto" name="peg_foto" data-buttonname="btn-white" onchange="previewImg()">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="peg_simpan" class="btn btn-success">Simpan</button>
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
<script src="<?= base_url("assets"); ?>/plugins/select2/select2.js"></script>

<!-- Toastr -->
<script src="<?= base_url("assets"); ?>/plugins/toastr/toastr.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

<!-- Custom Java Script -->
<!-- <script src="<?= base_url("assets/"); ?>dist/js/pegawai.js"></script> -->
<script>
	var save_method; //for save method string
	var table;

	function previewImg() {
		const foto = document.querySelector('#peg_foto');
		const imgPreview = document.querySelector('.img-preview');

		const fileFoto = new FileReader();
		fileFoto.readAsDataURL(foto.files[0]);

		fileFoto.onload = function(e) {
			imgPreview.src = e.target.result;
		}
	}

	function drawTable() {
		$('#tabel-pegawai').DataTable({
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
				"url": "ajax_list_pegawai/",
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
		$("#peg_id").val(0);
		$("#frm_pegawai")[0].reset();
		$(".img-preview").attr('src', '<?= base_url("assets/dist/img/no-image.jpg"); ?>');
	}

	function tambah() {
		$("#peg_id").val(0);
		$("#frm_pegawai")[0].reset();
		$('#modal_pegawai').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	$("#frm_pegawai").submit(function(e) {
		e.preventDefault();
		$("#peg_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "simpan",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var res = JSON.parse(d);
				// console.log(d);
				var msg = "";
				if (res.status == 1) {
					toastr.success(res.desc);
					drawTable();
					reset_form();
					$("#modal_pegawai").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#peg_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#peg_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});
	});

	function ubah_pegawai(id) {
		event.preventDefault();
		$("#peg_id").val(id);
		$.ajax({
			type: "POST",
			url: "cari",
			data: "peg_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					if (dt[0] == "peg_tgl_lahir") {
						var tgl = dt[1].split("-");
						$("#" + dt[0]).val(tgl[2] + "/" + tgl[1] + "/" + tgl[0]);
					} else if (dt[0] == "peg_foto") {
						$(".img-preview").attr("src", "<?= base_url('assets/files/foto/') ?>" + dt[1]);
					} else {
						$("#" + dt[0]).val(dt[1]);
					}
				});
				$(".inputan").attr("disabled", false);
				$("#modal_pegawai").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}


	function hapus_pegawai(id) {
		event.preventDefault();
		$("#peg_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	$("#yaKonfirm").click(function() {
		var id = $("#peg_id").val();

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
	});
</script>