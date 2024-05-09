<?php
class Model_Progress extends CI_Model
{
	var $table = 'pgj_pengajuan_detail';
	var $column_order = array('ajud_id', 'aju_tgl', 'aju_sifat_kebutuhan', 'ajud_penggunaan', 'ajud_jml', 'aju_user'); //set column field database for datatable orderable
	var $column_search = array('aju_jenis_dana', 'aju_sifat_kebutuhan', 'aju_user', 'ajud_penggunaan', 'aju_kode_unik'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('ajud_tolak' => 'asc', 'ajud_cair_finance' => 'asc', 'ajud_approve_direktur' => 'asc', 'aju_tgl' => 'desc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($prg, $tgl1, $tgl2)
	{
		$whs = "";
		if ($this->session->userdata("level") == 1) $whs = "";
		if ($this->session->userdata("level") == 2) $whs = "";
		if ($this->session->userdata("level") == 3) $whs = "";

		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");

		if ($whs) $this->db->where($whs);
		if ($prg == 1) $this->db->where("ajud_approve_direktur = 0 and ajud_tolak is null");
		if ($prg == 2) $this->db->where("ajud_cair_finance = 0 and ajud_approve_direktur = 1");
		if ($prg == 3) $this->db->where("ajud_tolak = 1");
		if ($prg == 4) $this->db->where("ajud_cair_finance = 1");

		if ($this->session->userdata("level") == 3) $this->db->where("aju_user", $this->session->userdata("username"));

		if ($this->session->userdata("level") == 2) {
			$this->db->where("ajud_approve_direktur = 1");
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

	function get_datatables($prg, $tgl1, $tgl2)
	{
		$this->_get_datatables_query($prg, $tgl1, $tgl2);
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($prg, $tgl1, $tgl2)
	{
		$this->_get_datatables_query($prg, $tgl1, $tgl2);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function get_catatan($id)
	{
		$this->db->from("pgj_catatan");
		$this->db->where("ctt_ajud_id", $id);

		$query = $this->db->get();

		return $query->result();
	}

	public function cari_catatan($id)
	{
		$this->db->from("pgj_catatan");
		$this->db->join("pgj_pengajuan_detail", "ctt_ajud_id = ajud_id", "left");
		$this->db->join("pgj_pengajuan", "aju_id = ajud_aju_id", "left");
		$this->db->where("ctt_id", $id);

		$query = $this->db->get();

		return $query->row();
	}

	public function get_file($id)
	{
		$this->db->from("pgj_file");
		$this->db->where("file_ajud_id", $id);

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

	public function get_pengajuan()
	{
		$this->db->from("pgj_pengajuan");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		$query = $this->db->get();

		return $query->result();
	}

	public function get_detail($id)
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "ajud_aju_id = aju_id", "left");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
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
		$this->db->where("ajud_proses", 0);
		$this->db->where("ajud_cair", 0);
		$query = $this->db->get();

		return $query->result();
	}

	public function cari_pengajuan($id)
	{
		$this->db->from("pgj_pengajuan");
		$this->db->join("pgj_pegawai", "aju_peg_id = peg_id", "left");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		$this->db->where('aju_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function cari_pengajuan_detail($id)
	{
		$this->db->from("pgj_pengajuan_detail");
		$this->db->join("pgj_pengajuan", "aju_id = ajud_aju_id", "left");
		$this->db->join("pgj_pegawai", "aju_peg_id = peg_id", "left");
		$this->db->join("pgj_divisi", "aju_div_id = div_id", "left");
		$this->db->join("pgj_instansi", "aju_ins_id = ins_id", "left");
		$this->db->where('ajud_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function getAllDevisi()
	{
		$this->db->from('pgj_divisi');
		$query = $this->db->get();

		return $query->result();
	}

	public function getAllInstansi()
	{
		$this->db->from('pgj_instansi');
		$query = $this->db->get();

		return $query->result();
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
