<?php

defined('BASEPATH') or exit('No direct script access allowed');
/**
 * This class describes a xero integration.
 */
require 'modules/social_analytic/vendor/autoload.php';

class Social_analytic extends AdminController {
	public function __construct() {
		parent::__construct();
        $this->load->model('social_analytic_model');
        hooks()->do_action('social_analytic_init'); 
            
	}

	/**
     * manage setting
     */
    public function setting()
    {
        $this->check_base_workspace();
    	$data['title'] = _l('setting');

        $data          = [];
        $data['group'] = $this->input->get('group');

        $data['tab'][] = 'facebook';
        $data['tab'][] = 'instagram';
        $data['tab'][] = 'tiktok';
        $data['tab'][] = 'twitter';
        $data['tab'][] = 'youtube';

        $data['tab_2'] = $this->input->get('tab');
        if ($data['group'] == '') {
            $data['group'] = 'facebook';
        }

        $data['title']         = _l('setting');
        $data['tabs']['view'] = 'settings/' . $data['group'];

    	$this->load->view('settings/manage', $data);
    }

    /**
     * [workspaces]
     */
    public function workspaces(){
        
        $data['title']         = _l('workspaces');
        $data['staffs']         = $this->staff_model->get('', ['active' => 1]);
        $this->load->view('workspaces/manage', $data);
    }

    /**
     * [get_facebook_data description]
     * @param  [integer] $id facebook account id
     */
    public function get_facebook_data($id){
        $message = '';
        $success = $this->social_analytic_model->get_facebook_data($id);
        if ($success) {
            $message = _l('updated_successfully', _l('facebook'));
        }

        redirect(admin_url('social_analytic/raw_data_detail/'.$id));
    }

