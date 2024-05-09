 <div class="card-body">
 	<center>Form Pencatatan untuk pengajuan <b><?= $pengajuan->ajud_penggunaan; ?></b> sejumlah <b>Rp <?= number_format($pengajuan->ajud_jml, 0, ",", "."); ?>,-</b></center>
 	<form id="frm_tolak">
 		<input type="hidden" name="ctt_id" value="<?= $data ? $data->ctt_id : 0; ?>">
 		<input type="hidden" name="ctt_ajud_id" value="<?= $ajud_id; ?>">
 		<div class="row">
 			<div class="col-12 p-3 mt-3 mb-3"><textarea id="ctt_isi" name="ctt_isi" rows="3" class="form-control" placeholder="Catatan Pengajuan" required><?= $data ? $data->ctt_isi : ""; ?></textarea></div>
 			<div class="col-12 text-right"><button type="button" onClick="kembali(<?= $pengajuan->ajud_aju_id; ?>)" class="btn btn-danger mr-3">Kembali</button><input type="submit" id="btn_catatan" value="Simpan" class="btn btn-success"></div>
 		</div>
 	</form>
 </div>

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
 	function kembali(id) {
 		$.post("<?= base_url("Pengajuan/proses_pengajuan"); ?>", {
 			id: id
 		}, function(d) {
 			$("#list_pengajuan").html(d);
 			$("#detailnya").html(d);
 		});
 	}

 	$("#frm_tolak").submit(function(e) {
 		e.preventDefault();
 		$(".btn").attr("disabled", true);
 		$.ajax({
 			type: "POST",
 			url: "<?= base_url("Progress/simpan_catatan"); ?>",
 			data: new FormData(this),
 			processData: false,
 			contentType: false,
 			success: function(d) {
 				let data = JSON.parse(d);
 				if (data.status == 1) {
 					toastr.success(data.desc);
 					kembali(<?= $pengajuan->ajud_aju_id; ?>);
 				} else {
 					toastr.error(data.desc);
 				}
 				$(".btn").attr("disabled", false);
 			},
 			error: function(jqXHR, namaStatus, errorThrown) {
 				$(".btn").attr("disabled", false);
 				alert('Error get data from ajax');
 			}
 		});
 	});
 </script>