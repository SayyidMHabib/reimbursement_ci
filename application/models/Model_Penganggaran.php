<?php
class Model_Penganggaran extends CI_Model
{
	var $table = 'pgj_penganggaran';
	var $column_order = array('agr_id', 'agr_tgl', 'div_nama', 'agr_jenis_dana', 'agr_sifat_kebutuhan', 'total_kebutuhan', 'total_diproses', 'agr_user', 'status'); //set column field database for datatable orderable
	var $column_search = array('agr_jenis_dana', 'agr_sifat_kebutuhan', 'div_nama', 'ins_nama', 'agr_user', 'agr_kode_unik', 'agr_rincian'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('status' => 'asc', 'agr_tgl' => 'desc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($tgl1, $tgl2, $periode)
	{
		$whs = "";
		if ($this->session->userdata("level") == 1) $whs = "and (agr_approve_yys = 1 or agr_tolak = 1)";
		if ($this->session->userdata("level") == 3) $whs = "and (agr_approve_yys = 1 or agr_tolak = 1)";
		if ($this->session->userdata("level") == 4) $whs = "and (agr_approve_ben = 1 or agr_tolak = 1)";
		if ($this->session->userdata("level") == 5) $whs = "and (agr_approve_mgr = 1 or agr_tolak = 1)";
		if ($this->session->userdata("level") == 6) $whs = "and (agr_approve_beny = 1 or agr_tolak = 1)";
		if ($this->session->userdata("level") == 7) $whs = "and (agr_approve_yys = 1 or agr_tolak = 1)";
		$this->db->group_by('agr_id');

		$this->db->select("pgj_penganggaran.*, pgj_divisi.*, pgj_instansi.*, 
		(select sum(agr_dana_approve) from pgj_penganggaran where agr_id and agr_approve_yys = 1) as total_diproses, 
		(if (
			(select sum(agr_dana_jml) from pgj_penganggaran where agr_id) = (select sum(agr_dana_jml) from pgj_penganggaran where agr_id {$whs})
			, 1, 0
			)
		) as status, 
		(select count(*) from pgj_penganggaran where agr_id) as jml, 
		(select count(*) from pgj_penganggaran where agr_id {$whs}) as selesai, 
		(select count(*) from pgj_penganggaran where agr_id and agr_tolak = 1) as tolak");
		$this->db->from($this->table);
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");

		if ($this->session->userdata("level") == 2) $this->db->where("agr_user", $this->session->userdata("username"));
		if ($this->session->userdata("level") == 3) {
			// $this->db->where("agr_user", $this->session->userdata("username"));
			$this->db->where("agr_div_id", $this->session->userdata("div_id"));
		}
		if ($this->session->userdata("level") == 4) {
			// $this->db->where("agr_user", $this->session->userdata("username"));
			$this->db->where("agr_ins_id", $this->session->userdata("ins_id"));
		}

		if ($this->session->userdata("level") == 5) {
			$this->db->where("agr_approve_ben", 1);
			$this->db->where("agr_ins_id", $this->session->userdata("ins_id"));
		}

		if ($this->session->userdata("level") == 6) {
			$this->db->where("agr_approve_mgr", 1);
		}

		if ($this->session->userdata("level") == 7) {
			$this->db->where("agr_approve_beny", 1);
		}

		if ($tgl1 != 'null' and $tgl2 != 'null') {
			$this->db->where("agr_tgl >= '{$tgl1}'");
			$this->db->where("agr_tgl <= '{$tgl2}'");
		}

		if ($periode != 0) {
			$this->db->where("agr_periode", $periode);
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

	function get_datatables($tgl1, $tgl2, $periode)
	{
		$this->_get_datatables_query($tgl1, $tgl2, $periode);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($tgl1, $tgl2, $periode)
	{
		$this->_get_datatables_query($tgl1, $tgl2, $periode);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function get_detail($id)
	{
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$this->db->join("pgj_login", "log_user = agr_user", "left");
		$this->db->where("agr_id", $id);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_penerima_penganggaran($level, $ins_id, $div_id)
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_pegawai", "peg_id = log_peg_id", "left");
		$this->db->join("pgj_divisi", "div_id = log_div_id", "left");
		$this->db->join("pgj_instansi", "ins_id = log_ins_id", "left");
		$this->db->where("log_level", $level + 1);

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

	public function get_penerima_pencairan($level, $ins_id, $div_id)
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_pegawai", "peg_id = log_peg_id", "left");
		$this->db->join("pgj_divisi", "div_id = log_div_id", "left");
		$this->db->join("pgj_instansi", "ins_id = log_ins_id", "left");
		$this->db->where("log_level", 3);
		$this->db->where("div_id", $div_id);
		$this->db->where("ins_id", $ins_id);

		$query = $this->db->get();

		return $query->result();
	}

	public function get_penganggaran()
	{
		$this->db->from("pgj_login");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$query = $this->db->get();

		return $query->result();
	}

	public function cari_detail($id)
	{
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$this->db->join("pgj_login", "log_user = agr_user", "left");
		$this->db->where("agr_id", $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get($id)
	{
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$this->db->join("pgj_login", "log_user = agr_user", "left");
		$this->db->where("agr_id", $id);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_semua()
	{
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$this->db->where("agr_cair", 0);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_setuju($id = null)
	{
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_penganggaran", "agr_id", "left");
		$this->db->join("pgj_pegawai", "agr_peg_id = peg_id", "left");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		if ($id) $this->db->where("agr_id", $id);
		$this->db->where("agr_status", 1);
		$this->db->where("agr_approve_ben", 0);
		$this->db->where("agr_cair_ben", 0);
		$query = $this->db->get();

		return $query->result();
	}

	public function cari_penganggaran($id)
	{
		$this->db->select("pgj_penganggaran.*,pgj_pegawai.*, pgj_divisi.*, pgj_instansi.*");
		$this->db->from("pgj_penganggaran");
		$this->db->join("pgj_pegawai", "agr_peg_id = peg_id", "left");
		$this->db->join("pgj_divisi", "agr_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "agr_ins_id = ins_id", "left");
		$this->db->where('agr_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function show_penganggaran($id)
	{
		$this->db->from('pgj_penganggaran');
		$this->db->where('agr_aju_id', $id);
		$query = $this->db->get();

		return $query->result();
	}

	public function get_catatan($id)
	{
		$this->db->from("pgj_catatan");
		$this->db->where("ctt_agr_id", $id);

		$query = $this->db->get();

		return $query->result();
	}

	public function cari_catatan($id)
	{
		$this->db->from("pgj_catatan");
		$this->db->join("pgj_penganggaran", "agr_id = ctt_agr_id", "left");
		$this->db->where("ctt_id", $id);

		$query = $this->db->get();

		return $query->row();
	}

	public function get_file($id)
	{
		$this->db->from("pgj_file");
		$this->db->where("file_agr_id", $id);

		$query = $this->db->get();

		return $query->result();
	}

	public function cari_file($id)
	{
		$this->db->from("pgj_file");
		$this->db->where("file_id", $id);

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
