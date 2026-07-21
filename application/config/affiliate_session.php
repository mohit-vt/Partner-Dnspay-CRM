<?php defined('BASEPATH') or exit('No direct script access allowed');

$config = [
    'sess_driver'             => 'database',
    'sess_cookie_name'        => 'affiliate_session',
    'sess_save_path'          => 'tblsessions_affiliate',
    'sess_expiration'         => 28800,
    'sess_match_ip'           => false,
    'sess_time_to_update'     => 0,
    'sess_regenerate_destroy' => false,

    // Cookies
    'cookie_prefix'           => '',
    'cookie_domain'           => '.edata24.com',
    'cookie_path'             => '/',
    'cookie_secure'           => true,
    'cookie_httponly'         => true,
    'cookie_samesite'         => 'None',
    'sess_cookie_samesite'    => 'None',

    // CSRF (if enabled)
    'csrf_cookie_name'        => 'affiliate_csrf',
    'csrf_cookie_domain'      => '.edata24.com',
    'csrf_cookie_path'        => '/',
];
