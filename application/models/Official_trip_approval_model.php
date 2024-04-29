<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Official_trip_approval_model extends CI_Model
{

    public function getAll()
    {
        return $this->db->get('official_trip_approval')->result_array();
    }

    public function getById($id)
    {
        return $this->db->get_where('official_trip_approval', ['id' => $id])->row_array();
    }

    public function save($data)
    {
        return $this->db->insert('official_trip_approval', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('official_trip_approval', ['id' => $id]);
    }

    public function getUserApprovalByPeminjamanId($official_trip_id) {
		$this->db->select('status, user_id');
		$this->db->where('official_trip_id', $official_trip_id);
		return $this->db->get('official_trip_approval')->row_array();
	}

    public function getUserById($id)
    {
        return $this->db->get_where('user', ['id' => $id])->row_array();
    }

}
