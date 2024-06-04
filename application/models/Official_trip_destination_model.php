<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Official_trip_destination_model extends CI_Model
{

    public function getAll()
    {
        return $this->db->get('official_trip_destination')->result_array();
    }

    public function getAllBy($id)
    {
        return $this->db->where('official_trip_id', $id)->get('official_trip_destination')->result_array();
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('official_trip_destination', $data);
    }

    public function save($data)
    {
        return $this->db->insert('official_trip_destination', $data);
    }

    public function deleteByOfficialTripId($id)
    {
        return $this->db->delete('official_trip_destination', ['official_trip_id' => $id]);
    }
}
