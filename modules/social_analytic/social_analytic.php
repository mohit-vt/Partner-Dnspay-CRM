<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Social Analytics
Description: Advanced social media analytics go beyond basic metrics to provide in-depth insights into audience behavior, campaign performance, and emerging trends, enabling data-driven optimization of social media strategies
Version: 1.0.0
Requires at least: 2.3.*
Author: edatapay
Author URI: https://edatapay.com/
*/

define('SOCIAL_ANALYTIC_MODULE_NAME', 'social_analytic');
define('SOCIAL_ANALYTIC_REVISION', 100);
define('SOCIAL_ANALYTIC_UPLOAD_FOLDER', module_dir_path(SOCIAL_ANALYTIC_MODULE_NAME, 'uploads'));
define('SOCIAL_ANALYTIC_IMPORT_ITEM_ERROR', 'modules/social_analytic/uploads/import_item_error/');

hooks()->add_action('after_cron_run', 'cron_social_analytics');
hooks()->add_action('admin_init', 'social_analytic_module_init_menu_items');
hooks()->add_action('admin_init', 'social_analytic_permissions');
hooks()->add_action('app_admin_head', 'social_analytic_head_components');
hooks()->add_action('app_admin_footer', 'social_analytic_add_footer_components');
hooks()->add_action('admin_navbar_start', 'social_analytic_navbar_components');
hooks()->add_action('customers_navigation_end', 'sa_module_init_client_menu_items');
hooks()->add_action('app_customers_footer', 'sa_client_add_footer_components');
hooks()->add_action('app_customers_head', 'sa_client_add_head_components');
hooks()->add_action('before_customers_area_sub_menu_start', 'sa_customers_area_sub_menu_start');
hooks()->add_action('social_analytic_init',SOCIAL_ANALYTIC_MODULE_NAME.'_appint');
hooks()->add_action('pre_activate_module', SOCIAL_ANALYTIC_MODULE_NAME.'_preactivate');
hooks()->add_action('pre_deactivate_module', SOCIAL_ANALYTIC_MODULE_NAME.'_predeactivate');
/**
 * Register activation module hook
 */
register_activation_hook(SOCIAL_ANALYTIC_MODULE_NAME, 'social_analytic_module_activation_hook');

function social_analytic_module_activation_hook() {
	$CI = &get_instance();
	require_once __DIR__ . '/install.php';
}

/**
* Load the module helper
*/
$CI = & get_instance();
$CI->load->helper(SOCIAL_ANALYTIC_MODULE_NAME . '/social_analytic');

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(SOCIAL_ANALYTIC_MODULE_NAME, [SOCIAL_ANALYTIC_MODULE_NAME]);

/**
 * Init social_analytic module menu items in setup in admin_init hook
 * @return null
 */
function social_analytic_module_init_menu_items() {
	if (has_permission('social_analytic', '', 'view')) {
		$CI = &get_instance();
		$CI->app_menu->add_sidebar_menu_item('social_analytic', [
			'name' => _l('social_analytic'),
			'icon' => 'fa fa-calendar',
			'position' => 30,
		]);

		
		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-analytics',
			'name' => _l('overview_analytics'),
			'icon' => 'fas fa-chart-area',
			'href' => admin_url('social_analytic/analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-facebook-analytics',
			'name' => _l('facebook_analytics'),
			'icon' => 'fab fa-facebook',
			'href' => admin_url('social_analytic/facebook_analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-instagram-analytics',
			'name' => _l('instagram_analytics'),
			'icon' => 'fab fa-instagram',
			'href' => admin_url('social_analytic/instagram_analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-tiktok-analytics',
			'name' => _l('tiktok_analytics'),
			'icon' => 'fa-brands fa-tiktok',
			'href' => admin_url('social_analytic/tiktok_analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-twitter-analytics',
			'name' => _l('twitter_analytics'),
			'icon' => 'fa-brands fa-x',
			'href' => admin_url('social_analytic/twitter_analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-youtube-analytics',
			'name' => _l('youtube_analytics'),
			'icon' => 'fab fa-youtube',
			'href' => admin_url('social_analytic/youtube_analytics'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-workspaces',
			'name' => _l('workspaces'),
			'icon' => 'fas fa-network-wired',
			'href' => admin_url('social_analytic/workspaces'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-social_accounts',
			'name' => _l('social_accounts'),
			'icon' => 'fas fa-pager',
			'href' => admin_url('social_analytic/social_accounts'),
			'position' => 1,
		]);

		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-raw-data',
			'name' => _l('raw_data'),
			'icon' => 'fa fa-list',
			'href' => admin_url('social_analytic/raw_data'),
			'position' => 1,
		]);
 
		
		$CI->app_menu->add_sidebar_children_item('social_analytic', [
			'slug' => 'social-analytic-settings',
			'name' => _l('settings'),
			'icon' => 'fa fa-cog',
			'href' => admin_url('social_analytic/setting'),
			'position' => 2,
		]);
	}
}


/**
 * resource workload permissions
 * @return
 */
function social_analytic_permissions() {
	$capabilities = [];

	$capabilities['capabilities'] = [
		'view' => _l('permission_view') . '(' . _l('permission_global') . ')',
	];

	register_staff_capabilities('social_analytic', $capabilities, _l('social_analytic'));
}

/**
 * add head components
 */
function social_analytic_head_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/social_analytic') === false)) {
		echo '<link href="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/css/sa_custom_style.css') . '?v=' . SOCIAL_ANALYTIC_REVISION . '"  rel="stylesheet" type="text/css" />';
	}
}

