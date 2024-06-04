<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Official_trip_detail_model extends CI_Model
{

    public function getAll()
    {
        return $this->db->get('official_trip_detail')->result_array();
    }

    public function getAllBy($id)
    {
        // Query to get trip details
        $tripDetails = $this->db->where('official_trip_id', $id)->get('official_trip_detail')->result_array();

        // Iterate through trip details and add activity information
        foreach ($tripDetails as &$detail) {
            $activityId = $detail['official_trip_activity_id'];
            $activityInfo = $this->db->select('*')->from('official_trip_activity')->where('id', $activityId)->get()->row_array();
            $detail['activity'] = $activityInfo; // Add activity information to trip detail
        }

        // Return trip details including activity information
        return $tripDetails;
    }

    public function update($id, $data)
    {
        return $this->db->where('official_trip_id', $id)->update('official_trip_detail', $data);
    }

    public function save($data)
    {
        return $this->db->insert('official_trip_detail', $data);
    }

    public function deleteByOfficialTripId($id)
    {
        return $this->db->delete('official_trip_detail', ['official_trip_id' => $id]);
    }
}
