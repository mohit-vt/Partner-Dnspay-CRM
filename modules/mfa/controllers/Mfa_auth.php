<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * MFA Controller
 */
class mfa_auth extends App_Controller {

	/**
	 * Constructs a new instance.
	 */
	public function __construct() {
		parent::__construct();
		if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }

        load_admin_language();
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');

		$this->load->model('mfa_model');
	}

	/**
	 * { multi factor authentication }
	 * @param        $staffid  The staffid
	 * @return view
	 */
	// public function multi_factor_authentication($staffid){
	// 	$this->load->model('staff_model');
	// 	$staff = $this->staff_model->get($staffid);
	// 	$data['title'] = _l('multi_factor_authentication');
	// 	$data['staff'] = $staff;
	// 	$data['qr_url'] = '';
	// 	if(!$this->input->post()){
	// 		$this->session->unset_userdata('staff_user_id');
	//         $this->session->unset_userdata('staff_logged_in');
	//     }
		
	// 	$this->load->view('authentication', $data);
	// }

	public function multi_factor_authentication($staffid = null)
{
    if (empty($staffid)) {
        $staffid = get_staff_user_id();
    }
    if (!$staffid) {
        show_error('Staff ID is missing for MFA setup.');
    }

    $this->load->model('staff_model');
    $staff = $this->staff_model->get($staffid);

    if (!class_exists('PHPGangsta_GoogleAuthenticator')) {
        require_once MFA_PATH . 'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
    }
    $ga = new PHPGangsta_GoogleAuthenticator();

    $data['qr_url'] = '';
    $data['show_qr'] = false; // default: don't show QR

    // If user has no MFA secret, generate one & show QR
    if (empty($staff->gg_auth_secret_key)) {
        $secret = $ga->createSecret();

        $this->db->where('staffid', $staff->staffid)
                 ->update(db_prefix() . 'staff', [
                     'gg_auth_secret_key'    => $secret,
                     'mfa_google_ath_enable' => 1
                 ]);

        $staff->gg_auth_secret_key = $secret;

        // Generate QR code for NEW users
        $data['qr_url'] = $ga->getQRCodeGoogleUrl(
            $staff->email,
            $staff->gg_auth_secret_key,
            get_option('companyname')
        );
        $data['show_qr'] = true;
    }

    $data['staff'] = $staff;
    $data['title'] = _l('multi_factor_authentication');

    $this->load->view('authentication', $data);
}



    /**
     * Legacy verify MFA code (optional if above is used)
     */
public function mfa_check_auth($staffid)
{
    $this->load->model('staff_model');
    $staff = $this->staff_model->get($staffid);

    if ($this->input->post('code')) {

        if (!class_exists('PHPGangsta_GoogleAuthenticator')) {
            require_once MFA_PATH . 'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
        }

        $ga = new PHPGangsta_GoogleAuthenticator();
        $code = trim($this->input->post('code'));

        if (!preg_match('/^\d{6}$/', $code) ||
            !$ga->verifyCode($staff->gg_auth_secret_key, $code, 2)) {
            set_alert('danger', _l('invalid_otp_code'));
            redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/'.$staffid));
        }

        // Mark MFA as verified
        $this->session->set_userdata([
            'mfa_verified'     => true,
            'staff_user_id'    => $staff->staffid,
            'staff_logged_in'  => true
        ]);

        // Preserve login context
        $context = $this->session->userdata('login_context') ?? 'admin';

        // Preserve temp login session data
        $email    = $this->session->userdata('temp_staff_email');
        $password = $this->session->userdata('temp_staff_password');
        $remember = $this->session->userdata('temp_staff_remember');

        // Perform login
        $this->load->model('Authentication_model');
        $this->Authentication_model->login($email, $password, $remember, true);

        // Clear temp MFA session data
        $this->session->unset_userdata([
            'temp_staff_email',
            'temp_staff_password',
            'temp_staff_remember',
            'temp_staff_id'
        ]);

        $this->load->model('announcements_model');
        $this->announcements_model->set_announcements_as_read_except_last_one($staff->staffid, true);

        // Pass staff object to hooks to avoid "staffid on string" error
        hooks()->do_action('after_staff_login', $staff);

        // Redirect to the proper context
        if ($context === 'reseller') {
            redirect(site_url('reseller/dashboard'));
        } else {
            redirect_after_login();
        }
    }
}

}

	
	// public function mfa_check_auth($staffid){
	// 	$this->load->model('staff_model');
	// 	$staff = $this->staff_model->get($staffid);

	// 	if($this->input->post()){
	// 		$code = $this->input->post();
	// 		$rs_gg_ath = 0;
	// 		$rs_whatsapp = 0;
	// 		$rs_sms = 0;

	// 		// Check staff & admin setting
	// 		if($staff->mfa_google_ath_enable == 1 && get_mfa_option('enable_google_authenticator') == 1 && enable_gg_auth_with_role($staff->role) == 1){ 
	// 			if(!class_exists('PHPGangsta_GoogleAuthenticator')){
	// 				require_once MFA_PATH.'assets/plugins/PHPGangsta/GoogleAuthenticator.php';
	// 			}

	// 			$auth = new PHPGangsta_GoogleAuthenticator();
	// 			$secret_key = $staff->gg_auth_secret_key	;
	// 			$tolerance = 1;
	// 			$check_result = $auth->verifyCode($secret_key, $code['code'], $tolerance);
	// 			if($check_result){
	// 				$rs_gg_ath++;
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'google_authenticator', 'success');
	// 			}
	// 		}

	// 		if($staff->mfa_whatsapp_enable == 1 && get_mfa_option('enable_whatsapp') == 1){
	// 			$check = check_security_code($staff->staffid, $code['code'], 'whatsapp');
	// 			if($check == true){
	// 				$rs_whatsapp++;
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'whatsapp', 'success');
	// 			}
	// 		}

	// 		if($staff->mfa_sms_enable == 1 && get_mfa_option('enable_sms') == 1){
	// 			$check = check_security_code($staff->staffid, $code['code'], 'sms');
	// 			if($check == true){
	// 				$rs_sms++;
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'sms', 'success');
	// 			}
	// 		}

	// 		if($rs_gg_ath > 0 || $rs_whatsapp > 0 || $rs_sms > 0){
	// 			$this->mfa_model->delete_old_security_code($staff->staffid);
	// 			$user_data = [
    //                     'staff_user_id'   => $staff->staffid,
    //                     'staff_logged_in' => true,
    //                 ];
    //             $this->session->set_userdata($user_data);
	// 			redirect(admin_url());
	// 		}else{
	// 			if($staff->mfa_google_ath_enable == 1 && get_mfa_option('enable_google_authenticator') == 1){
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'google_authenticator', 'fail');
	// 			}

	// 			if($staff->mfa_whatsapp_enable == 1 && get_mfa_option('enable_whatsapp') == 1){
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'whatsapp', 'fail');
	// 			}

	// 			if($staff->mfa_sms_enable == 1 && get_mfa_option('enable_sms') == 1){
	// 				$this->mfa_model->mfa_history_login($staff->staffid, 'sms', 'fail');
	// 			}

	// 			redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/'.$staff->staffid));
	// 		}		
	// 	}
	// }
//}