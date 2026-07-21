<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Sketchboard
Description: Sketchboard is a virtual whiteboard tool that allows users to easily sketch diagrams with a hand-drawn aesthetic.
Version: 1.0.1
Requires at least: 2.3.*
Author: eDatapay  
Author URI: https://edatapay.com/

*/

define('SKETCHBOARD_MODULE_NAME', 'sketchboard');
hooks()->add_action('admin_init', 'sketchboard_init_menu_items');
hooks()->add_action('admin_init', 'sketchboard_permissions');
hooks()->add_action('app_admin_head', 'sketchboard_style_admin_head');
hooks()->add_filter('module_sketchboard_action_links', 'module_sketchboard_action_links');
hooks()->add_filter('before_start_render_content', 'error_render_content');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(SKETCHBOARD_MODULE_NAME, [SKETCHBOARD_MODULE_NAME]);

define('VERSION_SKETCHBOARD', 101);
define('SKETCHBOARD_ITEM_ID', 54202997);


/**
 * Init Sketchboard module menu items in setup in admin_init hook
 * @return null
 */
function sketchboard_init_menu_items()
{  
    $CI = &get_instance();
    if (has_permission('sketchboard', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('sketchboard', [
            'name'     => _l('sketchboard'),
            'icon'     => 'fa fa-cubes',
            'href'     => admin_url('sketchboard'),
            'position' => 30,
        ]);
    }
    if (has_permission('sketchboard', '', 'view')) {
        $CI->app_tabs->add_project_tab('sketchboard_projects', [
            'name'     => _l('sketchboard_projects'),
            'icon'     => 'fa fa-cubes',
            'view'     => 'sketchboard/admin/project_sketchboards',
            'position' => 10,
        ]);
    }
    if (is_admin()) {   
        $CI->app_menu->add_setup_menu_item('sketchboard', [
            'slug' => 'sketchboard-setting',
            'name' => _l('sketchboard_setting'),
            'href' => admin_url('sketchboard/verify'),
            'position' => 35,
        ]);
    }
}

/** 
 * Handle module activation tasks (e.g., database setup)
*/
register_activation_hook(SKETCHBOARD_MODULE_NAME, 'sketchboard_module_activation_hook');

function sketchboard_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

function sketchboard_style_admin_head(){
    echo '<link rel="stylesheet" type="text/css" id="vendor-css" href="'.module_dir_url(SKETCHBOARD_MODULE_NAME,'assets/css/sketchboard.css') . '?v=' . VERSION_SKETCHBOARD.'">
';
}

function error_render_content(){
    if(get_option('sketchboard_purchase_is_valid') != 1){
        echo  '<div class="col-lg-12 mt-tw-mt-1">
            <div class="alert alert-warning" font-medium="">
                <h4>Sketchboard - Please verify your purchase item token before proceeding.</h4>
                <p>
                <a rel="noopener" href="'.admin_url('sketchboard/setting').'">Click here to verify your purchase token</a><p>
            </div>
        </div>';
    }
   
}



function sketchboard_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
            'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
            'create' => _l('permission_create'),
            'edit'   => _l('permission_edit'),
            'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('sketchboard', $capabilities, _l('sketchboard'));
}

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_sketchboard_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('sketchboard/verify') . '">' . _l('verify') . '</a>';

    return $actions;
}