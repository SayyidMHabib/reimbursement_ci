<div class="inner">
	<div class="row">
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<input type="text" class="form-control range" id="filter_tanggal">
			</div>
		</div>
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<select id="fil_progress" onChange="drawTable()" class="form-control">
					<option value=0 selected>Semua progress</option>
					<option value=1>Menunggu Persetujuan Direktur</option>
					<option value=2>Menunggu Pencairan Finance</option>
					<option value=3>Pengajuan Di Tolak</option>
					<option value=4>Selesai</option>
				</select>
			</div>
		</div>
		<div class="col-md-2 col-xs-12">
			<div class="form-group">
				<a href="javascript:refresh()" class="btn btn-success btn-block"><i class="fa fa-sync-alt"></i> &nbsp;&nbsp;&nbsp; Refresh</a>
			</div>
		</div>
	</div>
	<div class="row" id="isidata">
		<div class="col-lg-12">
			<div class="card">
				<div class="card-header">
					<?php
					$ins = "";
					if ($this->session->userdata("ins_id") != 0 and $this->session->userdata("ins_id") != null) {
						$ins = $instansi->ins_nama;
					} ?>
					Progress Pengajuan <b><?= $ins; ?></b>
				</div>
				<div class="card-body table-responsive">
					<table class="table table-striped table-bordered table-hover" id="tabel-progress" width="100%" style="font-size:120%;">
						<thead>
							<tr>
								<th width='0%'>No</th>
								<th width='0%'>Tanggal</th>
								<th width='0%'>Sifat <br> Kebutuhan</th>
								<th width='0%'>Item</th>
								<th width='0%'>Jumlah</th>
								<th width='0%'>Dicairkan</th>
								<th width='0%'>Diajukan Oleh</th>
								<?php if ($this->session->userdata("level") != 3) { ?>
									<th width='0%'>Persetujuan <br> Pengajuan</th>
								<?php } ?>
								<th width='0%'>Status</th>
								<th width='0%'>Kode <br> Unik</th>
								<th width='0%'>Catatan</th>
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

<div class="modal fade" id="modal_catatan" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Form Catatan</h3>
			</div>
			<form role="form" name="catatan" id="frm_catatan">
				<div class="modal-body form">
					<input type="hidden" name="ctt_id" id="ctt_id" value="0">
					<input type="hidden" name="ctt_ajud_id" id="ctt_ajud_id" value="0">

					<div class="row">
						<div class="col-lg-12">
							<div class="form-group">
								<label>Isi Catatan</label>
								<textarea class="form-control" name="ctt_isi" id="ctt_isi" rows="4"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="ctt_simpan" class="btn btn-success">Simpan</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="modal_proses" role="dialog">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title"><i class="glyphicon glyphicon-info"></i> Detail progress</h3>
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

<!-- tiny -->
<script src="<?= base_url(); ?>assets/tinymce/js/tinymce/tinymce.min.js"></script>

<script>
	tinymce.init({
		selector: 'textarea',
		plugins: 'lists, link, image, media',
		toolbar: 'h1 h2 alignleft aligncenter alignright alignjustify bold italic strikethrough blockquote bullist numlist backcolor | link image media | removeformat help',
		menubar: false,
		setup: (editor) => {
			// Apply the focus effect
			editor.on("init", () => {
				editor.getContainer().style.transition = "border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out";
			});
			editor.on("focus", () => {
				(editor.getContainer().style.boxShadow = "0 0 0 .2rem rgba(0, 123, 255, .25)"),
				(editor.getContainer().style.borderColor = "#80bdff");
			});
			editor.on("blur", () => {
				(editor.getContainer().style.boxShadow = ""),
				(editor.getContainer().style.borderColor = "");
			});
		},
	});
</script>

