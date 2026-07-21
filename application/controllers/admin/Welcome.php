<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'System Maintenance in Progress';
        $this->load->view('welcome_page', $data);
    }
}
