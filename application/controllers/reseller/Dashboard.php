<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController {
public function index($staff_id = null)
{
    $this->load->model('pipeline_report_model');

    if (!$staff_id) {
        $staff_id = get_staff_user_id();
    }

    if (!$staff_id) {
        redirect(site_url('reseller'));
        exit;
    }

    // ✅ Keep inactive users from pipeline_report
    $data['inactive_users_count'] = $this->pipeline_report_model->count_inactive_users($staff_id);

    // ✅ New 3 cards
    $data['active_agents_count']   = $this->pipeline_report_model->count_active_agents($staff_id);
    $data['pipeline_report_count'] = $this->pipeline_report_model->count_pipeline_report_apps($staff_id);
    $data['sub_agents_count']      = $this->pipeline_report_model->count_sub_agents($staff_id);
  
  	//  How many pipeline_report applications submitted per day.

  	$data['pipeline_growth_30'] = $this->pipeline_report_model->get_pipeline_growth_last_30_days($staff_id);
  	$data['pipeline_status_stats'] = $this->pipeline_report_model->get_pipeline_status_stats($staff_id);
  	$data['recent_pipeline'] = $this->pipeline_report_model->get_recent_pipeline($staff_id);



    $data['title'] = 'Reseller Dashboard';
    $this->load->view('reseller/reseller_dashboard', $data);
}

}