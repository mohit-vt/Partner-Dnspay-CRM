<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Impersonate extends CI_Controller
{
    public function start($token)
    {
        $this->load->database();

        // Delete old/expired tokens (optional - cleanup)
        $this->db->where('created_at <', date('Y-m-d H:i:s', strtotime('-10 minutes')));
        $this->db->delete('tbl_impersonation_tokens');

        // Find the token
        $row = $this->db->get_where('tbl_impersonation_tokens', ['token' => $token])->row();

        if (!$row) {
            show_error('Invalid or expired impersonation link.', 403);
        }

        // Create session data for agent in THIS session/tab only
        $this->load->library('session');
        $this->session->set_userdata([
            'staff_user_id'   => $row->agent_id,
            'staff_logged_in' => true,
            'impersonator_id' => $row->admin_id
        ]);

        // Delete the token (one-time use)
        $this->db->delete('tbl_impersonation_tokens', ['token' => $token]);

        // Redirect to admin dashboard (as that agent) in THIS tab
        redirect(admin_url());
    }

    // Optional: helper to return to admin if you want here too
    public function return_to_admin()
    {
        $impersonator_id = $this->session->userdata('impersonator_id');
        if ($impersonator_id) {
            $this->session->unset_userdata('impersonator_id');
            $this->session->set_userdata([
                'staff_user_id'   => $impersonator_id,
                'staff_logged_in' => true
            ]);
            set_alert('success', 'Returned to your admin account successfully.');
            redirect(admin_url('reseller'));
        } else {
            show_error('No impersonation session found.', 403);
        }
    }
}
