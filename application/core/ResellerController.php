<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ResellerController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Must be logged in AND context must be reseller
        if (!is_staff_logged_in() || $this->session->userdata('login_context') !== 'reseller') {
            redirect(site_url('reseller')); // reseller login page
        }
    }



    /**
     * Flash messages similar to set_alert()
     */
    protected function set_alert($type, $message)
    {
        $this->session->set_flashdata($type, $message);
    }

    /**
     * Current reseller user ID
     */
    protected function get_reseller_id()
    {
        return $this->session->userdata('reseller_id');
    }
}
