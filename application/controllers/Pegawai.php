<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pegawai extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		$this->load->library('upload');
		$this->load->model('Model_Pegawai', 'pegawai');
		date_default_timezone_set('Asia/Jakarta');
	}

	//keldes	
	public function tampil()
	{

		$this->session->set_userdata("judul", "Data Pegawai");
		$ba = [
			'judul' => "Data Pegawai",
			'subjudul' => "Pegawai",
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pegawai');
		$this->load->view('background_bawah');
	}

	public function ajax_list_pegawai()
	{
		$list = $this->pegawai->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pegawai) {
			$no++;

			$row = array();
			$row[] = "<div class='text-center'>$no</div>";
			$row[] = '<img width="100" src="' . base_url("assets/files/foto/{$pegawai->peg_foto}") . '" alt="">';
			$row[] = $pegawai->peg_nama;
			$row[] = $pegawai->peg_jk == 1 ? "Laki-laki" : "Perempuan";
			$row[] = "<div class='text-center'>" . date("d M Y", strtotime($pegawai->peg_tgl_lahir)) . "</div>";;
			$row[] = "
			<a href='#' onClick='ubah_pegawai(" . $pegawai->peg_id . ")' class='btn btn-info btn-sm' title='Ubah data pegawai' style='margin-bottom: 5px;'><i class='fa fa-edit'></i></a>&nbsp;
			<a href='#' onClick='hapus_pegawai(" . $pegawai->peg_id . ")' class='btn btn-danger btn-sm' title='Hapus data pegawai'><i class='fa fa-trash-alt'></i></a>";

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pegawai->count_all(),
			"recordsFiltered" => $this->pegawai->count_filtered(),
			"data" => $data,
			"query" => $this->pegawai->getlastquery(),
		);
		//output to json format
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('peg_id');
		$data = $this->pegawai->cari_pegawai($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('peg_id');
		$data = $this->input->post();

		$tgl = explode("/", $data['peg_tgl_lahir']);
		$data['peg_tgl_lahir'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";

		$nmfile = "foto_" . time();

		$config['upload_path']          = 'assets/files/foto/';
		$config['allowed_types']        = 'jpg|png|jpeg';
		$config['file_name']						= $nmfile;

		$this->load->library('upload', $config);
		$this->upload->initialize($config);

		if ($_FILES['peg_foto']['name']) {
			if (!$this->upload->do_upload('peg_foto')) {
				$error = array('error' => $this->upload->display_errors());
				$resp['errorFoto'] = $error;
			} else {
				$data['peg_foto'] = $this->upload->data('file_name');
			}
		}

		$error = $this->db->error();
		if (!empty($error)) {
			$err = $error['message'];
		} else {
			$err = "";
		}

		if ($id == 0) {
			$insert = $this->pegawai->simpan("pgj_pegawai", $data);
		} else {
			$insert = $this->pegawai->update("pgj_pegawai", array('peg_id' => $id), $data);
		}

		if ($insert) {
			$resp['status'] = 1;
			$resp['desc'] = "Berhasil menyimpan data";
		} else {
			if ($id == 0) {
				$resp['status'] = 0;
				$resp['desc'] = "Ada kesalahan dalam penyimpanan!";
				$resp['error'] = $err;
			} else {
				$resp['status'] = 1;
				$resp['desc'] = "Berhasil menyimpan data";
			}
		}
		echo json_encode($resp);
	}

	public function hapus($id)
	{
		$delete = $this->pegawai->delete('pgj_pegawai', 'peg_id', $id);
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
