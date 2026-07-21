<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ResellerDashboard extends App_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!is_staff_logged_in()) {
            redirect(site_url('reseller/login'));
        }
        $email    = $this->input->post('email');
        $password = $this->input->post('password', false);
        $remember = $this->input->post('remember');

        $staff_id = get_staff_user_id(); // built-in helper
        $this->db->select('roles.name as role_name');
        $this->db->from(db_prefix() . 'staff as staff');
        $this->db->join(db_prefix() . 'roles as roles', 'roles.roleid = staff.role', 'left');
        $this->db->where('staff.staffid', $staff_id);
        $user = $this->db->get()->row();

        if (!$user || !in_array(strtolower($user->role_name), ['reseller', 'partner admin', 'agent'])) {
            access_denied('Only reseller-type roles can access this area.');
        }


        // $this->db->select('staff.*, roles.name as role_name');
        // $this->db->from(db_prefix() . 'staff as staff');
        // $this->db->join(db_prefix() . 'roles as roles', 'roles.roleid = staff.role', 'left');
        // $this->db->where('staff.email', $email);
        // $user = $this->db->get()->row();

        //  $role = strtolower($user->role_name);
        // if (!in_array($role, ['reseller', 'partner admin', 'agent'])) {
        //     access_denied('Only reseller-type roles can access this area.');
        // }
    }
  

    public function dashboard() {
    $this->load->model('pipeline_report_model');
    $staff_id = get_staff_user_id(); 

    $data['inactive_users_count'] = $this->pipeline_report_model->count_inactive_users($staff_id);
    $data['active_agents']        = $this->pipeline_report_model->get_active_agents($staff_id);
    $data['merchants']            = $this->pipeline_report_model->get_merchants($staff_id);
    $data['pre_applications']     = $this->pipeline_report_model->get_pre_applications($staff_id);
    
    $data['title']  = 'Reseller Dashboard';
    $data['locale'] = get_locale_key(); 
    $this->load->view('reseller/reseller_dashboard', $data);
    }


    


}


