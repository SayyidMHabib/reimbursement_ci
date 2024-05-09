<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengguna extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		$this->load->library('upload');
		$this->load->model('Model_Login', 'pengguna');
		$this->load->model('Model_Pegawai', 'pegawai');
		date_default_timezone_set('Asia/Jakarta');
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	//Pengguna	
	public function tampil()
	{
		$this->session->set_userdata("judul", "Data Pengguna");
		$ba = [
			'judul' => "Data Pengguna",
			'subjudul' => "Pengguna",
		];
		$d = [
			'pegawai' => $this->pegawai->get_pegawai(),
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('pengguna', $d);
		$this->load->view('background_bawah');
	}

	public function ajax_list_pengguna()
	{
		$list = $this->pengguna->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $pengguna) {
			$no++;
			$level = "";
			switch ($pengguna->log_level) {
				case 0:
					$level = "Administrator";
					break;
				case 1:
					$level = "Direktur";
					break;
				case 2:
					$level = "Finance";
					break;
				case 3:
					$level = "Staff";
					break;
			}

			$row = array();
			$row[] = $no;
			$row[] = $pengguna->log_user;
			$row[] = $pengguna->peg_nama;
			$row[] = $level;
			$row[] = "
			<a href='#' onClick='ubah_pengguna(" . $pengguna->log_id . ")' class='btn btn-info' title='Ubah data Pengguna'><i class='fa fa-edit'></i></a>&nbsp;&nbsp;&nbsp;&nbsp; 
			<a href='#' onClick='hapus_pengguna(" . $pengguna->log_id . ")' class='btn btn-danger' title='Hapus data Pengguna'><i class='fa fa-trash-alt'></i></a>";
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->pengguna->count_all(),
			"recordsFiltered" => $this->pengguna->count_filtered(),
			"data" => $data,
			"query" => $this->pengguna->getlastquery(),
		);
		//output to json format
		echo json_encode($output);
	}

	public function cari()
	{
		$id = $this->input->post('log_id');
		$data = $this->pengguna->cari_pengguna($id);
		echo json_encode($data);
	}

	public function simpan()
	{
		$id = $this->input->post('log_id');
		$idpeg = $this->input->post('log_peg_id');
		$pass = $this->input->post('log_pass');
		$level = $this->input->post('log_level');
		$data = $this->input->post();

		$pegawai = $this->pegawai->cari_pegawai($idpeg);
		$data['log_nama'] = $pegawai->peg_nama;

		if (!empty($pass)) {
			$data['log_pass'] = md5($pass);
		}

		if ($id == 0) {
			// Cek Password exist
			if (empty($pass)) {
				$data['log_pass'] = md5("user123");
			}
			$insert = $this->pengguna->simpan("pgj_login", $data);
		} else {
			if (!$pass) unset($data['log_pass']);
			$insert = $this->pengguna->update("pgj_login", array('log_id' => $id), $data);
		}
		$error = $this->db->error();
		if (!empty($error)) {
			$err = $error['message'];
		} else {
			$err = "";
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
		$delete = $this->pengguna->delete('pgj_login', 'log_id', $id);
		if ($delete) {
			$resp['status'] = 1;
			$resp['desc'] = "<i class='fa fa-exclamation-circle text-success'></i>&nbsp;&nbsp;&nbsp; Berhasil menghapus data";
		} else {
			$resp['status'] = 0;
			$resp['desc'] = "<i class='fa fa-exclamation-triangle text-warning'></i>&nbsp;&nbsp;&nbsp; Administrator tidak dapat dihapus";
		}
		echo json_encode($resp);
	}

	public function getusersbylevel()
	{
		$id = $this->input->post('id');
		$pengguna = $this->pengguna->cari_level_pengguna($id);
		if (isset($pengguna)) {
			foreach ($pengguna as $pgn) {
				echo '<option value="' . $pgn->peg_id . '">' . $pgn->peg_nama . '</option>';
			}
		}
		// else {
		// 	echo '<option value="">Belum Ada Data</option>';
		// }
	}
}
