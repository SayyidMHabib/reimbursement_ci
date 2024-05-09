<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Progress extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		if (!isset($this->session->userdata['id_user'])) {
			redirect(base_url("login"));
		}
		// if ($this->session->userdata('level') == 1) {
		// redirect(base_url("Dashboard"));
		// }
		$this->load->library('upload');
		$this->load->model('Model_Progress', 'progress');
		$this->load->model('Model_Pengajuan', 'pengajuan');
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

	//progress	
	public function tampil()
	{

		$this->session->set_userdata("judul", "Progress Pengajuan");
		$ba = [
			'judul' => "Data Proses",
			'subjudul' => "Progress Pengajuan",
		];
		$this->load->helper('url');
		$this->load->view('background_atas', $ba);
		$this->load->view('progress');
		$this->load->view('background_bawah');
	}

	public function ajax_list_progress($prg, $tgl1, $tgl2)
	{
		$list = $this->progress->get_datatables($prg, $tgl1, $tgl2);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $progress) {
			$no++;
			$row = array();

			// Status
			$statusnya =  "<span class='badge bg-dark' style='border-radius:5px;width:100px;font-size:12px;'>Menunggu <br> diproses <br> direktur</span>";

			// Status progress pengajuan direktur
			if ($progress->ajud_approve_direktur) $statusnya = "<span class='badge bg-warning' style='border-radius:5px;width:100px;font-size:12px;'>Menunggu <br> diproses <br>  finance</span>";

			// Status progress pengajuan finance
			if ($progress->ajud_cair_finance) $statusnya = "<span class='badge bg-success' style='border-radius:5px;width:100px;font-size:12px;'>Pengajuan <br> sudah dicairkan</span>";

			// Status pengajuan ditolak
			if ($progress->ajud_tolak) $statusnya = "<td align='center'><span class='badge bg-danger' style='border-radius:5px;width:100px;font-size:12px;'>Ditolak oleh <br> {$progress->ajud_user_tolak}<br>\"" . wordwrap($progress->ajud_alasan_tolak, 15, '<br>') . "\"</span></td>";


			$catatan = "";
			$dcatatan = $this->progress->get_catatan($progress->ajud_id);
			$tambah = "<center><a href='javascript:void()' onClick='buka_catatan({$progress->ajud_id},0)'><i class='fas fa-plus-circle'></i></a></center>";

			if ($this->session->userdata("level") == 3 and $progress->ajud_cair_finance or $progress->ajud_tolak) {
				$tambah = "";
			}

			// Aksi Catatan
			if ($dcatatan) {
				foreach ($dcatatan as $dct) {
					$edit = "";
					if ($dct->ctt_level == $this->session->userdata("level") and $tambah) {
						$tambah = "";
						$edit = "<a href='javascript:void()' onClick='buka_catatan({$progress->ajud_id},{$dct->ctt_id})'><i class='fas fa-edit text-white ml-2'></i></a>";
					}

					$catatan .= "<div class='bg-primary p-2 mb-1' style='border-radius:5px;width:100px;font-size:12px;'><b>{$dct->ctt_user}</b><br>{$dct->ctt_isi}{$edit}</div>";
				}
			}

			//Pencairan
			$cair = "";
			// Aksi Direktur
			if ($this->session->userdata('level') == 1) {
				if ($progress->ajud_tolak) {
					$cair = "<span class='badge bg-danger' style='border-radius:5px;width:100px;font-size:12px;'><i class='fa fa-times-circle'></i> " . wordwrap($progress->ajud_tgl_tolak, 10, '<br>') . "<br>" . wordwrap($progress->ajud_alasan_tolak, 10, '<br>') . "</span>";
				} else {
					if ($progress->ajud_approve_direktur) {
						$cair = "<span class='badge bg-success' style='border-radius:5px;width:130px;font-size:12px;'><i class='fa fa-check-circle'></i> $progress->ajud_tgl_approve_direktur</span>";
					} else {
						if ($progress->ajud_approve_direktur) {
							$cek = 'checked';
						} else {
							$cek = "";
						}
						$cair = "<input type='checkbox' {$cek} onChange='prosesDirektur(this,$progress->ajud_id)'> <span class='badge bg-warning' style='font-size:12px;'> Setujui</span>
						<br><button type='button' onClick='tolak($progress->ajud_id)' class='btn btn-danger btn-sm'><i class='fa fa-times-circle'></i></button> <span class='badge bg-danger' style='font-size:12px;'>Tolak</span>";
					}
				}
			}

			// Aksi Finance
			if ($this->session->userdata('level') == 2) {
				if ($progress->ajud_tolak) {
					$cair = "<span class='badge bg-danger' style='border-radius:5px;width:100px;font-size:12px;'><i class='fa fa-times-circle'></i> " . wordwrap($progress->ajud_tgl_tolak, 10, '<br>') . "<br>" . wordwrap($progress->ajud_alasan_tolak, 10, '<br>') . "</span>";
				} else {
					if ($progress->ajud_approve_direktur) {
						if ($progress->ajud_cair_finance) {
							$cairYys = "<span class='badge bg-success' style='border-radius:5px;width:100px;font-size:12px;'><i class='fa fa-check-circle'></i> " . wordwrap($progress->ajud_tgl_cair_finance, 10, '<br>') . " <br> Rp. $progress->ajud_dicairkan </span>";
						} else {
							if ($progress->ajud_cair_finance) {
								$cekBox = 'checked';
								$cekInput = '';
							} else {
								$cekBox = "";
								$cekInput = "disabled";
							}
							$cairYys = "<div class='row'>
							<div class='col-2'><input id='ceklis_cair_finance$progress->ajud_id' idnya='$progress->ajud_id' type='checkbox' {$cekBox} onChange='cek_cair_finance(this)'></div>
							<div class='col-8'><input {$cekInput} class='form-control' type='text' id='jml_pencairan$progress->ajud_id' value='$progress->ajud_dicairkan' placeholder='Jumlah Pencairan'></div>
							<div class='col-2'><button type='button' class='btn btn-sm btn-success' onClick='cairfinance($progress->ajud_id)'><i class='fas fa-save'></i></button></div>
						</div>";
						}
					} else {
						$cairYys = "<span class='badge bg-dark'>Belum <br> disetujui Yayasan</span>";
					}
				}
			}

			$catatan .= $tambah;
			$row[] = $no;
			$row[] = date("d/m/Y", strtotime($progress->aju_tgl));
			$row[] = $progress->aju_sifat_kebutuhan;
			$row[] = "<div class='text-justify'>" . wordwrap($progress->ajud_penggunaan, 10, "<br>\n") . "</div>";
			$row[] = 'Rp.' . number_format($progress->ajud_jml, 0);
			$row[] = 'Rp.' . number_format($progress->ajud_dicairkan, 0);
			$row[] = $progress->aju_user;
			if ($this->session->userdata('level') == 1) {
				$row[] = $cair;
			};
			if ($this->session->userdata('level') == 2) {
				$row[] = $cairYys;
			};
			$row[] = $statusnya;
			$row[] = "<span style='font-size:15px;'>$progress->aju_kode_unik</span>";
			$row[] = $catatan;
			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->progress->count_all(),
			"recordsFiltered" => $this->progress->count_filtered($prg, $tgl1, $tgl2),
			"data" => $data,
			"query" => $this->progress->getlastquery(),
		);
		//output to json format
		echo json_encode($output);
	}

	public function upload()
	{
		$data = $this->input->post();
		$nama = str_replace(' ', '-', str_replace("  ", " ", trim($data['file_deskripsi'])));
		if (!empty($_FILES['file_pengajuan']['name'])) {
			if (!is_dir('assets/files/pengajuan')) {
				mkdir('assets/files/pengajuan', 0777, TRUE);
				mkdir('assets/files/pengajuan/thumbs', 0777, TRUE);
			}
			$path = $_FILES['file_pengajuan']['name'];
			$ext =  pathinfo($path, PATHINFO_EXTENSION);
			$config['upload_path'] = 'assets/files/pengajuan/'; //path folder
			$config['allowed_types'] = '*'; //type yang dapat diakses bisa anda sesuaikan
			$config['encrypt_name'] = FALSE; //Enkripsi nama yang terupload
			$config['overwrite'] = TRUE; //Gantikan file dengan nama yang sama
			$config['file_name'] = "{$nama}." . microtime(true) . "." . $ext; //ganti nama file
			$this->upload->initialize($config);
			if ($this->upload->do_upload('file_pengajuan')) {
				$gbr = $this->upload->data();

				$data['file_link'] = base_url("assets/files/pengajuan/{$config['file_name']}");
				$data['file_name'] = $config['file_name'];
				$data['file_size'] = $gbr['file_size'];
				$data['file_type'] = $gbr['file_type'];
				$data['file_ext'] = $gbr['file_ext'];
				$data['file_level'] = $this->session->userdata("level");
				$data['file_log_id'] = $this->session->userdata("id_user");
				$data['file_user'] = $this->session->userdata("username");
				$insert = $this->progress->simpan("pgj_file", $data);
			} else {
				$insert = 0;
				$resp['desc'] = $this->upload->display_errors();
			}
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
			$resp['status'] = 0;
			$resp['desc'] = "Ada kesalahan dalam penyimpanan!";
			$resp['error'] = $err;
		}
		echo json_encode($resp);
	}

	public function cari_catatan()
	{
		$id = $this->input->post('id');
		$catatan = $this->progress->cari_catatan($id);
		echo $catatan->ctt_isi;
	}

	public function proses_progress()
	{
		$id = $this->input->post("id");
		$detail = $this->progress->get_detail($id);
		$d = [
			'id' => $id,
			'detail' => $detail,
		];

		$this->load->view('listprogress', $d);
	}

	public function proses_semua()
	{
		$detail = $this->progress->get_detail_semua();
		$d = [
			'id' => null,
			'detail' => $detail,
		];

		$this->load->view('listprogress', $d);
	}

	public function tolak_pengajuan()
	{
		$id = $this->input->post("id");
		$data = $this->pengajuan->cari_detail($id);
		$d = [
			"data" => $data,
		];
		$this->load->view("penolakan", $d);
	}

	public function approve()
	{
		$data = $this->input->post();
		$approve = array(
			"aju_kadiv" => $data['approve'],
		);
		if ($data['approve'] == 2) $approve["aju_alasan_ditolak"] = $data['alasan'];
		$update = $this->progress->update("pgj_progress", array("aju_id" => $data['id']), $approve);
		if ($update && $data['approve'] == 1) $this->notif_pengajuan($data['id']);
		echo $update;
	}

	// Direktur
	public function prosesDirektur()
	{
		$id = $this->input->post("id");
		$tgl = null;
		$user = null;
		$status = $this->input->post("status");
		if ($status) {
			$tgl = date("Y-m-d H:i:s");
			$user = $this->session->userdata("username");
		}
		$update = $this->progress->update("pgj_pengajuan_detail", array("ajud_id" => $id), array("ajud_approve_direktur" => $status, "ajud_tgl_approve_direktur" => $tgl, "ajud_user_approve_direktur" => $user));
		echo $update;
	}



	// Bendahara Yayasan
	public function prosesFinance()
	{
		$id = $this->input->post("id");
		$tgl = null;
		$user = null;
		$status = $this->input->post("status");
		if ($status) {
			$tgl = date("Y-m-d H:i:s");
			$user = $this->session->userdata("username");
		}
		$update = $this->progress->update("pgj_pengajuan_detail", array("ajud_id" => $id), array("ajud_proses_finance" => $status, "ajud_tgl_proses_finance" => $tgl, "ajud_user_proses_finance" => $user));
		$detail = $this->pengajuan->cari_detail($id);

		if ($update and $status == 1) $this->notif_pengajuan($detail->aju_id);
		echo $update;
	}

	public function cari()
	{
		$id = $this->input->post('aju_id');
		$data = $this->progress->cari_progress($id);
		$detail = $this->progress->get_detail($id);
		if ($detail) {
			$data->aju_detailprogress = "";
			foreach ($detail as $progress) {
				$data->aju_detailprogress .= ";{$progress->ajud_penggunaan}|{$progress->ajud_jml}";
			}
		}
		echo json_encode($data);
	}

	public function view_detailprogress()
	{
		$ajds = $this->input->post('ajd');
		$d = [
			'list_detailprogress' => $ajds,
		];
		$this->load->helper('url');
		$this->load->view('progressdetail', $d);
	}

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

	public function simpan_catatan()
	{
		$id = $this->input->post('ctt_id');
		$data = $this->input->post();
		$data['ctt_level'] = $this->session->userdata("level");
		$data['ctt_log_id'] = $this->session->userdata("id_user");
		$data['ctt_user'] = $this->session->userdata("username");
		if ($id == 0) {
			$insert = $this->progress->simpan("pgj_catatan", $data);
		} else {
			$insert = $this->progress->update("pgj_catatan", array('ctt_id' => $id), $data);
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
			$resp['status'] = 0;
			$resp['desc'] = "Ada kesalahan dalam penyimpanan!";
			$resp['error'] = $err;
		}
		echo json_encode($resp);
	}

	public function simpan()
	{
		$id = $this->input->post('aju_id');
		$data = $this->input->post();
		$detail = explode(";", $data['aju_detailprogress']);
		unset($data['aju_detailprogress']);
		unset($data['ajud']);
		$tgl = explode("/", $data['aju_tgl']);
		$data['aju_tgl'] = "{$tgl[2]}-{$tgl[1]}-{$tgl[0]}";
		$data['aju_periode'] = date("Y");
		$data['aju_peg_id'] = $this->session->userdata("peg_id");
		$data['aju_user'] = $this->session->userdata("username");
		$data['aju_ins_id'] = $this->session->userdata("ins_id");
		if ($this->session->userdata("level") == 2) $data['aju_div_id'] = $this->session->userdata("div_id");
		if ($this->session->userdata("level") >= 3) {
			$data['aju_kadiv'] = 1;
			$data['aju_div_id'] = $this->session->userdata("div_id");
		}

		if ($id == 0) {
			$insert = $this->progress->simpan("pgj_progress", $data);
		} else {
			$insert = $this->progress->update("pgj_progress", array('aju_id' => $id), $data);
		}
		$error = $this->db->error();
		if (!empty($error)) {
			$err = $error['message'];
		} else {
			$err = "";
		}
		if ($insert) {
			$tgl = date("Ymd");
			$kodeunik = "PGJ{$tgl}{$insert}";
			if ($id == 0) $this->progress->update("pgj_progress", array("aju_id" => $insert), array("aju_kode_unik" => $kodeunik));
			$this->progress->delete("pgj_pengajuan_detail", "ajud_aju_id", $insert);
			for ($i = 1; $i < count($detail); $i++) {
				$ddet = explode("|", $detail[$i]);
				$d = [
					'ajud_aju_id' => $insert,
					'ajud_penggunaan' => $ddet[0],
					'ajud_jml' => $ddet[1],
				];
				if ($this->session->userdata("level") == 4) {
					$d['ajud_proses_finance'] = 1;
					$d['ajud_user_proses_finance'] = $this->session->userdata("username");
					$d['ajud_tgl_proses_finance'] = date("Y-m-d H:i:s");
				}

				if ($this->session->userdata("level") == 5) {
					$d['ajud_approve_direktur'] = 1;
					$d['ajud_user_approve_direktur'] = $this->session->userdata("username");
					$d['ajud_tgl_approve_direktur'] = date("Y-m-d H:i:s");
				}

				if ($this->session->userdata("level") == 6) {
					$d['ajud_proses_finance'] = 1;
					$d['ajud_user_proses_finance'] = $this->session->userdata("username");
					$d['ajud_tgl_proses_finance'] = date("Y-m-d H:i:s");
				}

				if ($this->session->userdata("level") == 7) {
					$d['ajud_approve_direktur'] = 1;
					$d['ajud_user_approve_direktur'] = $this->session->userdata("username");
					$d['ajud_tgl_approve_direktur'] = date("Y-m-d H:i:s");
				}

				$this->progress->simpan("pgj_pengajuan_detail", $d);
			}
			$resp['status'] = 1;
			$resp['desc'] = "Berhasil menyimpan data";
		} else {
			if ($id > 0) {
				$this->progress->delete("pgj_pengajuan_detail", "ajud_aju_id", $id);
				for ($i = 1; $i < count($detail); $i++) {
					$ddet = explode("|", $detail[$i]);
					$d = [
						'ajud_aju_id' => $id,
						'ajud_penggunaan' => $ddet[0],
						'ajud_jml' => $ddet[1],
					];
					$this->progress->simpan("pgj_pengajuan_detail", $d);
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

	public function hapus($id)
	{
		$delete = $this->progress->delete('pgj_progress', 'aju_id', $id);
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
