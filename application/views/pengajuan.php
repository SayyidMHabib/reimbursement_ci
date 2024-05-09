<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<input type="text" class="form-control range" id="filter_tanggal">
			</div>
		</div>
		<!-- Tombol Tamnbah Pengajuan -->
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:tambah()" class="btn btn-success btn-block"><i class="fa fa-plus"></i> &nbsp;&nbsp;&nbsp; Tambah Pengajuan</a>
			</div>
		</div>
		<!-- Tombol Refresh -->
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:refresh()" class="btn btn-success btn-block"><i class="fa fa-sync-alt"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>
	</div>
	<!-- Table Pengajun -->
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					Data Pengajuan
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-bordered table-hover" id="tabel-pengajuan" width="100%" style="font-size:120%;">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>Jenis <br> Dana</th>
								<th>Sifat <br> Kebutuhan</th>
								<th>Jumlah <br> Kebutuhan</th>
								<th>Jumlah <br> Diproses</th>
								<th>Diajukan <br> Oleh</th>
								<th>Progress</th>
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
	<!-- End Table Pengajuan -->
</div>
<!-- Modal Tambah Pengajuan -->
<div class="modal fade" id="modal_pengajuan" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Detail Pengajuan</h3>
			</div>
			<!-- Form Pengajuan -->
			<form role="form  col-lg" name="Pengajuan" id="frm_pengajuan">
				<div class="modal-body form">
					<div class="row">
						<input type="hidden" id="aju_id" name="aju_id" value="">
						<input type="hidden" id="aju_level" name="aju_level" value="<?= $this->session->userdata("level"); ?>">
						<div class="col-lg-6">
							<div class="form-group">
								<label>Jenis Dana</label>
								<select class="form-control" id="jenis_dana" onChange="pilih_jenis_dana(this.value)" placeholder="" required>
									<option value="">== Pilih Jenis Dana ==</option>
									<option value="Operasional">Operasional</option>
									<option value="Penyaluran">Penyaluran</option>
									<option value="-1">Lainnya</option>
								</select>
								<input type="hidden" class="form-control mt-1" id="aju_jenis_dana" name="aju_jenis_dana" placeholder="Jenis Dana" value="" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Sifat Kebutuhan</label>
								<select class="form-control" name="aju_sifat_kebutuhan" id="aju_sifat_kebutuhan" placeholder="" required>
									<option value="">== Pilih Sifat Kebutuhan ==</option>
									<option value="Biasa">Biasa</option>
									<option value="Mendesak">Mendesak</option>
									<option value="Rutin">Rutin</option>
								</select>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label>Tanggal Pengajuan</label>
								<input type="text" class="form-control tgl" name="aju_tgl" id="aju_tgl" value="<?= date("d/m/Y"); ?>" placeholder="" required>
							</div>
						</div>
					</div>
					<hr />
					<div class="row">
						<input type="hidden" id="aju_detailpengajuan" name="aju_detailpengajuan">
						<input type="hidden" id="ajud" name="ajud">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Detail Pengajuan</label>
								<table class="table table-striped table-bordered table-hover" width="100%" style="font-size:100%;">
									<thead>
										<tr>
											<th>No</th>
											<th>Rincian</th>
											<th>Jumlah</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<!-- tbody Detail Pengajuan from view pengajuandetail -->
									<tbody id="view_detailpengajuan">
									</tbody>
								</table>
							</div>
						</div>
						<!-- Rincian Pengajuan Muncul setelah tombol detail pengajuan di klik -->
						<div class="col-lg-12" style="display:none;" id="input_detailpengajuan">
							<div class="row">
								<div class="col-lg-6">
									<div class="form-group">
										<label>Rincian</label>
										<textarea class="form-control" id="ajud_penggunaan_dana" rows="4"></textarea>
									</div>
								</div>
								<div class="col-lg-6">
									<div class="form-group">
										<label>Jumlah Dana</label>
										<input type="number" class="form-control" id="ajud_jml" value="">
									</div>
								</div>
								<div class="col-lg-12" style="text-align:center;">
									<a href="#" onClick="batalDetailPengajuan()" class="btn btn-danger">Batal</a>
									<a href="#" onClick="tambahDetailPengajuan()" class="btn btn-success">Tambah</a>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="aju_simpan" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="reset_form()">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap modal -->
