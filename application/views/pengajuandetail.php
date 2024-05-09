<?php
if (!empty($list_detailpengajuan)) {

	$data = explode(";", $list_detailpengajuan);
	$max = count($data);

	if ($max > 0) {
		for ($i = 1; $i < $max; $i++) { ?>
			<tr>
				<td><?= $i; ?></td>
				<?php
				$list_text = explode("|", $data[$i]);
				?>
				<td><?= $list_text[0]; ?></td>
				<td><?= "Rp." . number_format($list_text[1]); ?></td>
				<td><a href="javascript:void()" onClick="hapusDetailPengajuan('<?= $data[$i]; ?>')" class="btn btn-danger btn-circle"><i class="fa fa-trash" /></a></td>
			</tr>
<?php
		}
	}
}
?>
<tr>
	<td colspan="7" align="center"><a href="#" onClick="inputDetailPengajuan()" class="fa fa-plus btn btn-flat btn-success"> Tambah Detail Pengajuan</a></td>
</tr>