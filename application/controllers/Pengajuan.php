<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		$this->load->library('upload');
		$this->load->model('Model_Progress', 'progress');
		$this->load->model('Model_Pengajuan', 'pengajuan');
		date_default_timezone_set('Asia/Jakarta');
	}

	//pengajuan	
	public function tampil()
	{

		$this->session->set_userdata("judul", "Data Pengajuan");
		$ba = [
			'judul' => "Data Pengajuan",
			'subjudul' => "Pengajuan",
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pengajuan');
		$this->load->view('background_bawah');
	}

	public function ajax_list_pengajuan($tgl1, $tgl2)
	{
		$list = $this->pengajuan->get_datatables($tgl1, $tgl2);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pengajuan) {
			$no++;
			$row = array();

			// Aksi untuk Ubah dan Hapus pengajuan
			$aksi = "<a href='#' onClick='ubah_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-info btn-sm' title='Ubah data pengajuan'><i class='fa fa-edit'></i></a>
			<a href='#' onClick='hapus_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengajuan'><i class='fa fa-trash-alt'></i></a>";

			// Jika sudah disetujui finance untuk dicairkan

			if ($this->session->userdata("level") == 1) {
				if ($pengajuan->cairfinance) $aksi = "<a href='#' onClick='lihat_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-info btn-sm' title='Lihat Detail pengajuan'><i class='fa fa-list-alt'></i></a>";
			}
			if ($this->session->userdata("level") == 3) {
				if ($pengajuan->cairfinance or $pengajuan->tolak or $pengajuan->progresdirektur) $aksi = "<a href='#' onClick='lihat_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-info btn-sm' title='Lihat Detail pengajuan'><i class='fa fa-list-alt'></i></a>";
			}

			// Aksi Untuk Admin
			if ($this->session->userdata("level") == 0) $aksi = "<a href='#' onClick='lihat_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-info btn-sm' title='Lihat Detail pengajuan'><i class='fa fa-list-alt'></i></a>&nbsp;<a href='#' onClick='ubah_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-info btn-sm' title='Ubah data pengajuan'><i class='fa fa-edit'></i></a>&nbsp;<a href='#' onClick='hapus_pengajuan(" . $pengajuan->aju_id . ")' class='btn btn-danger btn-sm' title='Hapus data pengajuan'><i class='fa fa-trash-alt'></i></a>";

			// Icon Progress
			$progress = $pengajuan->selesai == $pengajuan->jml ? "<span class='badge bg-success'>{$pengajuan->selesai}/{$pengajuan->jml}</span>" : "<span class='badge bg-danger'>{$pengajuan->selesai}/{$pengajuan->jml}</span>";

			// Icon Ditolak
			$tolak = "";
			if ($pengajuan->tolak > 0) $tolak = "<i class='fa fa-times-circle text-danger mr-1'></i>";

			$row[] = $no;
			$row[] = date("d/m/Y", strtotime($pengajuan->aju_tgl));
			$row[] = $pengajuan->aju_jenis_dana;
			$row[] = $pengajuan->aju_sifat_kebutuhan;
			$row[] = 'Rp.' . number_format($pengajuan->total_kebutuhan, 0);
			$row[] = 'Rp.' . number_format($pengajuan->total_diproses, 0);
			$row[] = $pengajuan->aju_user;
			$row[] = $tolak . $progress;
			$row[] = $aksi;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pengajuan->count_all(),
			"recordsFiltered" => $this->pengajuan->count_filtered($tgl1, $tgl2),
			"data" => $data,
			"query" => $this->pengajuan->getlastquery(),
		);
		//output to json format
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('aju_id');
		$data = $this->pengajuan->cari_pengajuan($id);
		$detail = $this->pengajuan->get_detail($id);
		if ($detail) {
			$data->aju_detailpengajuan = "";
			foreach ($detail as $dtl) {
				$data->aju_detailpengajuan .= ";{$dtl->ajud_penggunaan}|{$dtl->ajud_jml}";
			}
		}
		echo json_encode($data);
	}

	public function proses_pengajuan()
	{
		$id = $this->input->post("id");
		$detail = $this->pengajuan->get_detail($id);
		$d = [
			'id' => $id,
			'detail' => $detail,
		];

		$this->load->view('listpengajuan', $d);
	}

	public function view_detailpengajuan()
	{
		$ajds = $this->input->post('ajd');
		$d = [
			'list_detailpengajuan' => $ajds,
		];
		$this->load->helper('url');
		$this->load->view('pengajuandetail', $d);
	}

	public function simpan()
	{
		$id = $this->input->post('aju_id');
		$data = $this->input->post();
		$detail = explode(";", $data['aju_detailpengajuan']);
		// Menghapus / melepaskan input post
		unset($data['aju_detailpengajuan']);
		unset($data['ajud']);

		$tgl = explode("/", $data['aju_tgl']);
		$data['aju_tgl'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";
		$data['aju_periode'] = date("Y");
		$data['aju_peg_id'] = $this->session->userdata("peg_id");
		$data['aju_user'] = $this->session->userdata("username");

		if ($id == 0) {
			// Simpan data di pgj_pengajuan
			$insert = $this->pengajuan->simpan("pgj_pengajuan", $data);
		} else {
			$insert = $this->pengajuan->update("pgj_pengajuan", array('aju_id' => $id), $data);
		}

		$error = $this->db->error();
		if (!empty($error)) {
			$err = $error['message'];
		} else {
			$err = "";
		}
		if ($insert) {
			// Simpan data ke pgj_pengajuan_detail
			$tgl = date("Ymd");
			$kodeunik = "PGJ{$tgl}{$insert}";
			if ($id == 0) $this->pengajuan->update("pgj_pengajuan", array("aju_id" => $insert), array("aju_kode_unik" => $kodeunik));
			$this->pengajuan->delete("pgj_pengajuan_detail", "ajud_aju_id", $insert);
			for ($i = 1; $i < count($detail); $i++) {
				$ddet = explode("|", $detail[$i]);

				$d = [
					'ajud_aju_id' => $insert,
					'ajud_penggunaan' => $ddet[0],
					'ajud_jml' => $ddet[1],
				];

				// Direktur
				if ($this->session->userdata("level") == 1) {
					$d['ajud_approve_direktur'] = 1;
					$d['ajud_user_approve_direktur'] = $this->session->userdata("username");
					$d['ajud_tgl_approve_direktur'] = date("Y-m-d H:i:s");
				}

				$this->pengajuan->simpan("pgj_pengajuan_detail", $d);
			}
			$resp['status'] = 1;
			$resp['desc'] = "Berhasil menyimpan data";
		} else {
			if ($id > 0) {
				$this->pengajuan->delete("pgj_pengajuan_detail", "ajud_aju_id", $id);
				for ($i = 1; $i < count($detail); $i++) {
					$ddet = explode("|", $detail[$i]);
					$d = [
						'ajud_aju_id' => $id,
						'ajud_penggunaan' => $ddet[0],
						'ajud_jml' => $ddet[1],
					];
					$this->pengajuan->simpan("pgj_pengajuan_detail", $d);
				}
				$resp['status'] = 1;
				$resp['desc'] = "Berhasil menyimpan data";
			} else {
				$resp['status'] = 0;
				$resp['desc'] = "Ada kesalahan dalam penyimpanan!";
				$resp['error'] = $err;
			}
		}
		echo json_encode($resp);
	}

	// Catatan Pengajaun
	public function catatan_pengajuan()
	{
		$id = $this->input->post("id");
		$ajud_id = $this->input->post("ajud_id");
		$data = $this->progress->cari_catatan($id);
		$pengajuan = $this->pengajuan->cari_detail($ajud_id);
		$d = [
			"ajud_id" => $ajud_id,
			"data" => $data,
			"pengajuan" => $pengajuan,
		];
		$this->load->view("pencatatan", $d);
	}

	public function simpan_penolakan()
	{
		$data = $this->input->post();
		$update = $this->pengajuan->update("pgj_pengajuan_detail", array("ajud_id" => $data['ajud_id']), $data);
		// if ($update and $data['approve'] == 1) $this->notif_pengajuan($data['id']);
		echo $update;
	}

	public function hapus($id)
	{
		$delete = [
			$this->pengajuan->delete('pgj_pengajuan', 'aju_id', $id),
			$this->pengajuan->delete('pgj_pengajuan_detail', 'ajud_aju_id', $id),
		];

		if ($delete) {
			$resp['status'] = 1;
			$resp['desc'] = "<i class='fa fa-exclamation-circle text-success'></i>&nbsp;&nbsp;&nbsp; Berhasil menghapus data";
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "Gagal menghapus data !";
		}
		echo json_encode($resp);
	}
}
