<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Handles upload for workspace logo
 * @param  mixed $id expense id
 * @return void
 */
function handle_workspace_logo($id)
{
    if (isset($_FILES['workspace_logo']['name']) && $_FILES['workspace_logo']['name'] != '' && _perfex_upload_error($_FILES['workspace_logo']['error'])) {
        header('HTTP/1.0 400 Bad error');
        echo _perfex_upload_error($_FILES['workspace_logo']['error']);
        die;
    }
    $path = SOCIAL_ANALYTIC_UPLOAD_FOLDER . '/workspaces/' . $id . '/';
    $CI   = & get_instance();

    if (isset($_FILES['workspace_logo']['name'])) {

        // Get the temp file path
        $tmpFilePath = $_FILES['workspace_logo']['tmp_name'];
        // Make sure we have a filepath
        if (!empty($tmpFilePath) && $tmpFilePath != '') {
            _maybe_create_upload_path($path);
            $filename    = $_FILES['workspace_logo']['name'];
            $newFilePath = $path . $filename;
            // Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                $CI->db->where('id', $id);
                $CI->db->update(db_prefix() . 'sa_workspaces', [
                    'workspace_logo' => $filename,
                ]);
            }
        }
    }
}

/**
 * [workspace_logo_html]
 * @param  [integer] $id       workspace id
 * @param  array  $classes  
 * @param  string $type     
 * @param  array  $img_attrs
 * @return string          
 */
function workspace_logo_html($id, $classes = ['workspace-logo-image'], $type = 'small', $img_attrs = [])
{
    $url = base_url('assets/images/preview-not-available.jpg');

    $id = trim($id);

    $_attributes = '';
    foreach ($img_attrs as $key => $val) {
        $_attributes .= $key . '=' . '"' . e($val) . '" ';
    }

    $blankImageFormatted = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';

    $CI     = & get_instance();
    $result = $CI->app_object_cache->get('workspace-logo-image-data-' . $id);

    if (!$result) {
        $CI->db->select('workspace_logo,name');
        $CI->db->where('id', $id);
        $result = $CI->db->get(db_prefix() . 'sa_workspaces')->row();
        $CI->app_object_cache->add('workspace-logo-image-data-' . $id, $result);
    }

    if (!$result) {
        return $blankImageFormatted;
    }

    if ($result && $result->workspace_logo !== null) {
        $profileImagePath = 'modules/social_analytic/uploads/workspaces/' . $id . '/' . $result->workspace_logo;

        if (file_exists($profileImagePath)) {
            $profile_image = '<img ' . $_attributes . ' src="' . base_url($profileImagePath) . '" class="' . implode(' ', $classes) . '" />';
        } else {
            return $blankImageFormatted;
        }
    } else {
        $profile_image = '<img src="' . $url . '" ' . $_attributes . ' class="' . implode(' ', $classes) . '" />';
    }

    return $profile_image;
}

/**
 * Gets the base workspace identifier.
 *
 * @param        $account_id  The account identifier
 */
function sa_get_base_workspace_id(){

    $staff_id = get_staff_user_id();

    $CI   = & get_instance();
    $CI->db->where('staffid', $staff_id);
    $staff = $CI->db->get(db_prefix().'staff')->row();
    if($staff && is_numeric($staff->base_workspace_id) && $staff->base_workspace_id > 0){

        $CI->db->where('id', $staff->base_workspace_id);
        $workspace = $CI->db->get(db_prefix().'sa_workspaces')->row();
        if($workspace){
            return $staff->base_workspace_id;
        }
    }
    return 0;
}

/**
 * [sa_get_facebook_config]
 * @return [array]
 */
function sa_get_facebook_config(){
    $config = [
          'app_id' => get_option('sa_facebook_app_id'),
          'app_secret' => get_option('sa_facebook_app_secret'),
          'default_graph_version' => get_option('sa_facebook_graph_version'),
        ];

    return $config;
}

/**
 * [sa_get_account_ids_by_base_workspace]
 * @param  [string]  $type      
 * @param  boolean $return_ids
 * @return [array]             
 */
function sa_get_account_ids_by_base_workspace($type, $return_ids = false){
    $CI   = & get_instance();
    $workspace_id = sa_get_base_workspace_id();
    $CI->db->where('workspace_id', $workspace_id);
    $CI->db->where('type', $type);
    $CI->db->where('active', 1);
    $accounts = $CI->db->get(db_prefix().'sa_accounts')->result_array();

    if($accounts){
        if($return_ids){
            $account_ids = [0];
            foreach ($accounts as $key => $value) {
                $account_ids[] = $value['id'];
            }

            return implode(',', $account_ids);
        }
    }
    
    return $accounts;
}

/**
 * [sa_get_tiktok_config]
 * @return [array]
 */
function sa_get_tiktok_config(){
    $config = [
          'client_key' => get_option('sa_tiktok_client_key'),
          'client_secret' => get_option('sa_tiktok_client_secret'),
          'api_domain' => 'https://open.tiktokapis.com/v2',
        ];

    return $config;
}

/**
 * [sa_get_instagram_config]
 * @return [type]
 */
