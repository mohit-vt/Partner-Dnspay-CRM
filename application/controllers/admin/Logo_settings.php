<?php defined('BASEPATH') or exit('No direct script access allowed');

class Logo_settings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['file', 'settings']);
    }

private function is_restricted_role()
{
    $this->load->model('staff_model');
    $staff = $this->staff_model->get(get_staff_user_id());

    if (!$staff || !isset($staff->role)) {
        return false;
    }

    $role = strtolower(trim($staff->role));

    $restricted_roles = [
        'employee',
        'partner admin',
        'management',
        'reseller'
    ];

    return in_array($role, $restricted_roles);
}


    public function index()
    {
     if ($this->is_restricted_role()) {
            set_alert('danger', 'You are not allowed to access Logo Settings.');
            redirect(admin_url()); // or use access_denied() if it’s working
        }
           if (!empty($_FILES['logo_upload']['name'])) {
            $path = get_upload_path_by_type('company'); // Saves to uploads/company/
            $filename = unique_filename($path, $_FILES['logo_upload']['name']);
            $fullpath = $path . $filename;

            if (move_uploaded_file($_FILES['logo_upload']['tmp_name'], $fullpath)) {
                update_option('admin_logo_custom', 'uploads/company/' . $filename);
                set_alert('success', _l('Logo uploaded successfully.'));
            } else {
                set_alert('danger', _l('Logo upload failed.'));
            }
        }

        $data['title'] = 'Logo Settings';
        $this->load->view('admin/logo_settings', $data);
    }

 public function upload_logo()
{
    if ($this->is_restricted_role()) {
        access_denied('You are not allowed to change the logo.');
    }

    if (!empty($_FILES['logo_upload']['name'])) {

        $allowed_extensions = ['png', 'jpg', 'jpeg', 'svg'];
        $file_name = $_FILES['logo_upload']['name'];
        $file_size = $_FILES['logo_upload']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $max_size = 2 * 1024 * 1024;

        if (!in_array($file_ext, $allowed_extensions)) {
            set_alert('danger', 'Invalid file type. Allowed: PNG, JPG, JPEG, SVG.');
            redirect(admin_url('logo_settings'));
        }

        if ($file_size > $max_size) {
            set_alert('danger', 'File too large. Max size is 2MB.');
            redirect(admin_url('logo_settings'));
        }

        $path = get_upload_path_by_type('company');

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $logo_filename = unique_filename($path, $file_name);
        $logo_path = $path . $logo_filename;

        if (move_uploaded_file($_FILES['logo_upload']['tmp_name'], $logo_path)) {

            $relative = 'uploads/company/' . $logo_filename;

            // Main option used by your view
            update_option('admin_logo_custom', $relative);

            // Optional role-based logo also saved
            $this->load->model('staff_model');
            $staff = $this->staff_model->get(get_staff_user_id());
            $role_key = strtolower(trim($staff->role ?? 'default'));
            $role_key = str_replace(' ', '_', $role_key);

            update_option('logo_custom_' . $role_key, $relative);

            set_alert('success', 'Logo uploaded successfully.');
        } else {
            set_alert('danger', 'Upload failed. Please check folder permission for uploads/company.');
        }
    } else {
        set_alert('danger', 'Please select a logo file.');
    }

    redirect(admin_url('logo_settings'));
}

public function reset_logo()
{
      if ($this->is_restricted_role()) {
        access_denied('You are not allowed to reset the logo.');
    }
    // Delete logo from options
    delete_option('admin_logo_custom');
    

    $upload_path = get_upload_path_by_type('company');
    $favicon_target = $upload_path . 'favicon.png';


    // Point favicon to a default image
    $default_company_logo = FCPATH . 'assets/images/company-default-logo.png';

    if (file_exists($default_company_logo)) {
        // Copy default company logo as favicon
        copy($default_company_logo, $favicon_target);
        update_option('admin_favicon_custom', 'uploads/company/favicon.png');
    } else {
        // Fallback to default Perfex favicon or remove
        delete_option('admin_favicon_custom');
    }

    
    // NEW: Add cache busting token
    update_option('favicon_cache_bust', uniqid());

    set_alert('success', 'Logo and favicon have been reset to your default company branding.');
    $this->session->set_flashdata('logo_reset_trigger_reload', true);
    redirect(admin_url('logo_settings'));

}




}





