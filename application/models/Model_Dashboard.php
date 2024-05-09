<?php
class Model_Dashboard extends CI_Model
{
	public function get_pengajuan()
	{
		$this->db->select("sum(ajud_jml) as jumlah");
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "aju_id = ajud_aju_id", "left");
		if ($this->session->userdata("level") == 3) $this->db->where("aju_user", $this->session->userdata("username"));

		$query = $this->db->get();

		return $query->row()->jumlah;
	}

	public function get_approve_direktur()
	{
		$this->db->select("sum(ajud_jml) as jumlah");
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "aju_id = ajud_aju_id", "left");
		$this->db->where("ajud_approve_direktur", 1);
		if ($this->session->userdata("level") == 3) $this->db->where("aju_user", $this->session->userdata("username"));

		$query = $this->db->get();

		return $query->row()->jumlah;
	}

	public function get_cair_finance()
	{
		$this->db->select("sum(ajud_jml) as jumlah");
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "aju_id = ajud_aju_id", "left");
		$this->db->where("ajud_cair_finance", 1);
		if ($this->session->userdata("level") == 3) $this->db->where("aju_user", $this->session->userdata("username"));

		$query = $this->db->get();

		return $query->row()->jumlah;
	}

	// uang idr
	public function bulat($nilai, $koma)
	{
		$sat = "";
		$nb = $nilai;
		if ($nilai > 1000000000) {
			$sat = "M";
			$nb = $nilai / 1000000000;
		} else {
			if ($nilai > 1000000) {
				$sat = "JT";
				$nb = $nilai / 1000000;
			} else {
				if ($nilai > 1000) {
					$sat = "RB";
					$nb = $nilai / 1000;
				}
			}
		}
		$bulat = number_format($nb, $koma, ",", ".") . " " . $sat;
		return $bulat;
	}

	// uang idr
	public function uang($nilai, $koma)
	{
		$uangnya = number_format($nilai, $koma);
		if ($koma > 0) {
			$pisah = explode(".", $uangnya);
			$depan = str_replace(",", ".", $pisah[0]);
			$belakang = str_replace(".", ",", $pisah[1]);
			$uang = "$depan,$belakang";
		} else {
			$uang = str_replace(",", ".", $uangnya);
		}
		return $uang;
	}

	public function getlastquery()
	{
		$query = str_replace(array("\r", "\n", "\t"), '', trim($this->db->last_query()));

		return $query;
	}
}
