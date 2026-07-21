<?php

defined('BASEPATH') or exit('No direct script access allowed');

function _app_init_load()
{
    $ci = &get_instance();

    $ci->load->library([
        'app_modules',
        'app_menu',
        'app_tabs',
        'app_module_migration',
        'assets/app_scripts',
        'assets/app_css',
        'sms/app_sms',
        'mails/app_mail_template',
        'merge_fields/app_merge_fields',
        'app_object_cache',
    ]);
}

function _app_init()
{
    $ci = &get_instance();
  
     // ------------------------------------------------------
    // Affiliate session override (safe)
    // ------------------------------------------------------
    if (strpos($_SERVER['REQUEST_URI'] ?? '', '/affiliate/') !== false) {

        // Fix sessions for affiliate module ONLY
        $ci->config->set_item('sess_driver', 'database');
        $ci->config->set_item('sess_cookie_name', 'affiliate_session');
        $ci->config->set_item('sess_save_path', 'tblsessions_affiliate');

        $ci->config->set_item('cookie_prefix', '');
        $ci->config->set_item('cookie_domain', '.edata24.com');
        $ci->config->set_item('cookie_path', '/');

        $ci->config->set_item('cookie_samesite', 'None');
        $ci->config->set_item('sess_cookie_samesite', 'None');

        $ci->config->set_item('cookie_secure', TRUE);
        $ci->config->set_item('cookie_httponly', TRUE);

        $ci->config->set_item('csrf_cookie_name', 'affiliate_csrf');
        $ci->config->set_item('csrf_cookie_domain', '.edata24.com');
        $ci->config->set_item('csrf_cookie_path', '/');
    }


    _app_init_load();

    /**
     * In case of failures, users can skip the modules to be loaded
     */


    if ($ci->input->get('skip_modules_load') && $ci->input->get('skip_modules_load') && is_admin()) {
        $modules = [];
    } else {
        /**
         * Get all registered and active modules
         * @var array
         */
        $modules = $ci->app_modules->get_activated();
    }

    foreach ($modules as $module) {
        /**
         * Require the init module file
         */
        require_once($module['init_file']);
    }

    $themeFunctionsPath = VIEWPATH . 'themes/' . active_clients_theme() . '/functions.php';

    if (file_exists($themeFunctionsPath)) {
        include_once($themeFunctionsPath);
    }

    hooks()->do_action('modules_loaded');
}
