<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // user access
        is_logged_in();
    }

    // index view user info
    public function index()
    {
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // edit profile
    public function edit()
    {
        $data['title'] = 'Perbarui Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('name', 'Full name', 'required', [
            'required' => 'Full name is required!'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('user/edit', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $name = $this->input->post('name');
            $email = $this->input->post('email');
            // upload image

            $upload_image = $_FILES['image']['name'];
            $upload_ttd = $_FILES['ttd']['name'];

            if ($upload_image) {
                $config['allowed_types']    = 'jpg|jpeg|png';
                $config['max_size']         = '6000';
                $config['upload_path']      = './assets/img/profile/';
                $config['encrypt_name']     = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $old_image = $data['user']['image'];
                    if ($old_image != 'default.jpg') {
                        unlink(FCPATH . '/assets/img/profile/' . $old_image);
                    }

                    $new_image = $this->upload->data('file_name');
                    $this->db->set('image', $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            if ($upload_ttd) {
                $config['allowed_types']    = 'jpg|jpeg|png';
                $config['max_size']         = '6000';
                $config['upload_path']      = './assets/img/profile/ttd';
                $config['encrypt_name']     = true;

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('ttd')) {
                    $old_image = $data['user']['ttd'];
                    if ($old_image != 'default.jpg' && $old_image != null) {
                        unlink(FCPATH . '/assets/img/profile/ttd/' . $old_image);
                    }
                    
                    $new_image = $this->upload->data('file_name');
                    $this->db->set('ttd', $new_image);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            

            $this->db->set('name', $name);
            $this->db->where('email', $email);
            $this->db->update('user');

            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Profile changed successfully!</div>');
            redirect('user');
        }
    }

    // change password user
    public function changepassword()
    {
        $data['title'] = 'Ganti Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->form_validation->set_rules('current_password', 'Password Lama', 'required|trim', [
            'required' => 'Masukan Password Lama Anda!'
        ]);
        $this->form_validation->set_rules('new_password1', 'Password Baru', 'required|trim|min_length[5]|matches[new_password2]', [
            'required' => 'Silahkan Masukan Password Baru Anda!',
            'min_length' => 'Password terlalu pendek, minimal 5 karakter!',
            'matches' => 'Passwords tidak sama!'
        ]);
        $this->form_validation->set_rules('new_password2', 'Ketik Ulang Password Baru', 'required|trim|min_length[5]|matches[new_password1]', [
            'required' => 'Fill in new password!',
            'min_length' => 'Password terlalu pendek, minimal 5 karakter!',
            'matches' => 'Passwords tidak sama!'
        ]);

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('user/changepassword', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $current_password = $this->input->post('current_password');
            $new_password = $this->input->post('new_password1');

            if (!password_verify($current_password, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Password salah!</div>');
                redirect('user/changepassword');
            } else {
                if ($current_password == $new_password) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Password baru tidak boleh sama dengan password lama!</div>');
                    redirect('user/changepassword');
                } else {
                    // password ok!
                    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

                    $this->db->set('password', $password_hash);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Password berhasil diganti!</div>');
                    redirect('user/changepassword');
                }
            }
        }
    }

    // delete acc
    public function deleteuser($id)
    {
        $this->db->delete('user', ['id' => $id]);
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akun berhasil dihapus!</div>');
        redirect('auth');
    }
}
