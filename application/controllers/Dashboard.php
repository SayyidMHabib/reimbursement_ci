<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		$this->load->model('Model_Dashboard', 'dashboard');
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
	public function index()
	{
		redirect(base_url("Dashboard/tampil"));
	}
	public function tampil()
	{
		$userr = $this->session->userdata('log_nama');
		$ba = [
			'judul' => "Dashboard",
			'subjudul' => "",
		];

		$pengajuan = $this->dashboard->get_pengajuan();
		$approve_direktur = $this->dashboard->get_approve_direktur();
		$cair_finance = $this->dashboard->get_cair_finance();
		$d = [
			'pengajuan' => $pengajuan,
			'approve_direktur' => $approve_direktur,
			'cair_finance' => $cair_finance,
		];

		$this->load->view('background_atas', $ba);
		$this->load->view('dashboard', $d);
		$this->load->view('background_bawah');
	}



	private function random_color_part()
	{
		return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
	}

	private function random_color()
	{
		return "#" . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
}
