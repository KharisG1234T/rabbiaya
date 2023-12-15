<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Area extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('Area_model');
    }

    public function index()
    {
        $data['title'] = 'List Area Terdaftar';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['areas'] = $this->Area_model->getAll();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('area/index', $data);
        $this->load->view('templates/admin_footer');
        
    }

    // add area
    public function addarea()
    {
        $data['title'] = 'Daftar Area Cabang';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['area'] = $this->db->get('area')->result_array();

        $this->form_validation->set_rules('area', 'Nama Area', 'required', [
            'required' => 'Nama Area harus di isi !'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('area/index', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $this->db->insert('area', ['area' => $this->input->post('area')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Area baru berhasil ditambahkan!</div>');
            redirect('area');
        }
    }

    public function editarea($id_area = null)
    {   
        $this->form_validation->set_rules('area', 'Nama Area', 'required', [
            'required' => 'Nama Area tidak boleh kosong !'
        ]);
        
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Edit Area';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['area'] = $this->db->get_where('area', ['id_area' => $id_area])->row_array();

            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('area/edit_area', $data);
            $this->load->view('templates/admin_footer');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal merubah area!</div>');
        } else {
            $data = [
                'id_area' => $this->input->post('id_area'),
                'area' => $this->input->post('area')
            ];

            $this->db->update('area', $data, ['id_area' => $id_area]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Area berhasil dirubah !</div>');
            redirect('area');
        }
    }

    // delete area


    public function deletearea()
    {
        $id_area = $this->input->post("id_area");
        if (!isset($id_area)) show_404();

        $areas = $this->Area_model;
        if ($areas->delete($id_area)) {
            redirect('area');
        }
    }

    
}