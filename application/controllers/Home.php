<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // user access
        is_logged_in();
    }

    // index view web
    public function index()
    {
        $data['title'] = 'Form Approval';

        $this->load->view('templates/home_header', $data);
        $this->load->view('home/index');
        $this->load->view('templates/home_footer', $data);
    }

}