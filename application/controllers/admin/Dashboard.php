<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends AdminController
{
  

    // public function __construct()
    // {
    //     parent::__construct();
    //     $this->load->model('dashboard_model');

    //     $staff_id = get_staff_user_id();

    //     if (!$staff_id) {
    //         set_alert('warning', 'You have been logged out.');
    //         redirect(site_url('authentication'));
    //     }

    //     $staff = $this->staff_model->get($staff_id);
    //     $role  = $this->roles_model->get($staff->role ?? 0);
    //     $role_name = strtolower(trim($role->name ?? ''));

    //     if ($role_name === 'agent' && $this->router->fetch_class() === 'dashboard') {
    //         return $this->reseller_dashboard($staff_id);
    //     }
    // }
  
  public function __construct()
{
    parent::__construct();
    $this->load->model('dashboard_model');

    $staff_id = get_staff_user_id();

    if (!$staff_id) {
        set_alert('warning', 'You have been logged out.');
        redirect(site_url('authentication'));
    }

    $staff = $this->staff_model->get($staff_id);
    $role  = $this->roles_model->get($staff->role ?? 0);
    $role_name = strtolower(trim($role->name ?? ''));

    // Only Platform Administrator (or is_admin()) sees the real admin dashboard.
    // Everyone else goes to the reseller dashboard, by default — not by whitelist.
    if (!is_admin() && $role_name !== 'platform administrator' && $this->router->fetch_class() === 'dashboard') {
        return $this->reseller_dashboard($staff_id);
    }
}

    // This is admin dashboard view
    public function index()
    {
      
      	        $this->load->model('staff_model');
        $this->load->model('roles_model');

        $staff_id = get_staff_user_id();
        $staff = $this->staff_model->get($staff_id);
        $role = $this->roles_model->get($staff->role ?? 0);

        $role_name = strtolower(trim($role->name ?? ''));
        $context   = $this->session->userdata('login_context') ?? '';

    if ($context === 'reseller' || (!is_admin() && $role_name !== 'platform administrator')) {
        return $this->reseller_dashboard($staff_id);
    }
        close_setup_menu();
        $this->load->model('departments_model');
        $this->load->model('todo_model');
        $data['departments'] = $this->departments_model->get();

        $data['todos'] = $this->todo_model->get_todo_items(0);
        // Only show last 5 finished todo items
        $this->todo_model->setTodosLimit(5);
        $data['todos_finished']            = $this->todo_model->get_todo_items(1);
        $data['upcoming_events_next_week'] = $this->dashboard_model->get_upcoming_events_next_week();
        $data['upcoming_events']           = $this->dashboard_model->get_upcoming_events();
        $data['title']                     = _l('dashboard_string');

        $this->load->model('contracts_model');
        $data['expiringContracts'] = $this->contracts_model->get_contracts_about_to_expire(get_staff_user_id());

        $this->load->model('currencies_model');
        $data['currencies']    = $this->currencies_model->get();
        $data['base_currency'] = $this->currencies_model->get_base_currency();
        $data['activity_log']  = $this->misc_model->get_activity_log();
        // Tickets charts
        $tickets_awaiting_reply_by_status     = $this->dashboard_model->tickets_awaiting_reply_by_status();
        $tickets_awaiting_reply_by_department = $this->dashboard_model->tickets_awaiting_reply_by_department();

        $data['tickets_reply_by_status']              = json_encode($tickets_awaiting_reply_by_status);
        $data['tickets_awaiting_reply_by_department'] = json_encode($tickets_awaiting_reply_by_department);

        $data['tickets_reply_by_status_no_json']              = $tickets_awaiting_reply_by_status;
        $data['tickets_awaiting_reply_by_department_no_json'] = $tickets_awaiting_reply_by_department;

        $data['projects_status_stats'] = json_encode($this->dashboard_model->projects_status_stats());
        $data['leads_status_stats']    = json_encode($this->dashboard_model->leads_status_stats());
        $data['google_ids_calendars']  = $this->misc_model->get_google_calendar_ids();
        $data['bodyclass']             = 'dashboard invoices-total-manual';
        $this->load->model('announcements_model');
        $data['staff_announcements']             = $this->announcements_model->get();
        $data['total_undismissed_announcements'] = $this->announcements_model->get_total_undismissed_announcements();

        $this->load->model('projects_model');
        $data['projects_activity'] = $this->projects_model->get_activity('', hooks()->apply_filters('projects_activity_dashboard_limit', 20));
        add_calendar_assets();
        $this->load->model('utilities_model');
        $this->load->model('estimates_model');
        $data['estimate_statuses'] = $this->estimates_model->get_statuses();

        $this->load->model('proposals_model');
        $data['proposal_statuses'] = $this->proposals_model->get_statuses();

        $wps_currency = 'undefined';
        if (is_using_multiple_currencies()) {
            $wps_currency = $data['base_currency']->id;
        }
        $data['weekly_payment_stats'] = json_encode($this->dashboard_model->get_weekly_payments_statistics($wps_currency));

        $data['dashboard'] = true;

        $data['user_dashboard_visibility'] = get_staff_meta(get_staff_user_id(), 'dashboard_widgets_visibility');

        if (! $data['user_dashboard_visibility']) {
            $data['user_dashboard_visibility'] = [];
        } else {
            $data['user_dashboard_visibility'] = unserialize($data['user_dashboard_visibility']);
        }
        $data['user_dashboard_visibility'] = json_encode($data['user_dashboard_visibility']);

        $data['tickets_report'] = [];
        if (is_admin()) {
            $data['tickets_report'] = (new app\services\TicketsReportByStaff())->filterBy('this_month');
        }

        $data = hooks()->apply_filters('before_dashboard_render', $data);


        $this->load->view('admin/dashboard/dashboard', $data);


        }


public function reseller_dashboard($staff_id = null)
{
    $this->load->model('pipeline_report_model');

    // ✅ Get from session if not passed
    if (!$staff_id) {
        $staff_id = get_staff_user_id();
    }

    // Safety: if still no staff_id (not logged in)
    if (!$staff_id) {
        redirect(site_url('reseller'));
        exit;
    }

    $data['inactive_users_count'] = $this->pipeline_report_model->count_inactive_users($staff_id);
    $data['active_agents']        = $this->pipeline_report_model->get_active_agents($staff_id);
    $data['merchants']            = $this->pipeline_report_model->get_merchants($staff_id);
    $data['pre_applications']     = $this->pipeline_report_model->get_pre_applications($staff_id);

    $data['title'] = 'Reseller Dashboard';

    $this->load->view('reseller/reseller_dashboard', $data);
}


    // Chart weekly payments statistics on home page / ajax
    public function weekly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_weekly_payments_statistics($currency));

            exit();
        }
    }

    // Chart monthly payments statistics on home page / ajax
    public function monthly_payments_statistics($currency)
    {
        if ($this->input->is_ajax_request()) {
            echo json_encode($this->dashboard_model->get_monthly_payments_statistics($currency));

            exit();
        }
    }

    public function ticket_widget($type)
    {
        $data['tickets_report'] = (new app\services\TicketsReportByStaff())->filterBy($type);
        $this->load->view('admin/dashboard/widgets/tickets_report_table', $data);
    }
}