<script>
	var save_method; //for save method string
	var table;
	var tgl1 = null;
	var tgl2 = null;

	var fil_divisi_id = null;
	var fil_instansi_id = null;
	var prg2 = $("#fil_progress").val();

	function tolak(id) {
		$.post("tolak_pengajuan", {
			id: id
		}, function(data) {
			$("#list_pengajuan").html(data);
			$("#modal_proses").modal({
				show: true,
				keyboard: false,
				backdrop: 'static'
			});
		});
	}

	function prosescairtim(ele, id) {
		let t = confirm("Pastikan dana sudah Antum terima, karena aksi ini tidak bisa dibatalkan. Yakin sudah terima Dana ?");
		if (t) {
			let status = 0;
			if (ele.checked) status = 1;
			$.post("prosescairtim", {
				id: id,
				status: status
			}, function(d) {
				if (d) {
					if (status == 1) {
						toastr.success("Berhasil diajukan");
					} else {
						toastr.success("Pengajuan dibatalkan");
					}
				} else {
					toastr.error("Gagal diajukan");
				}
				drawTable();
			});
		} else {
			ele.checked = false;
		}
	}

	function prosesterima(ele, id) {
		event.preventDefault();
		let t = confirm("Pastikan dana sudah Antum terima, karena aksi ini tidak bisa dibatalkan. Yakin sudah terima Dana ?");
		if (t) {
			let status = 0;
			if (ele.checked) status = 1;
			$.post("prosesterima", {
				id: id,
				status: status
			}, function(d) {
				if (d) {
					if (status == 1) {
						toastr.success("Berhasil diajukan");
					} else {
						toastr.success("Pengajuan dibatalkan");
					}
				} else {
					toastr.error("Gagal diajukan");
				}
				drawTable();
			});
		} else {
			ele.checked = false;
		}
	}

	function prosesben(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesben", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Berhasil diajukan");
				} else {
					toastr.success("Pengajuan dibatalkan");
				}
			} else {
				toastr.error("Gagal diajukan");
			}
			drawTable();
		});
	}

	function prosesmgr(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesmgr", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Persetujuan diberikan");
				} else {
					toastr.success("Persetujuan dibatalkan");
				}
			} else {
				toastr.error("Gagal memberikan persetujuan");
			}
			drawTable();
		});
	}

	function prosesDirektur(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesDirektur", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Persetujuan diberikan");
				} else {
					toastr.success("Persetujuan dibatalkan");
				}
			} else {
				toastr.error("Gagal memberikan persetujuan");
			}
			drawTable();
		});
	}

	function prosesbeny(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesbeny", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Berhasil diajukan");
				} else {
					toastr.success("Pengajuan dibatalkan");
				}
			} else {
				toastr.error("Gagal diajukan");
			}
			drawTable();
		});
	}

	function prosesyys(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesyys", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Persetujuan diberikan");
				} else {
					toastr.success("Persetujuan dibatalkan");
				}
			} else {
				toastr.error("Gagal memberikan persetujuan");
			}
			drawTable();
		});
	}

	function cek_cair_finance(ele) {
		let id = ele.getAttribute("idnya");
		if (ele.checked) {
			$("#jml_pencairan" + id).attr("disabled", false);
			$("#jml_pencairan" + id).focus();
		} else {
			$("#jml_pencairan" + id).attr("disabled", true);
			$("#jml_pencairan" + id).val("");
		}
	}

	function cairfinance(id) {
		let status = 0;
		let ele = document.getElementById("ceklis_cair_finance" + id);
		let dicairkan = $("#jml_pencairan" + id).val();
		if (ele.checked) status = 1;
		$.post("cairfinance", {
			id: id,
			status: status,
			cair: dicairkan
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Pencairan berhasil");
				} else {
					toastr.success("Pencairan dibatalkan");
				}
			} else {
				toastr.error("Gagal merubah status");
			}
			drawTable();
		});
	}

	function cairben(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("cairben", {
			id: id,
			status: status
		}, function(d) {
			if (d) {
				if (status == 1) {
					toastr.success("Pencairan berhasil");
				} else {
					toastr.success("Pencairan dibatalkan");
				}
			} else {
				toastr.error("Gagal merubah status");
			}
			drawTable();
		});
	}


	function drawTable() {
		var prg = $("#fil_progress").val();
		$('#tabel-progress').DataTable({
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
				"url": "ajax_list_progress/" + prg + "/" + tgl1 + "/" + tgl2 + "/" + fil_instansi_id + "/" + fil_divisi_id,
				"type": "POST"
			},
			//Set column definition initialisation properties.
			"columnDefs": [{
					"targets": [-1], //last column
					"orderable": false, //set not orderable
				},
				{
					"targets": [5], //last column
					"className": "text-right", //set not orderable
				},
			],
			// biar tetap ignationpage
			"bStateSave": true,
			"fnStateSave": function(oSettings, oData) {
				localStorage.setItem('DataTables', JSON.stringify(oData));
			},
			"fnStateLoad": function(oSettings) {
				return JSON.parse(localStorage.getItem('DataTables'));
			},
			// end
			"initComplete": function(settings, json) {
				$("#process").html("<i class='glyphicon glyphicon-search'></i> Process")
				$(".btn").attr("disabled", false);
				$("#isidata").fadeIn();
			}
		});
	}

	// Refresh
	function refresh() {
		tgl1 = null;
		tgl2 = null;
		fil_divisi_id = null;
		fil_instansi_id = null;
		prg2 = 0;

		$("#fil_divisi option").prop('selected', function() {
			return this.defaultSelected;
		})

		$("#fil_progress option").prop('selected', function() {
			return this.defaultSelected;
		})

		$("#fil_instansi option").prop('selected', function() {
			return this.defaultSelected;
		})
		drawTable();
	}

	// Filter  Divisi
	function filDiv() {
		let divisi = document.getElementById("fil_divisi").value;
		fil_divisi_id = divisi
		console.log(fil_divisi_id);
		drawTable();
	}

	// Filter  Instansi
	function filIns() {
		let instansi = document.getElementById("fil_instansi").value;
		fil_instansi_id = instansi
		console.log(fil_instansi_id);
		drawTable();
	}

	function tambah() {
		$("#aju_id").val(0);
		$("#ajud").val("");
		$("#aju_detailprogress").val("");
		$("frm_progress").trigger("reset");

		$('#modal_progress').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	$("#frm_catatan").submit(function(e) {
		e.preventDefault();
		tinymce.triggerSave();
		$("#ctt_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "<?= base_url("Progress/"); ?>simpan_catatan",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var res = JSON.parse(d);
				if (res.status == 1) {
					toastr.success(res.desc);
					drawTable();
					$("#frm_catatan").trigger("reset");
					$("#modal_catatan").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#ctt_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#ctt_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});

	});

	// Form Upload File
	$("#frm_file").submit(function(e) {
		e.preventDefault();
		$("#file_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "<?= base_url("Progress/"); ?>upload",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var res = JSON.parse(d);
				if (res.status == 1) {
					toastr.success(res.desc);
					drawTable();
					$("#frm_file").trigger("reset");
					$("#modal_file").modal("hide");
				} else {
					toastr.error(res.desc);
				}
				$("#file_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
			},
			error: function(jqXHR, namaStatus, errorThrown) {
				$("#file_simpan").html("Simpan");
				$(".btn").attr("disabled", false);
				alert('Error get data from ajax');
			}
		});

	});
	// Form Approve
	$("#frm_approve").submit(function(e) {
		e.preventDefault();
		$("#appr_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "<?= base_url("Pengajuan/"); ?>approve",
			data: new FormData(this),
			processData: false,
			contentType: false,
			success: function(d) {
				var msg = "";
				if (d == 1) {
					toastr.success("Berhasil disimpan");
					drawTable();
					reset_form();
					close_approve();
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

	function approve(id) {
		$("#appr_id").val(id);
		$('#modal_approve').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
		$.post("<?= base_url("Pengajuan/"); ?>proses_pengajuan", {
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

	function buka_catatan(ajud_id, id) {
		$("#ctt_ajud_id").val(ajud_id);
		$("#ctt_id").val(id);
		if (id > 0) {
			$.post("cari_catatan", {
				id: id
			}, function(res) {
				tinymce.get("ctt_isi").setContent(res);
				$('#modal_catatan').modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
			});
		} else {
			$('#modal_catatan').modal({
				show: true,
				keyboard: false,
				backdrop: 'static'
			});
		}
	}

	function proses(id) {
		$.post("<?= base_url("Pengajuan/"); ?>proses_pengajuan", {
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

	$("#frm_progress").submit(function(e) {
		e.preventDefault();
		$("#aju_simpan").html("Menyimpan...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "POST",
			url: "<?= base_url("Pengajuan/"); ?>simpan",
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
					$("#modal_progress").modal("hide");
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

	function hapus_file(id) {
		event.preventDefault();
		$("#id").val(id);
		$("#mode").val("file");
		$("#jdlKonfirm").html("Konfirmasi hapus file");
		$("#isiKonfirm").html("Yakin ingin menghapus file ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function hapus_progress(id) {
		event.preventDefault();
		$("#id").val(id);
		$("#mode").val("");
		$("#jdlKonfirm").html("Konfirmasi hapus data");
		$("#isiKonfirm").html("Yakin ingin menghapus data ini ?");
		$("#frmKonfirm").modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
	}

	function lihat_progress(id) {
		$('#modal_proses').modal({
			show: true,
			keyboard: false,
			backdrop: 'static'
		});
		$.post("<?= base_url("Pengajuan/"); ?>proses_pengajuan", {
			id: id
		}, function(d) {
			$("#list_pengajuan").html(d);
		});
	}

	function ubah_progress(id) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			url: "<?= base_url("Pengajuan/"); ?>cari",
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
				});

				$(".inputan").attr("disabled", false);
				$("#modal_progress").modal({
					show: true,
					keyboard: false,
					backdrop: 'static'
				});
				return false;
			}
		});
	}

	function reset_form() {
		$("#aju_id").val(0);
		// $("#frm_progress")[0].reset();
	}

	$("#ajud_jml").keypress(function(e) {
		if (e.keycode == 13) {
			e.preventDefault();
			tambahDetailprogress();
		}
	});

	$("#yaKonfirm").click(function() {
		var id = $("#id").val();
		var mode = $("#mode").val();

		$("#isiKonfirm").html("Sedang menghapus data...");
		$(".btn").attr("disabled", true);
		$.ajax({
			type: "GET",
			url: "<?= base_url("Progress/hapus"); ?>" + mode + "/" + id,
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
	});
</script>