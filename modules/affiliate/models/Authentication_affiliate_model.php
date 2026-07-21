<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication_affiliate_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_autologin');
        //$this->autologin();
    }

    /**
     * @param  string Email address for login
     * @param  string User Password
     * @param  boolean Set cookies for user if remember me is checked
     * @param  boolean Is Staff Or Client
     * @return boolean if not redirect url found, if found redirect to the url
     */
    public function login($email, $password, $remember, $staff)
{
    if (empty($email) || empty($password)) {
        return false;
    }

    $table = db_prefix() . 'affiliate_users';
    $primary = 'id';

    if ($staff) {
        $table = db_prefix() . 'staff';
        $primary = 'staffid';
    }

    $this->db->where('email', $email);
    $user = $this->db->get($table)->row();

    if (!$user) {
        return false;
    }

    // Validate password
    if (!app_hasher()->CheckPassword($password, $user->password)) {
        return false;
    }

    // BLOCKED ACCOUNT CHECK
    if (isset($user->status) && $user->status == 0) {
        return ['memberinactive' => true];
    }

    // Affiliate approval check
    if (!$staff) {
        if (!isset($user->approval) || intval($user->approval) !== 1) {
            return ['memberinactive' => true];
        }
    }

    // Build user data
    if ($staff) {
        $user_data = [
            'staff_user_id'   => $user->{$primary},
            'staff_logged_in' => true,
        ];
    } else {
        $user_data = [
            'affiliate_user_id'   => $user->id,
            'affiliate_user_code' => $user->affiliate_code,
            'affiliate_logged_in' => true,
        ];
    }

    // WRITE SESSION
    $this->session->set_userdata($user_data);

    // Remember me
    if ($remember) {
        $this->create_autologin($user->{$primary}, $staff);
    }

	// MAKE SURE SESSION IS SAVED TO DB
session_write_close();

// VERY IMPORTANT: return the full user object
return $user;


}


    /**
     * @param  boolean If Client or Staff
     * @return none
     */
    public function logout($staff = true)
    {
        $this->delete_autologin($staff);

        if (is_affiliate_logged_in()) {
            hooks()->do_action('before_affiliate_logout', get_affiliate_user_id());

            $this->session->unset_userdata('affiliate_user_id');
            $this->session->unset_userdata('affiliate_logged_in');
        }

        $this->session->sess_destroy();
    }

    /**
     * @param  integer ID to create autologin
     * @param  boolean Is Client or Staff
     * @return boolean
     */
    private function create_autologin($user_id, $staff)
    {
        $this->load->helper('cookie');
        $key = substr(md5(uniqid(rand() . get_cookie($this->config->item('sess_cookie_name')))), 0, 16);
        $this->user_autologin->delete($user_id, $key, $staff);
        if ($this->user_autologin->set($user_id, md5($key), $staff)) {
            set_cookie([
                'name'  => 'autologin',
                'value' => serialize([
                    'user_id' => $user_id,
                    'key'     => $key,
                ]),
                'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
            ]);

            return true;
        }

        return false;
    }

    /**
     * @param  boolean Is Client or Staff
     * @return none
     */
    private function delete_autologin($staff)
    {
        $this->load->helper('cookie');
        if ($cookie = get_cookie('autologin', true)) {
            $data = unserialize($cookie);
            //$this->user_autologin->delete($data['affiliate_user_id'], md5($data['key']), $staff);
          $this->user_autologin->delete($data['user_id'], md5($data['key']), $staff);

            delete_cookie('autologin', 'aal');
        }
    }

    /**
     * @return boolean
     * Check if autologin found
     */
    public function autologin()
    {
        if (!is_affiliate_logged_in() && !is_staff_logged_in()) {
            $this->load->helper('cookie');
            if ($cookie = get_cookie('autologin', true)) {
                $data = unserialize($cookie);
                if (isset($data['key']) and isset($data['user_id'])) {
                    if (!is_null($user = $this->user_autologin->get($data['user_id'], md5($data['key'])))) {
                        // Login user
                        if ($user->staff == 1) {
                            $user_data = [
                                'staff_user_id'   => $user->id,
                                'staff_logged_in' => true,
                            ];
                        } else {
                            $user_data = [
                                'affiliate_user_id'   => $user->id,
                                'affiliate_logged_in' => true,
                            ];
                        }
                        $this->session->set_userdata($user_data);
                        // Renew users cookie to prevent it from expiring
                        set_cookie([
                            'name'   => 'autologin',
                            'value'  => $cookie,
                            'expire' => 60 * 60 * 24 * 31 * 2, // 2 months
                        ]);

                        return true;
                    }
                }
            }
        }

        return false;
    }
    
    /**
     * Get user from database by 2 factor authentication code
     * @param  string $code authentication code to search for
     * @return object
     */
    public function get_user_by_two_factor_auth_code($code)
    {
        $this->db->where('two_factor_auth_code', $code);

        return $this->db->get(db_prefix() . 'staff')->row();
    }

    /**
     * Login user via two factor authentication
     * @param  object $user user object
     * @return boolean
     */
    public function two_factor_auth_login($user)
    {
        hooks()->do_action('before_staff_login', [
            'email'  => $user->email,
            'userid' => $user->staffid,
        ]);

        $this->session->set_userdata(
            [
                'staff_user_id'   => $user->staffid,
                'staff_logged_in' => true,
            ]
        );

        $remember = null;
        if ($this->session->has_userdata('tfa_remember')) {
            $remember = true;
            $this->session->unset_userdata('tfa_remember');
        }

        if ($remember) {
            $this->create_autologin($user->staffid, true);
        }

        return true;
    }

    /**
     * Check if 2 factor authentication code is valid for usage
     * @param  string  $code auth code
     * @return boolean
     */
    public function is_two_factor_code_valid($code)
    {
        $this->db->select('two_factor_auth_code_requested');
        $this->db->where('two_factor_auth_code', $code);
        $user = $this->db->get(db_prefix() . 'staff')->row();

        // Code not exists because no user is found
        if (!$user) {
            return false;
        }

        $timestamp_minus_1_hour = time() - (60 * 60);
        $new_code_key_requested = strtotime($user->two_factor_auth_code_requested);
        // The code is older then 1 hour and its not valid
        if ($timestamp_minus_1_hour > $new_code_key_requested) {
            return false;
        }
        // Code is valid
        return true;
    }

    /**
     * Clears 2 factor authentication code in database
     * @param  mixed $id
     * @return boolean
     */
    public function clear_two_factor_auth_code($id)
    {
        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code' => null,
        ]);

        return true;
    }

    /**
     * Set 2 factor authentication code for staff member
     * @param mixed $id staff id
     */
    public function set_two_factor_auth_code($id)
    {
        $code = generate_two_factor_auth_key();
        $code .= $id;

        $this->db->where('staffid', $id);
        $this->db->update(db_prefix() . 'staff', [
            'two_factor_auth_code'           => $code,
            'two_factor_auth_code_requested' => date('Y-m-d H:i:s'),
        ]);

        return $code;
    }
}
