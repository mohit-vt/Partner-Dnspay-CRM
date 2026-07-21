<?php
defined('BASEPATH') or exit('No direct script access allowed');

hooks()->add_action('admin_auth_init', function () {
    $CI = &get_instance();

    if (!is_staff_logged_in()) {
        return;
    }

    // Skip MFA controller to avoid redirect loops
    $controller = $CI->router->fetch_class();
    if ($controller == 'mfa_auth') {
        return;
    }

    // Force verification before accessing anything else
    if (!$CI->session->userdata('mfa_verified')) {
        $staffid = get_staff_user_id();
        redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/' . $staffid));
    }
});

hooks()->add_action('after_staff_login', function ($staff) {
    $CI = &get_instance();
    $staffData = $CI->staff_model->get($staff->staffid);

    // Skip if already verified this session
    if ($CI->session->userdata('mfa_verified')) {
        return;
    }

    // Force MFA setup if missing
    if (empty($staffData->gg_auth_secret_key) || $staffData->mfa_google_ath_enable != 1) {
        redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/' . $staff->staffid));
    }

    // Force OTP verification if setup exists but not verified
    redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/' . $staff->staffid));
});


