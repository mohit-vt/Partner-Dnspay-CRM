<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reseller_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Force login if session is missing (after admin logout)
        if (!$this->session->userdata('agent_logged_in')) {
            set_alert('warning', 'You have been logged out by admin.');
            redirect(site_url('reseller')); // redirect to agent login
        }
    }

    public function dashboard()
    {
        $data['title'] = 'Agent Dashboard';
        $this->load->view('reseller/dashboard', $data);
    }
}
