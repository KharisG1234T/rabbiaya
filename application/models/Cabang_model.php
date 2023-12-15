<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cabang_model extends CI_Model
{
	
    public function getAll()
    {
        return $this->db->get('cabang')->result_array();
    }

    public function getById($id_cabang)
    {
        return $this->db->get_where('cabang', ['id_cabang' => $id_cabang])->row_array();
    }

    public function get_area()
    {
        $query = $this->db->get('area');
        return $query->result_array();
    }

    public function get_cabang($id_cabang) {
        $this->db->select('cabang.*, area.area');
        $this->db->from('cabang');
        $this->db->join('area', 'cabang.id_area = area.id_area');
        $this->db->where('cabang.id_cabang', $id_cabang);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->id_cabang = uniqid();
        $this->nama_cabang = $post['nama_cabang'];
        $this->id_area = $post['area'];
    
        return $this->db->insert('cabang', $this);
    }
    

    public function delete($id_cabang)
    {
        return $this->db->delete('cabang', ['id_cabang' => $id_cabang]);
    }

}
