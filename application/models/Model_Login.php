<?php
class Model_Login extends CI_Model
{
	var $table = 'pgj_login';
	var $column_order = array('log_user', 'log_nama'); //set column field database for datatable orderable
	var $column_search = array('log_user', 'log_level', 'log_nama'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('log_level' => 'asc', 'log_user' => 'asc'); // default order  	private $db_sts;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user = $this->session->userdata('level');
		$this->db->from($this->table);
		$this->db->join("pgj_pegawai", "log_peg_id = peg_id", "left");
		$this->db->where('log_level > 0');

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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function get_pengguna()
	{
		$this->db->from("pgj_login");
		$query = $this->db->get();

		return $query->result();
	}

	public function cek_pengguna()
	{
		$this->db->from("pgj_login");
		$query = $this->db->get();

		return $query->row();
	}

	public function cari_pengguna($id)
	{
		$this->db->from("pgj_login");
		$this->db->where('log_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	function cek($username, $password)
	{
		$this->db->from('pgj_login');
		$this->db->join('pgj_pegawai', 'peg_id = log_peg_id', 'left');
		$this->db->where("log_user", $username);
		$this->db->where("log_pass", md5($password));
		return $this->db->get();
	}

	public function getlastquery()
	{
		$query = str_replace(array("\r", "\n", "\t"), '', trim($this->db->last_query()));

		return $query;
	}

	public function get_enum($table, $field)
	{
		$q = "show columns from $table like '$field'";
		$row = $this->db->query("show columns from $table like '$field'")->row()->Type;
		$enum_array = explode("(", str_replace(")", "", str_replace("'", "", $row)));
		$enum_field = explode(",", $enum_array[1]);

		foreach ($enum_field as $key => $value) {
			$enums[$value] = $value;
		}
		return $enums;
	}

	public function cari_level_pengguna($level)
	{
		$this->db->from("pgj_pegawai");
		$this->db->join("pgj_kontrak", "peg_id = ktr_peg_id", "left");
		$this->db->join("pgj_jabatan", "jbt_id = ktr_jbt_id", "left");
		$this->db->join("pgj_divisi", "div_id = ktr_div_id", "left");
		$this->db->join("pgj_instansi", "ins_id = ktr_ins_id", "left");
		if ($level == 3) {
			$this->db->where('jbt_level', 4);
		} elseif ($level == 4) {
			$this->db->where('jbt_level', 5);
		} elseif ($level == 6) {
			$this->db->where('jbt_level', 7);
		} elseif ($level == 7) {
			$this->db->where('jbt_level', 6);
		} elseif ($level == 8) {
			$this->db->where('jbt_level', 2);
		} elseif ($level == 9) {
			$this->db->where('jbt_level', 3);
		}
		$this->db->where('ktr_status', 1);
		$this->db->where('peg_status_data', 1);
		$this->db->where('ktr_berlaku_sampai  > CURDATE()');
		$query = $this->db->get();
		return $query->result();
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
