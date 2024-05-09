<?php
class Model_Pengajuan extends CI_Model
{
	var $table = 'pgj_pengajuan';
	var $column_order = array('aju_id', 'aju_tgl', 'aju_jenis_dana', 'aju_sifat_kebutuhan', 'total_kebutuhan', 'total_diproses', 'aju_user', 'status'); //set column field database for datatable orderable
	var $column_search = array('aju_jenis_dana', 'aju_sifat_kebutuhan', 'aju_user', 'aju_kode_unik'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('status' => 'asc', 'aju_tgl' => 'desc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($tgl1, $tgl2)
	{
		$whs = "and (ajud_cair_finance = 1 or ajud_tolak = 1)";

		$this->db->select("pgj_pengajuan.*, 
		(select sum(ajud_jml) from pgj_pengajuan_detail where ajud_aju_id = aju_id) as total_kebutuhan, 
		(select sum(ajud_dicairkan) from pgj_pengajuan_detail where ajud_aju_id = aju_id) as total_diproses, 
		(if (
			(select sum(ajud_jml) from pgj_pengajuan_detail where ajud_aju_id = aju_id) = (select sum(ajud_jml) from pgj_pengajuan_detail where ajud_aju_id = aju_id {$whs})
			, 1, 0
			)
		) as status, 
		(select count(*) from pgj_pengajuan_detail where ajud_aju_id = aju_id) as jml, 
		(select count(*) from pgj_pengajuan_detail where ajud_aju_id = aju_id {$whs}) as selesai, 
		(select count(*) from pgj_pengajuan_detail where ajud_aju_id = aju_id and ajud_approve_direktur = 1) as progresdirektur, 
		(select count(*) from pgj_pengajuan_detail where ajud_aju_id = aju_id and ajud_tolak = 1) as tolak, 
		(select count(*) from pgj_pengajuan_detail where ajud_aju_id = aju_id and (ajud_cair_finance = 1 or ajud_tolak = 1)) as cairfinance");
		$this->db->from($this->table);

		$this->db->where("aju_user", $this->session->userdata("username"));


		// query for bendahara unit
		if ($this->session->userdata("level") == 2) {
			$this->db->where("aju_direktur", 1);
		}

		if ($tgl1 != 'null' and $tgl2 != 'null') {
			$this->db->where("aju_tgl >= '{$tgl1}'");
			$this->db->where("aju_tgl <= '{$tgl2}'");
		}
		$i = 0;

		foreach ($this->column_search as $item) // loop column 
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			foreach ($this->order as $key => $order) {
				$this->db->order_by($key, $order);
			}
		}
	}

	function get_datatables($tgl1, $tgl2)
	{
		$this->_get_datatables_query($tgl1, $tgl2);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($tgl1, $tgl2)
	{
		$this->_get_datatables_query($tgl1, $tgl2);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function get_penerima_pengajuan($level, $ins_id, $div_id)
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_pegawai", "peg_id = log_peg_id", "left");
		$this->db->join("pgj_divisi", "div_id = log_div_id", "left");
		$this->db->join("pgj_instansi", "ins_id = log_ins_id", "left");

		if ($level == 5) {
			$this->db->where("log_level", $level + 3);
		} else if ($level == 8) {
			$this->db->where("log_level", $level - 1);

			// for ($i = 1; $i <= 3; $i++) {
			// if ($i == 1) {
			// $this->db->where("log_level", $level - 1);
			// }
			// }
		} else if ($level == 7) {
			$this->db->where("log_level", $level + 2);
		} else {
			$this->db->where("log_level", $level + 1);
		}

		if ($level < 3) {
			$this->db->where("div_id", $div_id);
			$this->db->where("ins_id", $ins_id);
		}

		if ($level < 5) {
			$this->db->where("ins_id", $ins_id);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function get_penerima_pencairan($level, $ins_id, $div_id, $aju_level)
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_pegawai", "peg_id = log_peg_id", "left");
		$this->db->join("pgj_divisi", "div_id = log_div_id", "left");
		$this->db->join("pgj_instansi", "ins_id = log_ins_id", "left");

		if ($level == 7) {
			$this->db->where("log_level", 6);
			// $this->db->where("ins_id", $ins_id);
		}

		if ($level == 6) {
			$this->db->where("log_level", 4);
			$this->db->where("log_ins_id", $ins_id);
		}

		if ($level == 4) {
			if ($aju_level == 5) {
				$this->db->where("log_level", $aju_level);
			} else {
				$this->db->where("log_level", 3);
				$this->db->where("log_div_id", $div_id);
			}
			$this->db->where("log_ins_id", $ins_id);
		}

		if ($level == 3) {
			$this->db->where("log_level", 2);
			$this->db->where("log_div_id", $div_id);
			$this->db->where("log_ins_id", $ins_id);
		}

		$query = $this->db->get();

		return $query->result();
	}

	public function get_penganggaran($div)
	{
		$ins_id = $this->session->userdata("ins_id");

		$this->db->from('pgj_penganggaran');
		$this->db->where('agr_div_id', $div);
		$this->db->where('agr_ins_id', $ins_id);
		$this->db->where('agr_aju_id', null); // nilai agr_aju_id = null
		$this->db->where('agr_approve_yys', 1);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_instansi($ins_id)
	{
		$this->db->from('pgj_instansi');
		$this->db->where('ins_id', $ins_id);
		$query = $this->db->get();

		return $query->row();
	}


	public function get_pengajuan()
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		$query = $this->db->get();

		return $query->result();
	}

	public function cari_detail($id)
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");
		$this->db->join("pgj_login", "log_user = aju_user", "left");
		$this->db->where("ajud_id", $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function cari_penganggaran($id)
	{
		$this->db->from("pgj_penganggaran");
		$this->db->where("agr_id", $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_detail($id)
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");
		$this->db->join("pgj_login", "log_user = aju_user", "left");
		$this->db->where("ajud_aju_id", $id);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_detail_semua()
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		$this->db->where("ajud_cair", 0);
		$this->db->where("aju_direktur", 1);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_detail_setuju($id = null)
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");
		$this->db->join("pgj_pegawai", "aju_peg_id = peg_id", "left");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		if ($id) $this->db->where("ajud_aju_id", $id);
		$this->db->where("ajud_status", 1);
		$this->db->where("ajud_proses_ben", 0);
		$this->db->where("ajud_cair_ben", 0);
		$this->db->where("aju_direktur", 1);
		$query = $this->db->get();

		return $query->result();
	}

	public function cari_pengajuan($id)
	{
		$this->db->select("pgj_pengajuan.*,pgj_pengajuan_detail.*,pgj_pegawai.*,
		(select sum(ajud_jml) from pgj_pengajuan_detail where ajud_aju_id = aju_id) as jml,
		(select sum(ajud_dicairkan) from pgj_pengajuan_detail where ajud_aju_id = aju_id) as total_diproses");
		$this->db->from("pgj_pengajuan");
		$this->db->join("pgj_pegawai", "aju_peg_id = peg_id", "left");
		$this->db->join("pgj_pengajuan_detail", "ajud_aju_id = aju_id", "left");
		$this->db->where('aju_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function getlastquery()
	{
		$query = str_replace(array("\r", "\n", "\t"), '', trim($this->db->last_query()));

		return $query;
	}

	public function update($tbl, $where, $data)
	{
		$this->db->update($tbl, $data, $where);
		return $this->db->affected_rows();
	}

	public function simpan($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function delete($table, $field, $id)
	{
		$this->db->where($field, $id);
		$this->db->delete($table);

		return $this->db->affected_rows();
	}
}