/**
 * add footer components
 * @return
 */
function social_analytic_add_footer_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/social_analytic') === false)) {
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/js/sa_main.js') . '?v=' . SOCIAL_ANALYTIC_REVISION . '"></script>';
	}  

	if (!(strpos($viewuri, '/admin/social_analytic/analytics') === false) || !(strpos($viewuri, '/admin/social_analytic/facebook_analytics') === false) || !(strpos($viewuri, '/admin/social_analytic/instagram_analytics') === false) || !(strpos($viewuri, '/admin/social_analytic/tiktok_analytics') === false) || !(strpos($viewuri, '/admin/social_analytic/twitter_analytics') === false) || !(strpos($viewuri, '/admin/social_analytic/youtube_analytics') === false)) {
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/heatmap.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
}


function cron_social_analytics() {
	$CI = &get_instance();

}

function social_analytic_navbar_components() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'admin/social_analytic') === false)) {
		$CI->load->model('social_analytic/social_analytic_model');
	    $staffid = get_staff_user_id();
		if(!is_admin()){
        	$CI->db->where('(super_admin = "'.$staffid.'" OR ' . db_prefix() . 'sa_workspaces.id in (SELECT ' . db_prefix() . 'sa_workspace_members.workspace_id FROM ' . db_prefix() . 'sa_workspace_members WHERE ' . db_prefix() . 'sa_workspace_members.workspace_id = ' . db_prefix() . 'sa_workspaces.id AND type = "staff" AND member_id = "'.$staffid.'"))');
		}
		
        $CI->db->order_by('name', 'asc');
        $workspaces = $CI->db->get(db_prefix() . 'sa_workspaces')->result_array();

		$workspace_id = sa_get_base_workspace_id();

	    echo '<li class="">';
	    echo '<div class="navbar_base_workspace mtop15 min-width-200px">';
	    echo render_select('sa_base_workspace', $workspaces, array('id', 'name'), '', $workspace_id,array(),array(),'','',false);
	    echo '</div>';
	    echo '</li>';
	}  
}

function sa_module_init_client_menu_items()
{
    if(check_workspace_client(get_client_user_id())){
	    $menu = '';
	    if (is_client_logged_in()) {

       $menu .= '<li class=" customers-nav-item">';
          $menu .= ' <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
            aria-expanded="false">'._l('social_analytic');        
          $menu .= '</a>';
          $menu .= ' <ul class="dropdown-menu animated fadeIn client-ul">';
          $menu .=  '<li class="customers-nav-item-analytics">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/overview_analytics').'">'._l('overview').'</a>';
          $menu .=  '</li>';

          $menu .=  '<li class="customers-nav-item-facebook">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/facebook_analytics').'">'._l('facebook').'</a>';
          $menu .=  '</li>';

           $menu .=  '<li class="customers-nav-item-instagram">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/instagram_analytics').'">'._l('instagram').'</a>';
          $menu .=  '</li>';

          $menu .=  '<li class="customers-nav-item-tiktok">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/tiktok_analytics').'">'._l('tiktok').'</a>';
          $menu .=  '</li>';

          $menu .=  '<li class="customers-nav-item-twitter">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/twitter_analytics').'">'._l('twitter').'</a>';
          $menu .=  '</li>';

          $menu .=  '<li class="customers-nav-item-youtube">';
          $menu .= '<a href="'.site_url('social_analytic/social_analytic_client/youtube_analytics').'">'._l('youtube').'</a>';
          $menu .=  '</li>';

          $menu .= '</ul>';

        $menu .= '</li>';
        
	    }
    	echo html_entity_decode($menu);
    }
}

function sa_client_add_head_components()
{
    $CI      = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];

    if (!(strpos($viewuri, 'social_analytic/social_analytic_client') === false)) {
		echo '<link href="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/css/sa_custom_style.css') . '?v=' . SOCIAL_ANALYTIC_REVISION . '"  rel="stylesheet" type="text/css" />';
    }
}


