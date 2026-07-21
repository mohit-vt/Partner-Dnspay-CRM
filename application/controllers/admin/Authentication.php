<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends App_Controller
{
    public function __construct()
    {
        parent::__construct();

        if ($this->app->is_db_upgrade_required()) {
            redirect(admin_url());
        }

        load_admin_language();
        $this->load->model('Authentication_model');
        $this->load->library('form_validation');

        $this->form_validation->set_message('required', _l('form_validation_required'));
        $this->form_validation->set_message('valid_email', _l('form_validation_valid_email'));
        $this->form_validation->set_message('matches', _l('form_validation_matches'));

        hooks()->do_action('admin_auth_init');

        $public_routes = [
            'admin',
            'index',
            'forgot_password',
            'reset_password',
            'set_password',
            'reset_disabled_notice',
            'reseller_login',
            'verify_twofa',
            'two_factor',
            'force_reset_password',
            'logout',
            'logout_agent',
            'recaptcha',
        ];

        if (!in_array($this->router->fetch_method(), $public_routes) && !is_staff_logged_in()) {
            $context = $this->session->userdata('login_context') ?? 'admin';

            if ($context === 'reseller') {
                redirect(site_url('reseller'));
            } else {
                redirect(admin_url('authentication'));
            }
        }
    }

    public function index()
    {
        return $this->admin();
    }

    public function admin()
    {
        if (is_staff_logged_in()) {
            return $this->redirect_by_context();
        }

        $this->session->set_userdata('login_context', 'admin');

        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
        $this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');

        if (show_recaptcha()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        if ($this->input->post()) {
            return $this->process_login('admin');
        }

        $data['title'] = _l('admin_auth_login_heading');
        $this->load->view('authentication/login_admin', $data);
    }

    public function reseller_login()
    {
        if (is_staff_logged_in()) {
            return $this->redirect_by_context();
        }

        $this->session->set_userdata('login_context', 'reseller');

        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');
        $this->form_validation->set_rules('password', _l('admin_auth_login_password'), 'required');

        if (show_recaptcha()) {
            $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'callback_recaptcha');
        }

        if ($this->input->post()) {
            return $this->process_login('reseller');
        }

        $data['title'] = 'Reseller Login';
        $this->load->view('authentication/login_reseller', $data);
    }

    private function process_login($context = 'admin')
    {
        if ($this->form_validation->run() === false) {
            return false;
        }

        $email    = trim($this->input->post('email'));
        $password = $this->input->post('password', false);
        $remember = $this->input->post('remember');

        $this->db->select('staff.*, roles.name as role_name');
        $this->db->from(db_prefix() . 'staff as staff');
        $this->db->join(db_prefix() . 'roles as roles', 'roles.roleid = staff.role', 'left');
        $this->db->where('staff.email', $email);
        $user = $this->db->get()->row();

        if (!$user || !app_hasher()->CheckPassword($password, $user->password)) {
            set_alert('danger', _l('admin_auth_invalid_email_or_password'));
            redirect($context === 'reseller' ? site_url('reseller') : admin_url('authentication'));
            return false;
        }

        $role_name   = strtolower(trim($user->role_name ?? ''));
        $user_status = trim($user->user_status ?? '');

        if ($context === 'reseller') {
            if (in_array($role_name, ['agent', 'reseller', 'partner admin'])) {
                if (strcasecmp($user_status, 'Approved') !== 0) {
                    set_alert('danger', 'Your account is pending approval. Please wait for admin approval.');
                    redirect(site_url('reseller'));
                    return false;
                }
            }
        }

        if ($user->active == 0) {
            $this->session->set_userdata('disabled_userid', $user->staffid);
            $this->session->set_userdata('login_context', $context);

            $new_pass_key = app_generate_hash();

            $this->db->where('staffid', $user->staffid);
            $this->db->update(db_prefix() . 'staff', [
                'new_pass_key'           => $new_pass_key,
                'new_pass_key_requested' => date('Y-m-d H:i:s'),
            ]);

            redirect(
                $context === 'reseller'
                    ? site_url('authentication/reset_disabled_notice')
                    : admin_url('authentication/reset_disabled_notice')
            );
            return false;
        }

        $this->session->set_userdata('login_context', $context);

        if ((int) $user->mfa_google_ath_enable === 1 && get_mfa_option('enable_google_authenticator')) {
            $this->session->set_userdata([
                'temp_staff_email'    => $email,
                'temp_staff_password' => $password,
                'temp_staff_remember' => $remember,
                'temp_staff_id'       => $user->staffid,
                'login_context'       => $context,
            ]);

            redirect(admin_url('mfa/mfa_auth/multi_factor_authentication/' . $user->staffid));
            return false;
        }

        $this->Authentication_model->login($email, $password, $remember, true);

        $this->load->model('announcements_model');
        $this->announcements_model->set_announcements_as_read_except_last_one(get_staff_user_id(), true);

        hooks()->do_action('after_staff_login');

        return $this->redirect_by_context();
    }

    public function reset_disabled_notice()
    {
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]');
        $this->form_validation->set_rules('passwordr', 'Repeat Password', 'required|matches[password]');

        $userid = $this->session->userdata('disabled_userid');
        $context = $this->session->userdata('login_context') ?? 'admin';

        if (!$userid) {
            redirect($context === 'reseller' ? site_url('reseller') : admin_url('authentication'));
            return;
        }

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $new_password = $this->input->post('password');
                $hashed       = app_hasher()->HashPassword($new_password);

                $this->db->where('staffid', $userid);
                $this->db->update(db_prefix() . 'staff', [
                    'password'               => $hashed,
                    'active'                 => 1,
                    'new_pass_key'           => null,
                    'new_pass_key_requested' => null,
                    'password_last_changed'  => date('Y-m-d H:i:s'),
                ]);

                $this->session->unset_userdata('disabled_userid');

                set_alert('success', 'Your password has been reset. Please login again.');

                redirect($context === 'reseller' ? site_url('reseller') : admin_url('authentication'));
                return;
            }
        }

        $data['title'] = 'Account Disabled - Reset Password';
        $this->load->view('authentication/reset_disabled_notice', $data);
    }

    private function redirect_by_context()
    {
        $context  = $this->session->userdata('login_context') ?? 'admin';
        $staff_id = get_staff_user_id();

        if (!$staff_id) {
            redirect(admin_url('authentication'));
            return;
        }

        $this->db->select('roles.name as role_name');
        $this->db->from(db_prefix() . 'staff as staff');
        $this->db->join(db_prefix() . 'roles as roles', 'roles.roleid = staff.role', 'left');
        $this->db->where('staff.staffid', $staff_id);
        $user = $this->db->get()->row();

        $user_role = strtolower(trim($user->role_name ?? ''));

        if ($context === 'reseller' || in_array($user_role, ['reseller', 'agent', 'partner admin'])) {
            redirect(site_url('reseller/dashboard'));
            return;
        }

        redirect(admin_url('dashboard'));
    }

    public function logout()
    {
        $login_context = $this->session->userdata('login_context') ?? 'admin';

        $this->Authentication_model->logout();
        hooks()->do_action('after_user_logout');

        redirect($login_context === 'reseller' ? site_url('reseller') : admin_url('authentication'));
    }

    public function logout_agent()
    {
        $this->session->unset_userdata(['agent_logged_in', 'staffid', 'login_context']);
        hooks()->do_action('after_user_logout');
        redirect(site_url('reseller'));
    }

    public function forgot_password()
    {
        if (is_staff_logged_in()) {
            redirect(admin_url());
        }

        $this->form_validation->set_rules('email', _l('admin_auth_login_email'), 'trim|required|valid_email');

        if ($this->input->post()) {
            if ($this->form_validation->run() !== false) {
                $success = $this->Authentication_model->forgot_password($this->input->post('email'), true);

                if (is_array($success) && isset($success['memberinactive'])) {
                    set_alert('danger', _l('inactive_account'));
                    redirect(admin_url('authentication/forgot_password'));
                } elseif ($success == true) {
                    set_alert('success', _l('check_email_for_resetting_password'));
                    redirect(admin_url('authentication'));
                } else {
                    set_alert('danger', _l('error_setting_new_password_key'));
                    redirect(admin_url('authentication/forgot_password'));
                }
            }
        }

        $this->load->view('authentication/forgot_password');
    }

    public function recaptcha($str = '')
    {
        return do_recaptcha_validation($str);
    }
}