function sa_get_instagram_config(){
    $config = [
          'app_id' => get_option('sa_instagram_app_id'),
          'app_secret' => get_option('sa_instagram_app_secret'),
          'default_graph_version' => get_option('sa_instagram_graph_version'),
          'api_domain' => 'https://graph.instagram.com/',
        ];

    return $config;
}

/**
 * [sa_get_twitter_config]
 * @return [array]
 */
function sa_get_twitter_config(){
    $config = [
          'client_id' => get_option('sa_twitter_client_id'),
          'client_secret' => get_option('sa_twitter_client_secret'),
          'api_domain' => 'https://api.twitter.com',
        ];

    return $config;
}

/**
 * [sa_get_youtube_config]
 * @return [array]
 */
function sa_get_youtube_config(){

    $config = [
          'client_id' => get_option('sa_youtube_client_id'),
          'client_secret' => get_option('sa_youtube_client_secret'),
          'api_domain' => 'https://www.googleapis.com/youtube/v3',
        ];

    return $config;
}

/**
 * [check_raw_data_import ]
 * @param  [type] $account_id account id
 * @param  [type] $date     
 * @param  [type] $type     
 * @return [boolean]         
 */
function check_raw_data_import($account_id, $date, $type){
    $CI   = & get_instance();
    $CI->db->where('account_id', $account_id);
    $CI->db->where('type', $type);
    $CI->db->where('import', 1);
    $CI->db->where('date_format(time, \'%Y-%m-%d\') = "'.$date.'"');
    $account = $CI->db->get(db_prefix().'sa_analytics')->row();
    if($account){
        return true;
    }

    return false;
}

/**
 * [convert_import_data]
 * @param  [string] $value
 * @return [string]       
 */
function convert_import_data($value){
    if($value == '65+'){
        return '65_and_over';
    }

    $value = strtolower($value);
    $value = str_replace(' ', '_', $value);
    $value = str_replace('-', '_', $value);

    return $value;
}

/**
 * [check_workspace_client]
 * @return [boolean]
 */
function check_workspace_client(){
    $CI   = & get_instance();
    $CI->db->where('member_id', get_contact_user_id());
    $CI->db->where('type', 'contact');
    $workspace = $CI->db->get(db_prefix().'sa_workspace_members')->row();
    if($workspace){
        return true;
    }

    return false;
}

/**
 * [sa_get_contact_base_workspace_id]
 * @return [integer]
 */
function sa_get_contact_base_workspace_id(){

    $contact_id = get_contact_user_id();

    $CI   = & get_instance();
    $CI->db->where('id', $contact_id);
    $contact = $CI->db->get(db_prefix().'contacts')->row();
    if($contact && is_numeric($contact->base_workspace_id) && $contact->base_workspace_id > 0){

        $CI->db->where('id', $contact->base_workspace_id);
        $workspace = $CI->db->get(db_prefix().'sa_workspaces')->row();
        if($workspace){
            return $contact->base_workspace_id;
        }
    }

    $CI->db->where('member_id', $contact_id);
    $CI->db->where('type', 'contact');
    $member = $CI->db->get(db_prefix().'sa_workspace_members')->row();
    if($member){
        $CI->load->model('social_analytic/social_analytic_model');
        $CI->social_analytic_model->set_contact_default_workspace($member->workspace_id);
        
        return $member->workspace_id;
    }

    return 0;
}

/**
 * [sa_get_contact_workspace_ids]
 * @param  boolean $return_workspace_list
 * @return [array]                        
 */
function sa_get_contact_workspace_ids($return_workspace_list = false){

    $contact_id = get_contact_user_id();

    $CI   = & get_instance();
    $CI->db->where('member_id', $contact_id);
    $CI->db->where('type', 'contact');
    $members = $CI->db->get(db_prefix().'sa_workspace_members')->result_array();

    $workspace_ids = [0];
    foreach ($members as $key => $value) {
        $workspace_ids[] = $value['workspace_id'];
    }
   
    if($return_workspace_list){
        $CI->db->where('(id in ('. implode(',',$workspace_ids).'))');
        $workspaces = $CI->db->get(db_prefix().'sa_workspaces')->result_array();
        return $workspaces;
    }

    return $workspace_ids;
}

/**
 * [sa_get_contact_account_ids_by_base_workspace]
 * @param  [string]  $type      
 * @param  boolean $return_ids
 * @return [array]             
 */
function sa_get_contact_account_ids_by_base_workspace($type, $return_ids = false){
    $CI   = & get_instance();
    $workspace_id = sa_get_contact_base_workspace_id();
    $CI->db->where('workspace_id', $workspace_id);
    $CI->db->where('type', $type);
    $CI->db->where('active', 1);
    $accounts = $CI->db->get(db_prefix().'sa_accounts')->result_array();

    if($accounts){
        if($return_ids){
            $account_ids = [0];
            foreach ($accounts as $key => $value) {
                $account_ids[] = $value['id'];
            }

            return implode(',', $account_ids);
        }
    }
    
    return $accounts;
}

/**
 * [sa_check_csrf_protection]
 * @return [string]
 */
function sa_check_csrf_protection()
{
    if(config_item('csrf_protection')){
        return 'true';
    }
    return 'false';
}

/**
     * [new_html_entity_decode description]
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
if (!function_exists('new_html_entity_decode')) {
    
    function new_html_entity_decode($str){
        return html_entity_decode($str ?? '');
    }
}
