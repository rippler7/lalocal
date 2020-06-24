<?php 

class Model_status extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the active status */
	public function getStatus($id)
	{
		$sql = "SELECT * FROM status WHERE status_id = ?";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function getStatuses()
	{
		$sql = "SELECT * FROM status";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('status', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('status_id', $id);
			$update = $this->db->update('status', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('status_id', $id);
			$delete = $this->db->delete('status');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalStatuses()
	{
		$sql = "SELECT * FROM status";
		$query = $this->db->query($sql, array(1));
		return $query->num_rows();
	}

}