<!-- Modal Detail, proses, lihat pengajuan -->
<div class="modal fade" id="modal_approve" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Konfirmasi Pengajuan</h3>
			</div>
			<form role="form  col-lg" name="Approve" id="frm_approve">
				<div class="modal-body form">
					<div class="row">
						<input type="hidden" id="appr_id" name="id" value="">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Persetujuan</label>
								<select class="form-control" name="approve" id="approve" onChange="cek_approve(this.value)" placeholder="" required>
									<option value="1">Setujui</option>
									<option value="2">Tolak</option>
								</select>
							</div>
						</div>
						<div class="col-lg-12" id="div_alasan" style="display:none;">
							<div class="form-group">
								<label>Alasan</label>
								<textarea class="form-control" name="alasan" id="alasan" value="" placeholder=""></textarea>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-12" id="detailnya"></div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="appr_simpan" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="close_approve()">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--  Modal Detail Pengajuan -->
<div class="modal fade" id="modal_proses" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Detail Pengajuan</h3>
			</div>
			<div class="modal-body form" id="list_pengajuan">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
			</div>
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
<script>
	var save_method; //for save method string
	var table;
	var tgl1 = null;
	var tgl2 = null;

	function drawTable() {
		$('#tabel-pengajuan').DataTable({
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
				"url": "ajax_list_pengajuan/" + tgl1 + "/" + tgl2,
				"type": "POST"
			},
			//Set column definition initialisation properties.
			"columnDefs": [{
					"targets": [-1], //last column
					"orderable": false, //set not orderable
				},
				{
					"targets": [-2, -3], //last column
					"className": "text-right", //set not orderable
				},
			],
			// save ignationpage
			"bStateSave": true,
			"fnStateSave": function(oSettings, oData) {
				localStorage.setItem('DataTables', JSON.stringify(oData));
			},
			"fnStateLoad": function(oSettings) {
				return JSON.parse(localStorage.getItem('DataTables'));
			},
			"initComplete": function(settings, json) {
				$("#process").html("<i class='glyphicon glyphicon-search'></i> Process")
				$(".btn").attr("disabled", false);
				$("#isidata").fadeIn();
			}
		});
	}

	function refresh() {
		tgl1 = null;
		tgl2 = null;
		drawTable();
	}

	function tambah() {
		$("#aju_id").val(0);
		$("#ajud").val("");
		$("#aju_detailpengajuan").val("");
		getDetailPengajuan();
		$("frm_pengajuan").trigger("reset");

		$('#modal_pengajuan').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function lihat_pengajuan(id) {
		$('#modal_proses').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
		$.post("proses_pengajuan", {
			id: id
		}, function(d) {
			$("#list_pengajuan").html(d);
		});
	}

	// Approve
	function approve(id) {
		$("#appr_id").val(id);
		$('#modal_approve').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
		$.post("proses_pengajuan", {
			id: id
		}, function(d) {
			$("#detailnya").html(d);
		});
	}

	function cek_approve(val) {
		if (val == 2) {
			$("#div_alasan").show();
		} else {
			$("#div_alasan").hide();
		}
	}
	// DOM Select Aju Jenis Dana
	function pilih_jenis_dana(val) {
		if (val == "-1") {
			$("#aju_jenis_dana").attr("type", "text");
		} else {
			// console.log(val);
			$("#aju_jenis_dana").attr("type", "hidden");
			$("#aju_jenis_dana").val(val);
		}
	}
	// Form Approve Kadiv
	$("#frm_approve").submit(function(e) {
		e.preventDefault();
		$("#appr_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "approve",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var msg = "";
				if (d == 1) {
					toastr.success("Berhasil disimpan");
					drawTable();
					reset_form();
					$("#modal_approve").modal("hide");
				} else {
					toastr.error("Gagal disimpan");
				}
				$("#appr_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#appr_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});

	});

	function close_approve() {
		$("#modal_approve").modal("hide");
	}

	$("#frm_pengajuan").submit(function(e) {
		e.preventDefault();
		$("#aju_simpan").html("Menyimpan...");
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
					reset_form();
					batalDetailPengajuan();
					drawTable();
					$("#modal_pengajuan").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#aju_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#aju_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});

	});

	// Aksi Tombol Hapus Pengajuan
	function hapus_pengajuan(id) {
		event.preventDefault();
		$("#aju_id").val(id);
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function ubah_pengajuan(id) {
		event.preventDefault();
		batalDetailPengajuan();
		$.ajax({
			type: "POST",
			url: "cari",
			data: "aju_id=" + id,
			dataType: "json",
			success: function(data) {
				var obj = Object.entries(data);
				obj.map((dt) => {
					if (dt[0] == "aju_tgl") {
						let tgl = dt[1].split("-");
						$("#" + dt[0]).val(tgl[2] + "/" + tgl[1] + "/" + tgl[0]);
					} else {
						$("#" + dt[0]).val(dt[1]);
					}
					if (dt[0] == "aju_detailpengajuan") {
						$("#ajud").val(dt[1]);
						setTimeout(function() {
							getDetailPengajuan();
						}, 500);
					}
					if (dt[0] == "aju_jenis_dana") {
						$("#jenis_dana").val(dt[1]);
					}
				});

				$(".inputan").attr("disabled", false);
				$("#modal_pengajuan").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	function proses(id) {
		$.post("proses_pengajuan", {
			id: id
		}, function(d) {
			$("#list_pengajuan").html(d);
			$("#modal_proses").modal({
				show: true,
				keyboard: false,
				backdrop: 'static'
			});
		});
	}

	function prosessemua() {
		$.post("proses_semua", {}, function(d) {
			$("#list_pengajuan").html(d);
			$("#modal_proses").modal({
				show: true,
				keyboard: false,
				backdrop: 'static'
			});
		});
	}

	// Tombol Batal Detail Pengajuan
	function batalDetailPengajuan() {
		event.preventDefault();
		$("#input_detailpengajuan").slideUp(100);
		$("#ajud_jml").val('');
	}

	// Tombol Hapus Detail Pengajuan
	function hapusDetailPengajuan(hapus) {
		event.preventDefault();

		var data = hapus.split("|") // Explode hapus

		var vg = $("#ajud").val();
		var tp = $("#aju_detailpengajuan").val();
		var newList = vg.replace(";" + hapus, "");
		var ids = hapus.split(".");
		var newLists = tp.replace(";" + hapus, "");
		$("#ajud").val(newList);
		$("#aju_detailpengajuan").val(newLists);
		$("#jenis_dana").val(newLists);
		$("#aju_sifat_kebutuhan").val(newLists);

		// Show Detail Anggaran
		$("#tbl_agr" + data[2]).show();
		getDetailPengajuan();
	}

	// Aksi Tombol Tambah Detail Pengajuan
	function inputDetailPengajuan() {
		event.preventDefault();
		$("#input_detailpengajuan").slideDown(100);
	}

	// Aksi Tombol Tambah Detail Pengajuan
	function tambahDetailPengajuan() {
		event.preventDefault();
		var detailpengajuan = $("#aju_detailpengajuan").val();
		var aj = $("#ajud").val();
		var ajud_penggunaan_dana = $("#ajud_penggunaan_dana").val();
		var ajud_jml = $("#ajud_jml").val();
		aj += ";" + ajud_penggunaan_dana + "|" + ajud_jml + "|" + "null";
		detailpengajuan = aj;
		$("#ajud").val(aj);
		$("#aju_detailpengajuan").val(detailpengajuan);
		getDetailPengajuan();
		$("#ajud_penggunaan_dana").val('');
		$("#ajud_jml").val('');
	}

	function getDetailPengajuan() {
		var detailpengajuan = $("#ajud").val();
		// $.get('view_detailpengajuan/'+detailpengajuan, {}, function(d) {
		// $("#view_detailpengajuan").html(d);
		// });
		$.ajax({
			type: "POST",
			url: "view_detailpengajuan",
			data: 'ajd=' + detailpengajuan,
			success: function(d) {
				$("#view_detailpengajuan").html(d);
			},
			error: function(jqXHR, textStatus, errorThrown) {
				alert('Error get data from ajax');
			}
		});
	}
	// Reset Form
	function reset_form() {
		$("#aju_id").val(0);
		$("#frm_pengajuan")[0].reset();
		$("#ajud_penggunaan_dana").val('');
		$("#ajud_jml").val('');
		$("#input_detailpengajuan").slideUp(100);
	}

	$("#ajud_jml").keypress(function(e) {
		if (e.keycode == 13) {
			e.preventDefault();
			tambahDetailPengajuan();
		}
	});

	// Form Untuk Menghapus pengajuan
	$("#yaKonfirm").click(function() {
		var id = $("#aju_id").val();

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

	$('.range').daterangepicker({
		locale: {
			format: 'DD/MM/YYYY'
		},
		showDropdowns: true,
		"autoApply": true,
		opens: 'left'
	}).on('apply.daterangepicker', function(ev, picker) {
		setTimeout(function() {

			var periode = $("#filter_tanggal").val().split(" - ");
			var tgls1 = periode[0].split("/");
			tgl1 = tgls1[2] + "-" + tgls1[1] + "-" + tgls1[0];
			var tgls2 = periode[1].split("/");
			tgl2 = tgls2[2] + "-" + tgls2[1] + "-" + tgls2[0];
			drawTable();
		}, 100);
	});

	$(document).ready(function() {
		drawTable();
		getDetailPengajuan();
	});
</script>