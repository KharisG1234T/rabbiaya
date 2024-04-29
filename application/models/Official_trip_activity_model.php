<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Official_trip_activity_model extends CI_Model
{

    public function getAll()
    {
        return $this->db->get('official_trip_activity')->result_array();
    }

    public function getAllBy($id)
    {
        return $this->db->where('id', $id)->get('official_trip_activity')->result_array();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('official_trip_activity', $data);
    }
    public function save($data)
    {
        return $this->db->insert('official_trip_activity', $data);
    }

    public function delete($id)
    {
        return $this->db->delete('official_trip_activity', ['id' => $id]);
    }

}