    /**
     * [facebook_callback]
     */
    public function facebook_callback(){
        $config = sa_get_facebook_config();
        $fb = new \Facebook\Facebook($config);
        $oAuth2Client = $fb->getOAuth2Client();
        $helper = $fb->getRedirectLoginHelper();

        try {
            $code = isset($_GET['code']) ? $_GET['code'] : null;
            $accessToken = $oAuth2Client->getAccessTokenFromCode($code, admin_url('social_analytic/facebook_callback'));
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken->getValue());
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($longLivedAccessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        try {
            $data['account_id'] = isset($_GET['state']) ? $_GET['state'] : null;

            $data['access_token'] = $longLivedAccessToken->getValue();
            $data['expires_in'] = time() + $longLivedAccessToken->getExpiresAt();

            $response = $fb->get('/me', $longLivedAccessToken->getValue());
            $data_account = $response->getDecodedBody();
            $data['account'] = $data_account;
            $response = $fb->get('/'.$data_account['id'].'/accounts', $longLivedAccessToken->getValue());
            $data_pages = $response->getDecodedBody();
            $data['pages'] = $data_pages;
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $this->load->view('social_accounts/connects/facebook', $data);
    }

    /**
     * [facebook_reconnect]
     * @param  [integer] $account_id facebook account id
     */
    public function facebook_reconnect($account_id){
        $account = $this->social_analytic_model->get_social_accounts($account_id);
        $config = sa_get_facebook_config();
        $fb = new \Facebook\Facebook($config);

        $oAuth2Client = $fb->getOAuth2Client();
        $permissions = ['pages_show_list', 'pages_read_engagement', 'pages_manage_engagement', 'instagram_basic', 'instagram_manage_comments'];
        $data['connect_url'] = $oAuth2Client->getAuthorizationUrl(admin_url('social_analytic/facebook_reconnect_callback'), $account->page_id, $permissions);

        redirect($data['connect_url']);
    }

    /**
     * [facebook_connect]
     * @param  [integer] $account_id facebook account id
     */
    public function facebook_connect($account_id){
        $config = sa_get_facebook_config();
        $fb = new \Facebook\Facebook($config);

        $oAuth2Client = $fb->getOAuth2Client();
        $permissions = ['pages_show_list', 'pages_read_engagement', 'pages_manage_engagement', 'instagram_basic', 'instagram_manage_comments'];
        $data['connect_url'] = $oAuth2Client->getAuthorizationUrl(admin_url('social_analytic/facebook_callback'), $account_id, $permissions);

        redirect($data['connect_url']);
    }

    /**
     * [facebook_reconnect_callback]
     */
    public function facebook_reconnect_callback(){
        $config = sa_get_facebook_config();
        $fb = new \Facebook\Facebook($config);
        $oAuth2Client = $fb->getOAuth2Client();
        $helper = $fb->getRedirectLoginHelper();
        try {
            $code = isset($_GET['code']) ? $_GET['code'] : null;
            $accessToken = $oAuth2Client->getAccessTokenFromCode($code, admin_url('social_analytic/facebook_reconnect_callback'));
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($accessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        try {
            $page_id = isset($_GET['state']) ? $_GET['state'] : null;

            $data['access_token'] = $accessToken->getValue();
            $response = $fb->get('/me', $accessToken->getValue());
            $data_account = $response->getDecodedBody();
            $data['account'] = $data_account;
            $response = $fb->get('/me/accounts', $accessToken->getValue());
            $data_pages = $response->getDecodedBody();

            foreach ($data_pages['data'] as $key => $value) {
                if($page_id == $value['id']){
                    $this->db->where('page_id', $page_id);
                    $this->db->update(db_prefix().'sa_accounts', ['access_token' => $value['access_token']]);

                    $message = _l('reconnected_successfully');
                    set_alert('success', $message);

                    break;
                }
            }
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        redirect(admin_url('social_analytic/social_accounts?type=facebook'));
    }

    /**
     * [instagram_connect]
     * @param  [integer] $account_id instagram account id
     */
    public function instagram_connect($account_id){
        $config = sa_get_instagram_config();
        $fb = new \Facebook\Facebook($config);

        $oAuth2Client = $fb->getOAuth2Client();
        $permissions = ['pages_show_list', 'pages_read_engagement', 'pages_manage_engagement', 'instagram_basic', 'instagram_manage_comments'];
        $data['connect_url'] = $oAuth2Client->getAuthorizationUrl(admin_url('social_analytic/instagram_callback'), $account_id, $permissions);

        redirect($data['connect_url']);
    }
    /**
     * [instagram_callback]
     */
    public function instagram_callback(){
        $config = sa_get_instagram_config();
        $fb = new \Facebook\Facebook($config);
        $oAuth2Client = $fb->getOAuth2Client();
        $helper = $fb->getRedirectLoginHelper();

        try {
            $code = isset($_GET['code']) ? $_GET['code'] : null;
            $accessToken = $oAuth2Client->getAccessTokenFromCode($code, admin_url('social_analytic/instagram_callback'));
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($accessToken->getValue());
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        if (! isset($longLivedAccessToken)) {
          if ($helper->getError()) {
            header('HTTP/1.0 401 Unauthorized');
            echo "Error: " . $helper->getError() . "\n";
            echo "Error Code: " . $helper->getErrorCode() . "\n";
            echo "Error Reason: " . $helper->getErrorReason() . "\n";
            echo "Error Description: " . $helper->getErrorDescription() . "\n";
          } else {
            header('HTTP/1.0 400 Bad Request');
            echo 'Bad request';
          }
          exit;
        }

        try {
            $data['account_id'] = isset($_GET['state']) ? $_GET['state'] : null;

            $data['access_token'] = $longLivedAccessToken->getValue();
            $data['expires_in'] = time() + $longLivedAccessToken->getExpiresAt();

            $response = $fb->get('/me', $longLivedAccessToken->getValue());
            $data_account = $response->getDecodedBody();
            $data['account'] = $data_account;
            $response = $fb->get('/'.$data_account['id'].'/accounts', $data['access_token']);
            $data_pages = $response->getDecodedBody();
            $data['pages'] = [];

            foreach ($data_pages['data'] as $key => $value) {
                $response = $fb->get('/'.$value['id'].'?fields=instagram_business_account', $data['access_token']);
                $instagram_data = $response->getDecodedBody();

                if(isset($instagram_data['instagram_business_account']['id'])){
                    $response = $fb->get('/'.$instagram_data['instagram_business_account']['id'].'?fields=id,name,username', $data['access_token']);
                    $instagram_data = $response->getDecodedBody();

                    $data['pages'][] = $instagram_data;
                }
            }
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
          // When Graph returns an error
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
          // When validation fails or other local issues
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        }

        $this->load->view('social_accounts/connects/instagram', $data);
    }

    /**
     * [workspace]
     */
   	//  public function workspace(){
    //     $data = $this->input->post();

    //     $message = '';
    //     if($data['id'] == ''){
    //         $success = $this->social_analytic_model->add_workspace($data);
    //         if($success){
    //             handle_workspace_logo($success);
    //             $message = _l('added_successfully', _l('workspace'));
    //             set_alert('success', $message);
    //         }
    //     }else{
            
    //         $id = $data['id'];
    //         unset($data['id']);
    //         $success = $this->social_analytic_model->update_workspace($data, $id);
    //         handle_workspace_logo($id);
    //         if ($success) {
    //             $message = _l('updated_successfully', _l('workspace'));
    //             set_alert('success', $message);
    //         }
    //     }

    //     if($this->input->is_ajax_request()){
    //         echo json_encode(['success' => $success, 'message' => $message]);
    //         die();
    //     }

    //     redirect(admin_url('social_analytic/workspace_detail/'.$id));

    // }
  
  public function workspace()
{
    $data = $this->input->post();
    $message = '';
    $success = false;
    $id = null;

    if (empty($data['id'])) {
        // ADD new workspace
        $success = $this->social_analytic_model->add_workspace($data);
        if ($success) {
            $id = $success; // because add_workspace() returns the insert ID
            handle_workspace_logo($id);
            $message = _l('added_successfully', _l('workspace'));
            set_alert('success', $message);
        }
    } else {
        // UPDATE existing workspace
        $id = $data['id'];
        unset($data['id']);
        $success = $this->social_analytic_model->update_workspace($data, $id);
        handle_workspace_logo($id);
        if ($success) {
            $message = _l('updated_successfully', _l('workspace'));
            set_alert('success', $message);
        }
    }

    // Handle AJAX request separately
    if ($this->input->is_ajax_request()) {
        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }

    // Redirect safely
    if (!empty($id)) {
        redirect(admin_url('social_analytic/workspace_detail/' . $id));
    } else {
        redirect(admin_url('social_analytic/workspaces'));
    }
}


    /**
     * [workspaces_table]
     */
    public function workspaces_table(){

        $this->app->get_table_data(module_views_path('social_analytic', 'workspaces/table_workspaces'));
    }

    /**
     * [set_default_workspace]
     * @param [integer] $workspace_id workspace id
     */
    public function set_default_workspace($workspace_id){
        $message = '';
        $success = $this->social_analytic_model->set_default_workspace($workspace_id);
        if ($success) {
            $message = _l('updated_successfully', _l('workspace'));
        }

        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }


    /**
     * [workspace_detail]
     * @param [integer] $id workspace id
     */
    // public function workspace_detail($id){
        
    //     $data['workspace'] = $this->social_analytic_model->get_workspace($id);
    //     $data['staffs']         = $this->staff_model->get('', ['active' => 1]);
    //     $data['contacts']         = $this->social_analytic_model->get_contacts();
    //     $data['title'] = _l('workspace');

    //     $this->load->view('workspaces/workspace_detail', $data);
    // }
  
  	public function workspace_detail($id = null)
{
    if ($id === null) {
        show_error('Workspace ID is required', 400);
    }

    $data['workspace'] = $this->social_analytic_model->get_workspace($id);
    $data['staffs']    = $this->staff_model->get('', ['active' => 1]);
    $data['contacts']  = $this->social_analytic_model->get_contacts();
    $data['title']     = _l('workspace');

    $this->load->view('workspaces/workspace_detail', $data);
}

    
    /**
     * delete workspace
     * @param  integer $id
     * @return
     */
    public function delete_workspace($id)
    {

        $success = $this->social_analytic_model->delete_workspace($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('workspace'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('social_analytic/workspaces'));
    }

    /**
     * [remove_workspace_logo]
     * @param [integer] $workspace_id workspace id
     */
    public function remove_workspace_logo($workspace_id)
    {
        $workspaceLogoPath = 'modules/social_analytic/uploads/workspaces/' . $workspace_id;

        if (file_exists($workspaceLogoPath)) {
            delete_dir($workspaceLogoPath);
        }

        $this->db->where('id', $workspace_id);
        $this->db->update(db_prefix() . 'sa_workspaces', [
            'workspace_logo' => null,
        ]);

        redirect(admin_url('social_analytic/workspace_detail/' . $workspace_id));
    }

    /**
     * add workspace_member
     * @return json
     */
    public function add_workspace_member(){
        $data = $this->input->post();
        $success = false;
        $message = _l('add_failure');
        if($data['workspace_id'] != ''){
            $success = $this->social_analytic_model->add_workspace_member($data);
            if($success){
                $message = _l('added_successfully', _l('member'));
            }
        }
        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }

    /**
     * [workspace_members_table]
     * @param  string $type
     */
    public function workspace_members_table($type = 'staff'){
        $workspace_id = $this->input->post('workspace_id');

        $this->app->get_table_data(module_views_path('social_analytic', 'workspaces/table_workspace_members'), ['type' => $type, 'workspace_id' => $workspace_id]);
    }

    /**
     * delete workspace member
     * @param  integer $workspace_id
     * @param  integer $member_id
     */
    public function delete_workspace_member($workspace_id, $member_id)
    {
        $success = $this->social_analytic_model->delete_workspace_member($member_id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('workspace_member'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('social_analytic/workspace_detail/'.$workspace_id));
    }

    /**
     * [social_accounts]
     */
    public function social_accounts(){
        $this->check_base_workspace();
        
        $data['type'] = $this->input->get('type');
        $data['tab'][] = 'facebook';
        $data['tab'][] = 'instagram';
        $data['tab'][] = 'tiktok';
        $data['tab'][] = 'twitter';
        $data['tab'][] = 'youtube';
        if ($data['type'] == '') {
            $data['type'] = 'facebook';
        }

        $data['title']         = _l('social_accounts');
        $data['connect_url'] = '';
        $data['check_config'] = 0;

        if($data['type'] == 'facebook'){
            $config = sa_get_facebook_config();
            if($config['app_id'] != '' && $config['app_secret'] != ''){
                $data['check_config'] = 1;
            }
        }elseif ($data['type'] == 'instagram') {
            $config = sa_get_instagram_config();
            if($config['app_id'] != '' && $config['app_secret'] != ''){
                $data['check_config'] = 1;
            }
        }elseif ($data['type'] == 'tiktok') {
            $config = sa_get_tiktok_config();
            if($config['client_key'] != '' && $config['client_secret'] != ''){
                $data['check_config'] = 1;
            }
        }elseif ($data['type'] == 'twitter') {
            $config = sa_get_twitter_config();
            if($config['client_id'] != '' && $config['client_secret'] != ''){
                $data['check_config'] = 1;
            }
        }elseif ($data['type'] == 'youtube') {
            $config = sa_get_youtube_config();
            if($config['client_id'] != '' && $config['client_secret'] != ''){
                $data['check_config'] = 1;
            }
        }

        $data['tabs']['view'] = 'includes/' . $data['type'];
        $this->load->view('social_accounts/manage', $data);
    }

    /**
     * [social_accounts_table]
     */
    public function social_accounts_table(){

        $this->app->get_table_data(module_views_path('social_analytic', 'social_accounts/table_social_accounts'), ['type' => $this->input->post('type')]);
    }

    /**
     * [social_account]
     */
    public function social_account(){
        $data = $this->input->post();
        $message = '';
        if($data['id'] == ''){
            unset($data['id']);

            $success = $this->social_analytic_model->add_social_account($data);
            if($success){
                $message = _l('added_successfully', _l('account'));
            }
        }else{
            $id = $data['id'];
            unset($data['id']);
            $success = $this->social_analytic_model->update_social_account($data, $id);
            if ($success) {
                $message = _l('updated_successfully', _l('account'));
            }
        }

        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }

    /**
     * [analytics description]
     */
    public function analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('overview_analytics');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/manage', $data);
    }

     /**
     * import xlsx analytics
     * @return view
     */
    public function import_xlsx_analytics($id = '') {
        $this->check_base_workspace();

        $this->load->model('staff_model');
        $data_staff = $this->staff_model->get(get_staff_user_id());

        /*get language active*/
        if ($data_staff) {
            if ($data_staff->default_language != '') {
                $data['active_language'] = $data_staff->default_language;

            } else {

                $data['active_language'] = get_option('active_language');
            }

        } else {
            $data['active_language'] = get_option('active_language');
        }
        $data['title'] = _l('import_excel');
        $data['accounts'] = $this->social_analytic_model->get_social_accounts();
        $data['account_id'] = $id;

        $this->load->view('analytics/import_analytics', $data);
    }

    /**
     * import xlsx analytics
     * @return view
     */
    public function import_file_xlsx_analytics(){
        if(!class_exists('XLSXReader_fin')){
            require_once(module_dir_path(SOCIAL_ANALYTIC_MODULE_NAME).'assets/plugins/XLSXReader/XLSXReader.php');
        }
        require_once(module_dir_path(SOCIAL_ANALYTIC_MODULE_NAME).'assets/plugins/XLSXWriter/xlsxwriter.class.php');

        $filename ='';
        if($this->input->post()){
            $data_filter = $this->input->post();
            $account_id = $this->input->post('account_id');
            if (isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {

                // Get the temp file path
                $tmpFilePath = $_FILES['file_csv']['tmp_name'];                
                // Make sure we have a filepath
                if (!empty($tmpFilePath) && $tmpFilePath != '') {
                    $rows          = [];
                    $arr_insert          = [];

                    $tmpDir = TEMP_FOLDER . '/' . time() . uniqid() . '/';

                    if (!file_exists(TEMP_FOLDER)) {
                        mkdir(TEMP_FOLDER, 0755);
                    }

                    if (!file_exists($tmpDir)) {
                        mkdir($tmpDir, 0755);
                    }

                    // Setup our new file path
                    $newFilePath = $tmpDir . $_FILES['file_csv']['name'];                    

                    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
                        //Writer file
                        $writer_header = array(
                            _l('Time')     =>'string',
                            _l('Type')    =>'string',
                            _l('Value')      =>'string',
                            _l('sa_gender')     =>'string',
                            _l('sa_age')     =>'string',
                            _l('language')     =>'string',
                            _l('country')     =>'string',
                            _l('sa_reaction')     =>'string',
                            _l('sa_post_type')     =>'string',
                            _l('sa_stories_performance')     =>'string',
                            _l('sa_engagement')     =>'string',
                            _l('sa_subscriber')     =>'string',
                            _l('device')     =>'string',
                            _l('status')     =>'string',
                            _l('error')       =>'string',
                        );

                        $rowstyle[] =array('widths'=>[10,20,30,40]);

                        if($_FILES['file_csv']['type'] == 'text/csv'){
                            $fd   = fopen($newFilePath, 'r');
                            $data = [];
                            while ($row = fgetcsv($fd)) {
                                $data[] = $row;
                            }

                            fclose($fd);
                        }else{
                            $writer = new XLSXWriter();
                            $writer->writeSheetHeader('Sheet1', $writer_header,  $col_options = ['widths'=>[40,40,40,40,50,50]]);

                            //Reader file
                            $xlsx = new XLSXReader_fin($newFilePath);
                            $sheetNames = $xlsx->getSheetNames();
                            $data = $xlsx->getSheetData($sheetNames[1]);
                        }


                        $arr_header = [];

                        $arr_header['time'] = 0;
                        $arr_header['type'] = 1;
                        $arr_header['value'] = 2;
                        $arr_header['gender'] = 3;
                        $arr_header['age'] = 4;
                        $arr_header['language'] = 5;
                        $arr_header['country'] = 6;
                        $arr_header['reaction'] = 7;
                        $arr_header['post_type'] = 8;
                        $arr_header['stories_performance'] = 9;
                        $arr_header['engagement'] = 10;
                        $arr_header['subscriber'] = 11;
                        $arr_header['device'] = 12;
                        $arr_header['status'] = 13;

                        $total_rows = 0;
                        $total_row_false    = 0; 

                        for ($row = 1; $row < count($data); $row++) {

                            $total_rows++;

                            $rd = array();
                            $flag = 0;
                            $flag2 = 0;

                            $string_error ='';
                            $flag_position_group;
                            $flag_department = null;

                            $value_time  = isset($data[$row][$arr_header['time']]) ? $data[$row][$arr_header['time']] : '' ;
                            $value_type   = isset($data[$row][$arr_header['type']]) ? $data[$row][$arr_header['type']] : '' ;
                            $value_value     = isset($data[$row][$arr_header['value']]) ? $data[$row][$arr_header['value']] : '' ;
                            $value_gender    = isset($data[$row][$arr_header['gender']]) ? $data[$row][$arr_header['gender']] : '' ;
                            $value_age    = isset($data[$row][$arr_header['age']]) ? $data[$row][$arr_header['age']] : '' ;
                            $value_language    = isset($data[$row][$arr_header['language']]) ? $data[$row][$arr_header['language']] : '' ;
                            $value_country    = isset($data[$row][$arr_header['country']]) ? $data[$row][$arr_header['country']] : '' ;
                            $value_reaction    = isset($data[$row][$arr_header['reaction']]) ? $data[$row][$arr_header['reaction']] : '' ;
                            $value_post_type    = isset($data[$row][$arr_header['post_type']]) ? $data[$row][$arr_header['post_type']] : '' ;
                            $value_stories_performance    = isset($data[$row][$arr_header['stories_performance']]) ? $data[$row][$arr_header['stories_performance']] : '' ;
                            $value_engagement    = isset($data[$row][$arr_header['engagement']]) ? $data[$row][$arr_header['engagement']] : '' ;
                            $value_subscriber    = isset($data[$row][$arr_header['subscriber']]) ? $data[$row][$arr_header['subscriber']] : '' ;
                            $value_device    = isset($data[$row][$arr_header['device']]) ? $data[$row][$arr_header['device']] : '' ;
                            $value_status    = isset($data[$row][$arr_header['status']]) ? $data[$row][$arr_header['status']] : '' ;
                            
                            $reg_day = '/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/'; /*yyyy-mm-dd*/

                            if (is_null($value_time) == true) {
                                $string_error .= _l('Time') . _l('not_yet_entered');
                                $flag = 1;
                            }

                            if (is_null($value_type) == true) {
                                $string_error .= _l('Type') . _l('not_yet_entered');
                                $flag = 1;
                            }

                            if (is_null($value_value) == true) {
                                $string_error .= _l('Value') . _l('not_yet_entered');
                                $flag = 1;
                            }


                            if(($flag == 1) || $flag2 == 1 ){
                                //write error file
                                $writer->writeSheetRow('Sheet1', [
                                    $value_time,
                                    $value_type,
                                    $value_value,
                                    $value_gender,
                                    $value_age,
                                    $value_language,
                                    $value_country,
                                    $value_reaction,
                                    $value_post_type,
                                    $value_stories_performance,
                                    $value_engagement,
                                    $value_subscriber,
                                    $value_device,
                                    $value_status,
                                    $string_error,
                                ]);

                                $total_row_false++;
                            }

                            if($flag == 0 && $flag2 == 0){
                                $value_type = convert_import_data($value_type);
                                $value_gender = convert_import_data($value_gender);
                                $value_age = convert_import_data($value_age);
                                $value_reaction = convert_import_data($value_reaction);
                                $value_post_type = convert_import_data($value_post_type);
                                $value_stories_performance = convert_import_data($value_stories_performance);
                                $value_engagement = convert_import_data($value_engagement);
                                $value_subscriber = convert_import_data($value_subscriber);
                                $value_device = convert_import_data($value_device);
                                $value_status = convert_import_data($value_status);

                                $rd['time']       = $value_time;
                                $rd['type']         = $value_type;
                                $rd['value']        = $value_value;
                                $rd['account_id']       = $account_id;
                                $rd['gender']       = $value_gender;
                                $rd['age']       = $value_age;
                                $rd['language']       = $value_language;
                                $rd['country']       = $value_country;
                                $rd['reaction']       =  $value_reaction;
                                $rd['post_type']       = $value_post_type;
                                $rd['stories_performance']       = $value_stories_performance;
                                $rd['engagement']       = $value_engagement;
                                $rd['subscriber']       = $value_subscriber;
                                $rd['device']       = $value_device;
                                $rd['is_subscriber']       = $value_status;
                                
                                $rd['import']       = 1;
                                $rd['dateadded']               = date('Y-m-d H:i:s');
                                $rd['addedfrom']               = get_staff_user_id();

                                $rows[] = $rd;
                                array_push($arr_insert, $rd);

                            }

                        }

                        //insert batch
                        if(count($arr_insert) > 0){
                            $this->db->insert_batch(db_prefix().'sa_analytics', $arr_insert);
                        }

                        $total_rows = $total_rows;
                        $total_row_success = isset($rows) ? count($rows) : 0;
                        $dataerror = '';
                        $message ='Not enought rows for importing';

                        if($total_row_false != 0){
                            $filename = 'Import_analytic_error_'.get_staff_user_id().'_'.strtotime(date('Y-m-d H:i:s')).'.xlsx';
                            $writer->writeToFile(str_replace($filename, SOCIAL_ANALYTIC_IMPORT_ITEM_ERROR.$filename, $filename));
                        }


                    }
                }
            }
        }


        if (file_exists($newFilePath)) {
            @unlink($newFilePath);
        }

        echo json_encode([
            'message'           => $message,
            'total_row_success' => $total_row_success,
            'total_row_false'   => $total_row_false,
            'total_rows'        => $total_rows,
            'site_url'          => site_url(),
            'staff_id'          => get_staff_user_id(),
            'filename'          => SOCIAL_ANALYTIC_IMPORT_ITEM_ERROR.$filename,
        ]);
    }

    /**
     * get data dashboard
     * @return json
     */
    public function get_data_analytics(){
        $data_filter = $this->input->post();
        $data = [];
        switch ($data_filter['type']) {
            case 'engagement_vs_total_posts_published':
                $data['engagement_vs_total_posts_published'] = $this->social_analytic_model->get_data_engagement_vs_total_posts_published($data_filter);
                echo json_encode($data);
                break;
            case 'daily_post_impression_trend':
                $data['daily_post_impression_trend'] = $this->social_analytic_model->get_data_daily_post_impression_trend_chart($data_filter);
                echo json_encode($data);
                break;
            case 'audience_growth':
                $data['audience_growth'] = $this->social_analytic_model->get_data_audience_growth_chart($data_filter);
                echo json_encode($data);
                break;
            case 'published_posts_with_engagement':
                $data['published_posts_with_engagement'] = $this->social_analytic_model->get_data_published_posts_with_engagement($data_filter);
                echo json_encode($data);
                break;
            case 'post_rate':
                $data['post_rate'] = $this->social_analytic_model->get_data_post_rate($data_filter);
                echo json_encode($data);
                break;
            case 'post_density_daily':
                $data['post_density_daily'] = $this->social_analytic_model->get_data_post_density_daily($data_filter);
                echo json_encode($data);
                break;
            case 'engagement_rate':
                $data['engagement_rate'] = $this->social_analytic_model->get_data_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'top_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $data_filter['account_ids'] = $account_ids;
                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;

                $this->load->view('analytics/'.$data_filter['social'].'/top_stats', $data_filter);
                break;
            case 'post_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] = $this->load->view('analytics/'.$data_filter['social'].'/post_stats', $data_filter, true);
                if($data_filter['social'] == 'facebook'){
                    $data['post_by_type_chart'] = $this->social_analytic_model->get_data_post_by_type_chart($data_filter);
                }
                echo json_encode($data);
                break;
            case 'engagement_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/engagement_stats', $data_filter, true);
                
                if($data_filter['social'] == 'twitter'){

                $data['engagement_rate_pie_chart'] = $this->social_analytic_model->get_data_twitter_engagement_rate_pie_chart($data_filter);
                }else{

                $data['engagement_rate_pie_chart'] = $this->social_analytic_model->get_data_engagement_rate_pie_chart($data_filter);
                }
                
                echo json_encode($data);
                break;
            case 'fan_by_age':
                $data['fan_by_age'] = $this->social_analytic_model->get_data_fan_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'fan_by_gender':
                $data['fan_by_gender'] = $this->social_analytic_model->get_data_fan_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'fan_stats':
                $data = $this->social_analytic_model->get_data_fan_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/fan_stats', $data);
                break;
            case 'reactions_overview':
                $data['reactions_overview'] = $this->social_analytic_model->get_data_reactions_overview_chart($data_filter);
                echo json_encode($data);
                break;
            case 'engagement_by_day_time':
                $data['engagement_by_day_time'] = $this->social_analytic_model->get_data_engagement_by_day_time_chart($data_filter);
                echo json_encode($data);
                break;
            case 'publishing_behavior':
                $data['publishing_behavior'] = $this->social_analytic_model->get_data_publishing_behavior_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_hours':
                $data['active_users_by_hours'] = $this->social_analytic_model->get_data_active_users_by_hours_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_days':
                $data['active_users_by_days'] = $this->social_analytic_model->get_data_active_users_by_days_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_impressions':
                $data['instagram_impressions'] = $this->social_analytic_model->get_data_instagram_impressions_chart($data_filter);
                echo json_encode($data);
                break;
            case 'impressions_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $this->load->view('analytics/'.$data_filter['social'].'/impressions_stats', $data_filter);
                break;
            case 'instagram_engagement':
                $data['instagram_engagement'] = $this->social_analytic_model->get_data_instagram_engagement_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_stories_performance':
                $data['instagram_stories_performance'] = $this->social_analytic_model->get_data_instagram_stories_performance_chart($data_filter);
                echo json_encode($data);
                break;
            case 'stories_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $this->load->view('analytics/'.$data_filter['social'].'/stories_stats', $data_filter);
                break;
            case 'stories_performance':
                $data['stories_performance'] = $this->social_analytic_model->get_data_stories_performance_chart($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_engagement_rate':
                $data['twitter_engagement_rate'] = $this->social_analytic_model->get_data_twitter_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_audience_growth':
                $data['twitter_audience_growth'] = $this->social_analytic_model->get_data_twitter_audience_growth_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follow_stats':
                $where = $this->social_analytic_model->get_where_report_period('date_format(time, \'%Y-%m-%d\')');
                $account_ids = [0];
                foreach ($data_filter as $key => $value) {
                    if(!(strpos($key, 'account_id') === false)){
                        $account_ids[] = $value;
                    }
                }

                $data['follow_rate_pie_chart'] = $this->social_analytic_model->get_data_follow_rate_pie_chart($data_filter);
                $data_filter['account_ids'] = $account_ids;
                $account_ids = implode(',', $account_ids);
                $where .= ' AND account_id in ('.$account_ids.')';
                $data_filter['where'] = $where;
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/follow_stats', $data_filter, true);
                
                echo json_encode($data);
                break;
            case 'awareness_through_mention':
                $data['awareness_through_mention'] = $this->social_analytic_model->get_data_awareness_through_mention_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_chart':
                $data['follower_chart'] = $this->social_analytic_model->get_data_follower_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_follow_stats':
                
                $data_stats = $this->social_analytic_model->get_data_follower_stats($data_filter);
                $data['content_html'] =  $this->load->view('analytics/'.$data_filter['social'].'/follow_stats', $data_stats, true);
                
                $data['follower_gender_pie_chart'] = $this->social_analytic_model->get_data_tiktok_follower_gender_pie_chart($data_filter);
                
                echo json_encode($data);
                break;
            case 'video_views_chart':
                $data['video_views_chart'] = $this->social_analytic_model->get_data_video_views_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_engagement_rate':
                $data['tiktok_engagement_rate'] = $this->social_analytic_model->get_data_tiktok_engagement_rate($data_filter);
                echo json_encode($data);
                break;
            case 'posting_pattern_engagement_analysis':
                $data['posting_pattern_engagement_analysis'] = $this->social_analytic_model->get_data_posting_pattern_engagement_analysis($data_filter);
                echo json_encode($data);
                break;
            case 'gained_lost_chart':
                $data['gained_lost_chart'] = $this->social_analytic_model->get_data_gained_lost_chart($data_filter);
                echo json_encode($data);
                break;
            case 'daily_video_views_graph':
                $data['daily_video_views_graph'] = $this->social_analytic_model->get_data_daily_video_views_graph($data_filter);
                echo json_encode($data);
                break;
            case 'estimated_minutes_watched':
                $data['estimated_minutes_watched'] = $this->social_analytic_model->get_data_estimated_minutes_watched($data_filter);
                echo json_encode($data);
                break;
            case 'videos_published':
                $data['videos_published'] = $this->social_analytic_model->get_data_videos_published($data_filter);
                echo json_encode($data);
                break;
            case 'like_vs_dislike_chart':
                $data['like_vs_dislike_chart'] = $this->social_analytic_model->get_data_like_vs_dislike_chart($data_filter);
                echo json_encode($data);
                break;
            case 'comments_chart':
                $data['comments_chart'] = $this->social_analytic_model->get_data_comments_chart($data_filter);
                echo json_encode($data);
                break;
            case 'shares_chart':
                $data['shares_chart'] = $this->social_analytic_model->get_data_shares_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscribers_by_age':
                $data['subscribers_by_age'] = $this->social_analytic_model->get_data_subscriber_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscribers_by_gender':
                $data['subscribers_by_gender'] = $this->social_analytic_model->get_data_subscriber_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'subscriber_stats':
                $data = $this->social_analytic_model->get_data_subscriber_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/subscriber_stats', $data);
                break;
            case 'viewer_stats':
                $data = $this->social_analytic_model->get_data_view_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/viewer_stats', $data);
                break;
            case 'viewer_by_age':
                $data['viewer_by_age'] = $this->social_analytic_model->get_data_viewer_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'viewer_by_gender':
                $data['viewer_by_gender'] = $this->social_analytic_model->get_data_viewer_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'viewer_by_device':
                $data['viewer_by_device'] = $this->social_analytic_model->get_data_viewer_by_device_chart($data_filter);
                echo json_encode($data);
                break;
            case 'active_users_by_day':
                $data['active_users_by_day'] = $this->social_analytic_model->get_data_active_users_by_day($data_filter);
                echo json_encode($data);
                break;
            case 'follower_by_age':
                $data['follower_by_age'] = $this->social_analytic_model->get_data_follower_by_age_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_by_gender':
                $data['follower_by_gender'] = $this->social_analytic_model->get_data_follower_by_gender_chart($data_filter);
                echo json_encode($data);
                break;
            case 'follower_stats':
                $data = $this->social_analytic_model->get_data_follower_stats($data_filter);
                $this->load->view('analytics/'.$data_filter['social'].'/follower_stats', $data);
                break;
            case 'daily_video_views_chart':
                $data['daily_video_views_chart'] = $this->social_analytic_model->get_data_daily_video_views_chart($data_filter);
                echo json_encode($data);
                break;
            case 'account_performance':
                $data['account_performance'] = $this->social_analytic_model->get_data_account_performance($data_filter);
                echo json_encode($data);
                break;
            case 'twitter_engagement':
                $data['twitter_engagement'] = $this->social_analytic_model->get_data_twitter_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'facebook_engagement':
                $data['facebook_engagement'] = $this->social_analytic_model->get_data_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'tiktok_engagement':
                $data['tiktok_engagement'] = $this->social_analytic_model->get_data_tiktok_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'youtube_engagement':
                $data['youtube_engagement'] = $this->social_analytic_model->get_data_youtube_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            case 'instagram_engagement_pie_chart':
                $data['instagram_engagement'] = $this->social_analytic_model->get_data_instagram_engagement_rate_pie_chart($data_filter);
                echo json_encode($data);
                break;
            default:
                // code...
                break;
        }

    }

    /**
     * [facebook_analytics]
     */
    public function facebook_analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('facebook');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/groups/facebook', $data);
    }

    /**
     * [check_base_workspace]
     */
    public function check_base_workspace(){
        if(sa_get_base_workspace_id() == 0){
            set_alert('warning', _l('please_set_default_workspace'));
            redirect(admin_url('social_analytic/workspaces'));
        }
    }

    /**
     * [raw_data raw_data]
     */
    public function raw_data(){
        $this->check_base_workspace();
        $data['type'] = $this->input->get('type');
        $data['tab'][] = 'facebook';
        $data['tab'][] = 'instagram';
        $data['tab'][] = 'tiktok';
        $data['tab'][] = 'twitter';
        $data['tab'][] = 'youtube';
        if ($data['type'] == '') {
            $data['type'] = 'facebook';
        }

        $data['title']         = _l('raw_data');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);

        $data['tabs']['view'] = 'includes/' . $data['type'];

        $this->load->view('raw_data/manage', $data);
    }
    
    /**
     * [raw_data_table ]
     */
    public function raw_data_table(){

        $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_raw_data'), ['type' => $this->input->post('type')]);
    }

    /**
     * [raw_data_detail_table]
     */
    public function raw_data_detail_table(){

        switch ($this->input->post('social')) {
            case 'tiktok':
                $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_tiktok_raw_data'), ['lock' => $this->input->post('lock'), 'account' => $this->input->post('account'), 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date')]);
                break;
            case 'instagram':
                $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_instagram_raw_data'), ['lock' => $this->input->post('lock'), 'account' => $this->input->post('account'), 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date')]);
                break;
            case 'twitter':
                $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_twitter_raw_data'), ['lock' => $this->input->post('lock'), 'account' => $this->input->post('account'), 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date')]);
                break;
            case 'youtube':
                $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_youtube_raw_data'), ['lock' => $this->input->post('lock'), 'account' => $this->input->post('account'), 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date')]);
                break;
            default:
                $this->app->get_table_data(module_views_path('social_analytic', 'raw_data/table_raw_data_detail'), ['lock' => $this->input->post('lock'), 'account' => $this->input->post('account'), 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date')]);
                break;
        }
    }

    /**
     * [raw_data_detail]
     * @param  [integer] $id social account id
     */
    public function raw_data_detail($id){
        $data['title']         = _l('raw_data_detail');
        $data['account'] = $this->social_analytic_model->get_social_accounts($id);

        $this->load->view('raw_data/groups/'.$data['account']->type, $data);
    }

    /**
     * [raw_data_update]
     */
    public function raw_data_update(){
        $data = $this->input->post();

        $message = '';
        $success = $this->social_analytic_model->raw_data_update($data);
        if ($success) {
            $message = _l('updated_successfully', _l('raw_data'));
        }

        echo json_encode(['success' => $success, 'message' => $message]);
        die();
    }
    
    /**
     * [instagram_analytics]
     */
    public function instagram_analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('instagram');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/groups/instagram', $data);
    }

    /**
     * [tiktok_analytics]
     */
    public function tiktok_analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('tiktok');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/groups/tiktok', $data);
    }

    /**
     * [twitter_analytics]
     */
    public function twitter_analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('twitter');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/groups/twitter', $data);
    }

    /**
     * [youtube_analytics]
     */
    public function youtube_analytics(){
        $this->check_base_workspace();
        
        $data['title']         = _l('youtube');
        $data['clients']         = $this->clients_model->get('', [db_prefix().'clients.active' => 1]);
        $this->load->view('analytics/groups/youtube', $data);
    }

    /**
     * [update_setting]
     */
    public function update_setting(){
        $data = $this->input->post();
        $type = $data['type'];
        unset($data['type']);
        $success = $this->social_analytic_model->update_facebook_setting($data);
        if($success == true){
            $message = _l('updated_successfully', _l('setting'));
            set_alert('success', $message);
        }
        redirect(admin_url('social_analytic/setting?group='.$type));
    }

    /**
     * [delete_social_account]
     * @param  [integer] $id social account id
     */
    public function delete_social_account($id)
    {
        $success = $this->social_analytic_model->delete_social_account($id);
        $message = '';
        if ($success) {
            $message = _l('deleted', _l('social_account'));
            set_alert('success', $message);
        } else {
            $message = _l('can_not_delete');
            set_alert('warning', $message);
        }

        redirect(admin_url('social_analytic/social_accounts'));
    }

    /**
     * [tiktok_callback]
     */
    public function tiktok_callback(){
        $config = sa_get_tiktok_config();
        $code = $this->input->get("code") ?? '';
        $account_id = $this->input->get("state") ?? '';

        if ($code != '')
        {   
            $parameters = array(
              'code' => $code,
              'grant_type' => 'authorization_code',
              'redirect_uri' => admin_url('social_analytic/tiktok_callback'),
              'client_key' => $config['client_key'],
              'client_secret' => $config['client_secret'],
              'code' => $code,
            );

            $http_header = array(
                 'Accept' => 'application/json',
                 'Content-Type' => 'application/x-www-form-urlencoded',
            );

            $url = 'https://open.tiktokapis.com/v2/oauth/token/';

            $token_result = $this->social_analytic_model->executeRequest($url,  $parameters, $http_header, 'POST');

            $data = [];
            if($token_result){
                $data['status'] = 1;
                $data['refresh_token'] = $token_result['refresh_token'];
                $data['access_token'] = $token_result['access_token'];
                $data['expires_in'] = time() + $token_result['expires_in'];
                
                $header = array(
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer '. $token_result['access_token']);

                $user_url = $config['api_domain'].'/user/info/?fields=open_id,union_id,avatar_url_100,display_name,follower_count,following_count,likes_count,video_count';

                $user_result = $this->social_analytic_model->callAPI($user_url,  [], $header, 'GET');
                $data['page_id'] = $user_result['data']['user']['union_id'];
                $data['user_id'] = $user_result['data']['user']['union_id'];
                $data['avatar_url'] = $user_result['data']['user']['avatar_url_100'];

                $this->social_analytic_model->account_connect_save($data, $account_id);
                $message = _l('added_successfully', _l('account'));
                set_alert('success', $message);
            }
        }

        redirect(admin_url('social_analytic/social_accounts?type=tiktok'));
    }

    /**
     * [get_tiktok_data]
     * @param  [integer] $id tiktok account id
     */
    public function get_tiktok_data($id){
        $message = '';
        $success = $this->social_analytic_model->get_tiktok_data($id);
        if ($success) {
            $message = _l('updated_successfully', _l('tiktok'));
        }

        redirect(admin_url('social_analytic/raw_data_detail/'.$id));
    }

    /**
     * [twitter_callback]
     */
    public function twitter_callback(){
        $config = sa_get_twitter_config();
        $code = $this->input->get("code") ?? '';
        $account_id = $this->input->get("state") ?? '';

        if ($code != '')
        {   
            $parameters = array(
              'code' => $code,
              'grant_type' => 'authorization_code',
              'redirect_uri' => admin_url('social_analytic/twitter_callback'),
              'client_id' => $config['client_id'],
              'code_verifier' => 'challenge'
            );

            $http_header = array(
                 'Accept' => 'application/json',
                 'Content-Type' => 'application/x-www-form-urlencoded',
                 'Authorization' => 'Basic AAAAAAAAAAAAAAAAAAAAAIeZwgEAAAAAo2R8WYXWalocy2B2X3KOnZXc03E%3DdnSYJOkF50vFkvZUI5t8cHneRyv9ZdHgKqvjmYZr5xtCHDrFRk'
            );

            $url = $config['api_domain'].'/2/oauth2/token';
            $token_result = $this->social_analytic_model->executeRequest($url,  $parameters, $http_header, 'POST');
            $data = [];
            if($token_result){
                $data['access_token'] = $token_result['access_token'];
                $data['expires_in'] = time() + $token_result['expires_in'];
                $data['refresh_token'] = $token_result['refresh_token'];
                
                $header = array(
                'Authorization: Bearer '. $token_result['access_token']);

                $user_url = $config['api_domain'].'/2/users/me';
                $user_result = $this->social_analytic_model->callAPI($user_url,  [], $header, 'GET');
                $data['page_id'] = $user_result['data']['id'];
                $data['user_id'] = $user_result['data']['id'];
                $data['status'] = 1;

                $this->social_analytic_model->account_connect_save($data, $account_id);
                $message = _l('added_successfully', _l('account'));
                set_alert('success', $message);
            }
        }

        redirect(admin_url('social_analytic/social_accounts?type=twitter'));
    }

    /**
     * [change_account_active]
     * @param  [integer] $id     account id
     * @param  [string] $status 
     */
    public function change_account_active($id, $status)
    {
        if ($this->input->is_ajax_request()) {
            $this->social_analytic_model->change_account_active($id, $status);
        }
    }

    /**
     * [workspace_display_chart]
     */
    public function workspace_display_chart(){
        $data = $this->input->post();

        $id = '';
        $message = '';
        if($data['id'] != ''){
            $id = $data['id'];
            unset($data['id']);
            $success = $this->social_analytic_model->update_workspace_display_chart($data, $id);
            if ($success) {
                $message = _l('updated_successfully', _l('display_charts'));
                set_alert('success', $message);
            }
        }

        redirect(admin_url('social_analytic/workspace_detail/'.$id));
    }

    /**
     * [facebook_connect_save]
     */
    public function facebook_connect_save(){
        $data = $this->input->post();
        $success = $this->social_analytic_model->facebook_connect_save($data);
        if ($success) {
            $message = _l('connected_successfully', _l('account'));
            set_alert('success', $message);
        }
        redirect(admin_url('social_analytic/social_accounts'));
    }

    /**
     * [tiktok_connect]
     * @param  [integer] $account_id tiktok account id
     */
    public function tiktok_connect($account_id){
        $config = sa_get_tiktok_config();

        $data['connect_url'] = 'https://www.tiktok.com/v2/auth/authorize?client_key='.$config['client_key'].'&response_type=code&scope=user.info.basic,user.info.stats&redirect_uri='.admin_url('social_analytic/tiktok_callback').'&state='.$account_id;

        redirect($data['connect_url']);
    }

    /**
     * [twitter_connect ]
     * @param  [type] $account_id tiktok account id
     */
    public function twitter_connect($account_id){
        $config = sa_get_twitter_config();
        $data['connect_url'] = 'https://twitter.com/i/oauth2/authorize?response_type=code&client_id='.$config['client_id'].'&redirect_uri='.admin_url('social_analytic/twitter_callback').'&scope=offline.access%20tweet.read%20users.read%20follows.read%20space.read%20like.read%20list.read%20block.read%20bookmark.read&state='.$account_id.'&code_challenge=challenge&code_challenge_method=plain';
        redirect($data['connect_url']);
    }

    /**
     * [get_twitter_data ]
     * @param  [type] $id tiktok account id
     */
    public function get_twitter_data($id){
        $message = '';
        $success = $this->social_analytic_model->get_twitter_data($id);
        if ($success) {
            $message = _l('updated_successfully', _l('twitter'));
        }

        redirect(admin_url('social_analytic/raw_data_detail/'.$id));
    }

    /**
     * [youtube_connect]
     * @param  [type] $account_id youtube account id
     */
    public function youtube_connect($account_id){
        $config = sa_get_youtube_config();
        $client = new Google_Client();
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri(admin_url('social_analytic/youtube_callback'));
        $client->setState($account_id);
        $client->setAccessType("offline");
        $client->setPrompt("consent");
        $client->addScope("email");
        $client->addScope("profile");
        $client->addScope("https://www.googleapis.com/auth/youtube.readonly");
        $data['connect_url'] = $client->createAuthUrl();
        redirect($data['connect_url']);
    }

    /**
     * [youtube_callback]
     */
    public function youtube_callback(){
        $config = sa_get_youtube_config();
        $client = new Google_Client();
        $client->setClientId($config['client_id']);
        $client->setClientSecret($config['client_secret']);
        $client->setRedirectUri(admin_url('social_analytic/youtube_callback'));
        $code = $this->input->get("code") ?? '';
        $account_id = $this->input->get("state") ?? '';

        if ($code != '')
        {   
            $token_result = $client->fetchAccessTokenWithAuthCode($_GET['code']);
            if($token_result){
                $data['access_token'] = $token_result['access_token'];
                $data['refresh_token'] = $token_result['refresh_token'];
                $data['expires_in'] = time() + $token_result['expires_in'];
                

                $client->setAccessToken($token_result['access_token']);
                $youtube = new Google_Service_YouTube($client);
                $response = $youtube->channels->listChannels('snippet,statistics', ['mine' => true]);

                $data['channels'] = $response->getItems();
                $data['account_id'] = $account_id;
            }
        } 

        $this->load->view('social_accounts/connects/youtube', $data);
    }

    /**
     * [youtube_connect_save]
     */
    public function youtube_connect_save(){
        $data = $this->input->post();
        $success = $this->social_analytic_model->youtube_connect_save($data);
        if ($success) {
            $message = _l('connected_successfully', _l('account'));
            set_alert('success', $message);
        }
        redirect(admin_url('social_analytic/social_accounts?type=youtube'));
    }

    /**
     * [get_youtube_data]
     * @param  [integer] $id youtube account id
     */
    public function get_youtube_data($id){
        $message = '';
        $success = $this->social_analytic_model->get_youtube_data($id);
        if ($success) {
            $message = _l('updated_successfully', _l('youyube'));
        }

        redirect(admin_url('social_analytic/raw_data_detail/'.$id));
    }

    /**
     * [instagram_connect_save]
     */
    public function instagram_connect_save(){
        $data = $this->input->post();
        $success = $this->social_analytic_model->instagram_connect_save($data);
        if ($success) {
            $message = _l('connected_successfully', _l('account'));
            set_alert('success', $message);
        }
        redirect(admin_url('social_analytic/social_accounts?type=instagram'));
    }

    /**
     * [get_instagram_data]
     * @param  [type] $id instagram account id
     */
    public function get_instagram_data($id){
        $message = '';
        $success = $this->social_analytic_model->get_instagram_data($id);
        if ($success) {
            $message = _l('updated_successfully', _l('instagram'));
        }

        redirect(admin_url('social_analytic/raw_data_detail/'.$id));
    }
}