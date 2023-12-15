<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Area_model extends CI_Model
{
	
    public function getAll()
    {
        return $this->db->get('area')->result_array();
    }

    public function getById($id_area)
    {
        return $this->db->get_where('area', ['id_area' => $id_area])->row_array();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->id_area               = uniqid();
        $this->area             = $post['area'];

        return $this->db->insert('area', $this);
    }


    public function delete($id_area)
    {
        return $this->db->delete('area', ['id_area' => $id_area]);
    }

}
