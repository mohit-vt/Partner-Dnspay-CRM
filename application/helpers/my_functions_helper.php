<?php

defined('BASEPATH') or exit('No direct script access allowed');


// Custom redirect after login
if (!function_exists('redirect_after_login')) {
    function redirect_after_login()
    {
        $CI = &get_instance();
        $login_context = $CI->session->userdata('login_context');

        // Fallback: detect based on request URL
        $current_url = uri_string(); // e.g. "reseller/authentication" or "admin/authentication"

        if ($login_context === 'reseller' || strpos($current_url, 'reseller') !== false) {
            redirect(site_url('reseller/dashboard'));
            exit;
        }

        // Default: admin
        redirect(admin_url());
        exit;
    }
}
hooks()->add_filter('admin_header_logo_url', function ($url) {
    $CI = &get_instance();
    $staff_id = get_staff_user_id();

    if (!$staff_id) {
        return $url;
    }

    $CI->load->model('staff_model');
    $role = strtolower(trim($CI->staff_model->get($staff_id)->role ?? ''));

    // Admin and management always get default logo
    if (in_array($role, ['Employee', 'partner admin','Management','reseller'])) {
        return base_url('assets/images/company-default-logo.png');
    }

    // Others get their role-specific logo
    $custom_logo = get_option('logo_custom_' . $role);
    return $custom_logo ? base_url($custom_logo) : $url;
});




?>