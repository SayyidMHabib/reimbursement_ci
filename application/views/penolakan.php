<!-- <div class="card-body"> -->
<center>Form Penolakan untuk pengajuan <b><?= $data->ajud_penggunaan; ?></b> sejumlah <b>Rp <?= number_format($data->ajud_jml, 0, ",", "."); ?>,-</b></center>
<form id="frm_tolak">
	<input type="hidden" name="ajud_id" value="<?= $data->ajud_id; ?>">
	<input type="hidden" name="ajud_tolak" value="1">
	<input type="hidden" name="ajud_tgl_tolak" value="<?= date("Y-m-d H:i:s"); ?>">
	<input type="hidden" name="ajud_user_tolak" value="<?= $this->session->userdata("username"); ?>">
	<div class="row">
		<div class="col-12 p-3 mt-3 mb-3"><textarea id="ajud_alasan_tolak" name="ajud_alasan_tolak" rows="3" class="form-control" placeholder="Alasan penolakan" required></textarea></div>
		<div class="col-12 text-right">
			<button type="button" onClick="kembali(<?= $data->ajud_aju_id; ?>)" class="btn btn-default mr-3">Kembali</button>
			<input type="submit" id="btn_tolak" value="Tolak" class="btn btn-danger">
		</div>
	</div>
</form>

<script>
	function kembali(id) {
		$.post("<?= base_url("Pengajuan/proses_pengajuan"); ?>", {
			id: id
		}, function(d) {
			$("#list_pengajuan").html(d);
		});
	}


	$("#frm_tolak").submit(function(e) {
		e.preventDefault();
		let konf = confirm("Proses ini tidak bisa dibatalkan, Yakin ingin menolak pengajuan ini ?");
		if (konf) {
			$(".btn").attr("disabled", true);
			$.ajax({
				type: "POST",
				url: "<?= base_url("Pengajuan/simpan_penolakan"); ?>",
				data: new FormData(this),
				processData: false,
				contentType: false,
				success: function(d) {
					var msg = "";
					if (d == 1) {
						toastr.success("Penolakan berhasil disimpan");
						kembali(<?= $data->ajud_aju_id; ?>);
					} else {
						toastr.error("Penolakan gagal disimpan");
					}
					$(".btn").attr("disabled", false);
				},
				error: function(jqXHR, namaStatus, errorThrown) {
					$(".btn").attr("disabled", false);
					alert('Error get data from ajax');
				}
			});
		}
	});
</script>