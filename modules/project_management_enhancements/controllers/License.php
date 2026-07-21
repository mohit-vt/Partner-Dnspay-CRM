<?php

defined('BASEPATH') or exit('No direct script access allowed');

class License extends AdminController {
    protected const BASE_PATH = '/license';
    protected const MODULE_NAME = 'project_management_enhancements';

    public function index()
    {
        if ($this->input->post(NULL, TRUE)) {
            $data = $this->input->post(NULL, FALSE);
            $type = $data['type'] ?? 'activate';

            if ($type === 'remove') {
                $this->manually_remove_license();
                set_alert('success', _l('License removed successfully'));
            } else {
                $this->manually_activate_license($data);
                set_alert('success', _l('License activated successfully'));
            }

            // Redirect to the desired page after activation or removal
            redirect(admin_url('project_management_enhancements/tasks'));
        }

        $data = [
            'title' => 'Project Management Enhancements License',
        ];

        return $this->load->view(self::BASE_PATH . '/index', $data);
    }

    /**
     * Manually activates the module.
     */
    private function manually_activate_license($data)
    {
        // Simulate license activation and update the database
        update_option(self::MODULE_NAME . '_is_activated', TRUE);
        update_option(self::MODULE_NAME . '_license_key', 'bypassed-license-key');
        update_option(self::MODULE_NAME . '_activated_at', date('Y-m-d H:i:s'));
        update_option(self::MODULE_NAME . '_customer_name', $data['customer_name'] ?? 'Bypassed User');
        update_option(self::MODULE_NAME . '_customer_email', $data['customer_email'] ?? 'bypass@example.com');
    }

    /**
     * Manually removes the license.
     */
    private function manually_remove_license()
    {
        // Simulate license removal
        update_option(self::MODULE_NAME . '_is_activated', FALSE);
        update_option(self::MODULE_NAME . '_license_key', '');
        update_option(self::MODULE_NAME . '_activated_at', '');
        update_option(self::MODULE_NAME . '_customer_name', '');
        update_option(self::MODULE_NAME . '_customer_email', '');
    }
}
