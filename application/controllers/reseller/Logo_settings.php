<?php defined('BASEPATH') or exit('No direct script access allowed');

class Logo_settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['file', 'settings']);
        $this->load->model('staff_model');
    }

    private function is_restricted_role()
    {
        $staff = $this->staff_model->get(get_staff_user_id());

        if (!$staff || !isset($staff->role)) {
            return false;
        }

        $restricted_roles = ['Employee', 'partner admin', 'Management', 'reseller'];
        return in_array(strtolower(trim($staff->role)), $restricted_roles);
    }

    public function index()
    {
        if ($this->is_restricted_role()) {
            set_alert('danger', 'You are not allowed to access Logo Settings.');
            redirect(site_url());
        }

        $user_id     = get_staff_user_id();
        $custom_logo = get_option('admin_logo_custom_user_' . $user_id);

        //Default logo for all new users
        $default_logo = 'assets/images/company-default-logo.png';

        $logo_to_show = $custom_logo ? $custom_logo : $default_logo;

        $data['title']        = 'Logo Settings';
        $data['custom_logo']  = $custom_logo;
        $data['logo_to_show'] = $logo_to_show;

        $this->load->view('reseller/logo_settings', $data);
    }

    public function upload_logo()
    {
        if ($this->is_restricted_role()) {
            access_denied('You are not allowed to change the logo.');
        }

        if (!empty($_FILES['logo_upload']['name'])) {
            $allowed_types = ['image/png', 'image/jpeg', 'image/jpg', 'image/svg+xml'];
            $file_type     = $_FILES['logo_upload']['type'];
            $file_size     = $_FILES['logo_upload']['size'];
            $max_size      = 2 * 1024 * 1024;

            if (!in_array($file_type, $allowed_types)) {
                set_alert('danger', 'Invalid file type. Allowed: PNG, JPG, JPEG, SVG.');
               redirect(base_url('reseller/logo_settings'));
            }

            if ($file_size > $max_size) {
                set_alert('danger', 'File too large. Max size is 2MB.');
                redirect(base_url('reseller/logo_settings'));
            }

            $path          = get_upload_path_by_type('company');
            $logo_filename = unique_filename($path, $_FILES['logo_upload']['name']);
            $logo_path     = $path . $logo_filename;

            if (move_uploaded_file($_FILES['logo_upload']['tmp_name'], $logo_path)) {
                $user_id = get_staff_user_id();
                $relative = 'uploads/company/' . $logo_filename;

                //Save logo only for this user
                update_option('admin_logo_custom_user_' . $user_id, $relative);

                set_alert('success', 'Logo uploaded successfully.');
            } else {
                set_alert('danger', 'Logo upload failed.');
            }
        }

        $this->session->set_flashdata('logo_set_trigger_reload', true);
        redirect(base_url('reseller/logo_settings'));
    }

    public function reset_logo()
    {
        if ($this->is_restricted_role()) {
            access_denied('You are not allowed to reset the logo.');
        }

        $user_id = get_staff_user_id();

        //Delete only this user’s custom logo
        delete_option('admin_logo_custom_user_' . $user_id);

        set_alert('success', 'Your logo has been reset to the system default.');
        $this->session->set_flashdata('logo_reset_trigger_reload', true);

        redirect(base_url('reseller/logo_settings'));
    }
}
