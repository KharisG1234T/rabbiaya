<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cabang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('Cabang_model');
    }

    public function index()
    {
        $data['title'] = 'List Cabang Terdaftar';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['cabangs'] = $this->Cabang_model->getAll();
        $data['areas'] = $this->Cabang_model->get_area();

        foreach ($data['cabangs'] as &$cabang) {
            $area_cabang = $this->Cabang_model->get_cabang($cabang['id_cabang']);
            if ($area_cabang) {
                $area = $area_cabang['area'];
                $cabang['area'] = $area;
            } else {
                $cabang['area'] = '-';
            }
        }

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('cabang/index', $data);
        $this->load->view('templates/admin_footer');
    }


    

    // add cabang
    public function addcabang()
    {
        $data['title'] = 'Daftar Cabang';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['nama_cabang'] = $this->db->get('cabang')->result_array();
        $data['areas'] = $this->Cabang_model->get_area();

        $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required', [
            'required' => 'Nama Cabang harus di isi !'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('cabang/index', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $id_area = $this->input->post("id_area");
            $check_area = $this->db->get_where("cabang", ['id_area' => $id_area])->row_array();

            if ($check_area) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Gagal menambahkan cabang, area sudah digunakan!</div>');
                redirect('cabang');
            } else {
                $this->db->insert('cabang', ['nama_cabang' => $this->input->post('nama_cabang'), 'id_area' => $this->input->post('id_area')]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                Cabang baru berhasil ditambahkan!</div>');
                redirect('cabang');
            }
        }
    }


    public function editcabang($id_cabang = null)
    {
        $this->form_validation->set_rules('nama_cabang', 'Nama Cabang', 'required', [
            'required' => 'Nama Cabang tidak boleh kosong !'
        ]);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Cabang Edit';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['nama_cabang'] = $this->db->get_where('cabang', ['id_cabang' => $id_cabang])->row_array();
            $data['areas'] = $this->Cabang_model->get_area();

            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('cabang/edit_cabang', $data);
            $this->load->view('templates/admin_footer');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal merubah cabang!</div>');
        } else {
            $id_area = $this->input->post("id_area");
            $check_area = $this->db->get_where("cabang", ['id_area' => $id_area])->row_array();

            $data = [
                'id_cabang' => $this->input->post('id_cabang'),
                'id_area' => $this->input->post('id_area'),
                'nama_cabang' => $this->input->post('nama_cabang')
            ];

            if ($check_area && ($this->input->post('id_area') !== $this->input->post('old_area'))) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Gagal mengubah data, area sudah digunakan !</div>');
                redirect('cabang');
            } else {
                $this->db->update('cabang', $data, ['id_cabang' => $id_cabang]);
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Cabang berhasil diperbarui !</div>');
                redirect('cabang');
            }
        }
    }

    public function search_area()
    {
        $query = $this->input->get('query');
        $areas = $this->Cabang_model->search_area($query);
        echo json_encode($areas);
    }


    // delete cabang
    public function deletecabang()
    {
        $id_cabang = $this->input->post("id_cabang");
        if (!isset($id_cabang)) show_404();

        $cabangs = $this->Cabang_model;
        if ($cabangs->delete($id_cabang)) {
            redirect('cabang');
        }
    }

    
}