function sa_client_add_footer_components(){
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    if (!(strpos($viewuri, '/social_analytic/social_analytic_client') === false)) {
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/js/clients/sa_client_main.js')  . '?v=' . SOCIAL_ANALYTIC_REVISION . '"></script>';
  	}
    if (!(strpos($viewuri, '/social_analytic/social_analytic_client/overview_analytics') === false) || !(strpos($viewuri, '/social_analytic/social_analytic_client/facebook_analytics') === false) || !(strpos($viewuri, '/social_analytic/social_analytic_client/instagram_analytics') === false) || !(strpos($viewuri, '/social_analytic/social_analytic_client/tiktok_analytics') === false) || !(strpos($viewuri, '/social_analytic/social_analytic_client/twitter_analytics') === false) || !(strpos($viewuri, '/social_analytic/social_analytic_client/youtube_analytics') === false)) {
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/highcharts.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/heatmap.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/variable-pie.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/export-data.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/accessibility.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/modules/exporting.js') . '"></script>';
		echo '<script src="' . module_dir_url(SOCIAL_ANALYTIC_MODULE_NAME, 'assets/plugins/highcharts/highcharts-3d.js') . '"></script>';
	}
}


function sa_customers_area_sub_menu_start() {
	$CI = &get_instance();
	$viewuri = $_SERVER['REQUEST_URI'];
	if (!(strpos($viewuri, 'social_analytic/social_analytic_client') === false)) {
	    $contact_id = get_contact_user_id();
		if(!is_admin()){
        	$CI->db->where('(' . db_prefix() . 'sa_workspaces.id in (SELECT ' . db_prefix() . 'sa_workspace_members.workspace_id FROM ' . db_prefix() . 'sa_workspace_members WHERE ' . db_prefix() . 'sa_workspace_members.workspace_id = ' . db_prefix() . 'sa_workspaces.id AND type = "contact" AND member_id = "'.$contact_id.'"))');
		}
		
        $CI->db->order_by('name', 'asc');
        $workspaces = $CI->db->get(db_prefix() . 'sa_workspaces')->result_array();
		$workspace_id = sa_get_contact_base_workspace_id();

	    echo '<li class="">';
	    echo '<select name="sa_base_workspace" id="sa_base_workspace" class="selectpicker" data-width="100%">';
	    foreach ($workspaces as $key => $value) {
	    	$selected = '';
	    	if ($value['id'] == $workspace_id) {
	    		$selected = 'selected';
	    	}
    		echo '<option value="'.$value['id'].'" '.$selected .'>'.$value['name'].'</option>';
	    }
	    echo '</select>';
	    echo '</li>';
	}  
}

function social_analytic_appint(){
    // $CI = & get_instance();    
    // require_once 'libraries/gtsslib.php';
    // $sa_api = new SocialAnalyticLic();
    // $sa_gtssres = $sa_api->verify_license(true);    
    // if(!$sa_gtssres || ($sa_gtssres && isset($sa_gtssres['status']) && !$sa_gtssres['status'])){
    //      $CI->app_modules->deactivate(SOCIAL_ANALYTIC_MODULE_NAME);
    //     set_alert('danger', "One of your modules failed its verification and got deactivated. Please reactivate or contact support.");
    //     redirect(admin_url('modules'));
    // }    
}

function social_analytic_preactivate($module_name){
    if ($module_name['system_name'] == SOCIAL_ANALYTIC_MODULE_NAME) {             
        // require_once 'libraries/gtsslib.php';
        // $sa_api = new SocialAnalyticLic();
        // $sa_gtssres = $sa_api->verify_license();          
        // if(!$sa_gtssres || ($sa_gtssres && isset($sa_gtssres['status']) && !$sa_gtssres['status'])){
        //      $CI = & get_instance();
        //     $data['submit_url'] = $module_name['system_name'].'/gtsverify/activate'; 
        //     $data['original_url'] = admin_url('modules/activate/'.SOCIAL_ANALYTIC_MODULE_NAME); 
        //     $data['module_name'] = SOCIAL_ANALYTIC_MODULE_NAME; 
        //     $data['title'] = "Module License Activation"; 
        //     echo $CI->load->view($module_name['system_name'].'/activate', $data, true);
        //     exit();
        // }        
    }
}

function social_analytic_predeactivate($module_name){
    if ($module_name['system_name'] == SOCIAL_ANALYTIC_MODULE_NAME) {
        require_once 'libraries/gtsslib.php';
        $sa_api = new SocialAnalyticLic();
        $sa_api->deactivate_license();
    }
}