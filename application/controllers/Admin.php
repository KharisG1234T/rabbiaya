<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // user access
        is_logged_in();
        $this->load->model(array('Admin_model'));
    }

    // function index view
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'user' => $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array(),
            'user_role' => $this->db->get('user_role')->num_rows(),
            'user_member' => $this->db->get_where('user')->num_rows(),
            'menu' => $this->db->get('user_menu')->num_rows(),
            'sub_menu' => $this->db->get('user_sub_menu')->num_rows(),
            'peminjaman' => $this->db->get('peminjaman')->num_rows(),
        ];
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('templates/admin_footer');
    }

    // function role
    public function role()
    {
        $data['title'] = 'Tingkatan Hak Akses';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('admin/role');
        $this->load->view('templates/admin_footer');
    }

    // function add role
    public function addrole()
    {
        $this->form_validation->set_rules('role', 'Authority', 'required', [
            'required' => 'Authority name cannot be empty!'
        ]);

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Akses DiIzinkan';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['role'] = $this->db->get('user_role')->result_array();
            
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('admin/role');
            $this->load->view('templates/admin_footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            New authority has been added</div>');
            redirect('admin/role');
        }
    }

    // function edit role
    public function editrole($id = null)
    {
        $this->form_validation->set_rules('role', 'Authority', 'required');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Edit Hak Akses';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['role'] = $this->db->get_where('user_role', ['id' => $id])->row_array();
            
            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('admin/edit_role');
            $this->load->view('templates/admin_footer');
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Gagal Memperbarui Akses!</div>');
        } else {
            $data = [
                'id' => $this->input->post('id'),
                'role' => $this->input->post('role')
            ];

            $this->db->update('user_role', $data, ['id' => $id]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Berhasil Memperbarui Akses!</div>');
            redirect('admin/role');
        }
    }

    // function delete role
    public function deleterole($id = null)
    {
        $this->db->delete('user_role', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akses Berhasil DiHapus!</div>');
        redirect('admin/role');
    }
    
    // function role access
    public function roleaccess($role_id)
    {
        $data['title'] = 'Tingkatan Hak Akses';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();
        
        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('admin/role_access');
        $this->load->view('templates/admin_footer');
    }

    // change access
    public function changeaccess()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');

        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);

        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akses Telah Diperbarui!</div>');
    }

    // data member info
    public function datamember()
    {
        $data['title'] = 'User Data';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $member = $this->db->get("user")->result_array();
        foreach($member as $key=> $mem){
            $userarea = $this->db->select("area_id")->where("user_id", $mem['id'])->get("user_area")->result_array();
            $areaIds = [0];
            foreach($userarea as $ua){
                array_push($areaIds, (int)$ua['area_id']);
            }
            $area = $this->db->select("area")->where_in("id_area", $areaIds)->get("area")->result_array();
            $member[$key]["area"] = $area;
        }
        $data['user_member'] = $member;

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('admin/data_member', $data);
        $this->load->view('templates/admin_footer');
    }

     // function member access
     public function areaaccess($user_id)
     {
         $data['title'] = 'Pengaturan Akses Data Berdasarkan Area';
         $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
         $data['users'] = $this->db->get_where('user', ['id' => $user_id])->row_array();
 
         $data['area'] = $this->db->get('area')->result_array();
         
         $this->load->view('templates/admin_header', $data);
         $this->load->view('templates/admin_sidebar');
         $this->load->view('templates/admin_topbar', $data);
         $this->load->view('admin/member_access');
         $this->load->view('templates/admin_footer');
     }
 
     // change access area
     public function changearea()
    {
    $user_id = $this->input->post('userId');
    $areas = $this->input->post('areas');

    $this->db->where('user_id', $user_id);
    $this->db->delete('user_area');

    foreach ($areas as $area_id) {
        $data = [
        'user_id' => $user_id,
        'area_id' => $area_id
        ];
        $this->db->insert('user_area', $data);
    }

    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">Area Telah Diperbarui!</div>');
    redirect('admin/areaaccess/' . $user_id);
    }


    // info detail member
    public function detailmember($id)
    {
        $data['title'] = 'User Data Info';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['member'] = $this->db->get_where('user', ['id' => $id])->row_array();
        $data['roles'] = $this->db->get('user_role')->result_array();

        $this->load->view('templates/admin_header', $data);
        $this->load->view('templates/admin_sidebar');
        $this->load->view('templates/admin_topbar', $data);
        $this->load->view('admin/detail_member', $data);
        $this->load->view('templates/admin_footer');
    }

    // delete member
    public function deletemember($id)
    {
        $this->db->delete('user', ['id' => $id]);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        User deleted successfully!</div>');
        redirect('admin/datamember');
    }

    // edit member
    public function editmember($id)
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        
        if ($this->form_validation->run() == false) {
            $data['title'] = 'Perbarui User Data';
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
            $data['member'] = $this->db->get_where('user', ['id' => $id])->row_array();
            $data['roles'] = $this->db->get('user_role')->result_array();

            $this->load->view('templates/admin_header', $data);
            $this->load->view('templates/admin_sidebar');
            $this->load->view('templates/admin_topbar', $data);
            $this->load->view('admin/edit_member', $data);
            $this->load->view('templates/admin_footer');
        } else {
            $data = [
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'role_id' => $this->input->post('role_id'),
                'is_active' => $this->input->post('is_active')
            ];
                
            $this->db->update('user', $data, ['id' => $data['id']]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Data User Berhasil Diperbarui!</div>');
            redirect('admin/datamember');
        }
    }
    public function changepassword()
    {
        $data['title'] = 'Ganti Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

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
            $new_password = $this->input->post('new_password1');

            if ($new_password == $data['user']['password']) {
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

    public function editprofile()
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


}