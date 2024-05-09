<div class="card-body table-responsive">
	<!-- <?php if ($this->session->userdata("level") == 4) { ?>
		<a href="<?= base_url("Pengajuan/cetak_form/{$id}"); ?>" class="btn btn-info" target="_blank"><i class="fas fa-print"></i> Cetak Form Pengajuan</a>
	<?php } ?> -->
	<!-- Table Modal Pengajuan -->
	<table class="table table-striped table-bordered table-hover" id="tabel-divisi" width="100%" style="font-size:120%;">
		<thead>
			<tr>
				<th align="center">No</th>
				<th align="center">Penggunaan</th>
				<th align="center">Catatan</th>
				<th align="center">Jumlah</th>
				<th align="center">Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$no = 0;
			foreach ($detail as $dtl) {
				$no++;

				$catatan = "";
				$dcatatan = $this->progress->get_catatan($dtl->ajud_id);
				$tambah = "<center><a href='javascript:catatan(0,{$dtl->ajud_id})' ><i class='fas fa-plus-circle'></i></a></center>";

				if ($this->session->userdata("level") == 2 and $dtl->ajud_cair_finance) {
					$tambah = "";
				}
				if ($this->session->userdata("level") == 2 and $dtl->ajud_cair_finance) {
					$tambah = "";
				}
				if ($this->session->userdata("level") == 3 and $dtl->ajud_cair_finance or $dtl->ajud_tolak) {
					$tambah = "";
				}

				// Catatan Pengajuan
				if ($dcatatan) {
					foreach ($dcatatan as $dct) {
						// Edit Catatan
						$edit = "";
						if ($dct->ctt_level == $this->session->userdata("level") and $tambah) {
							$tambah = "";
							$edit = "<a href='javascript:catatan({$dct->ctt_id},{$dtl->ajud_id})'><i class='fas fa-edit text-white ml-2'></i></a>";
						}
						// Badge Catatan
						$catatan .= "<div class='bg-primary p-2 mb-1' style='border-radius:5px;width:200px;font-size:14px;'><b>{$dct->ctt_user}</b><br>{$dct->ctt_isi}{$edit}</div>";
					}
				}



				$catatan .=  $tambah;
			?>
				<tr>
					<td><?= $no; ?></td>
					<td><?= $dtl->ajud_penggunaan; ?></td>
					<td><?= $catatan; ?></td>
					<td align="right">Rp.<?= number_format($dtl->ajud_jml, 0); ?></td>
					<?php
					// Status Pengajuan
					$statusnya =  "<td align='center'><span class='badge bg-dark'>Menunggu diproses <br> direktur</span></td>";
					// Status Pengajuan di setujui Direktur
					if ($dtl->ajud_approve_direktur) $statusnya = "<td align='center'><span class='badge bg-warning'>Menunggu diproses <br> finance</span></td>";
					// Status Pengajuan sudah dicair oleh Finance
					if ($dtl->ajud_cair_finance) $statusnya = "<td align='center'><span class='badge bg-success'>Pengajuan sudah <br> dicairkan dari finance</span></td>";
					// Status jika pengajuan di tolak
					if ($dtl->ajud_tolak) $statusnya = "<td align='center'><span class='badge bg-danger'>Ditolak oleh <br> {$dtl->ajud_user_tolak}</span></td>";
					echo $statusnya;
					?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<script>
	function tolak(id) {
		$.post("tolak_pengajuan", { // Function di controller Pengajuan
			id: id
		}, function(data) {
			$("#list_pengajuan").html(data);
		});
	}

	function catatan(id, ajud_id) {
		$.post("catatan_pengajuan", { // Function di controller Pengajuan
			id: id,
			ajud_id: ajud_id
		}, function(data) {
			$("#list_pengajuan").html(data);
			$("#detailnya").html(data);
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
			});
		} else {
			ele.checked = false;
		}
	}

	// Direktur
	function prosesdirektur(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesdirektur", {
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
		});
	}

	// Direktur
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
		});
	}

	// Finance
	function prosesfinance(ele, id) {
		let status = 0;
		if (ele.checked) status = 1;
		$.post("prosesfinance", {
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
		});
	}

	// Input cek pencairan Finance
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

	// Pencairan bendahar yayasan
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
		});
	}
	$(document).ready(function() {

	});
</script>