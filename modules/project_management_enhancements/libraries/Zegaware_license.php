<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!class_exists('Zegaware_license')) {
    class Zegaware_license {
        /**
         * Check if activated - Always return true to bypass license checks.
         */
        public static function is_activated($module_name): bool
        {
            return true; // Always activated
        }

        /**
         * Get license key - Return a dummy key or stored value.
         */
        public static function get_license_key($module_name): ?string
        {
            return 'bypassed-license-key'; // Return a placeholder key
        }

        /**
         * Get activated date - Return the current date as a default.
         *
         * @return DateTime|false
         */
        public static function get_activated_date($module_name): DateTime|bool
        {
            return new DateTime(); // Always return the current date
        }

        /**
         * Activate the license - Simulate successful activation.
         */
        public static function activate_license($module_name, $additional_data): bool|string
        {
            // Simulate activation success
            update_option($module_name . '_is_activated', true);
            update_option($module_name . '_customer_name', $additional_data['customer_name'] ?? 'Bypassed User');
            update_option($module_name . '_customer_email', $additional_data['customer_email'] ?? 'bypass@example.com');
            update_option($module_name . '_license_key', 'bypassed-license-key');
            update_option($module_name . '_activated_at', date('Y-m-d H:i:s'));

            set_alert('success', _l('zegaware_activated_success'));
            return true; // Always successful
        }

        /**
         * Remove license - Simulate successful removal.
         */
        public static function remove_license($module_name): void
        {
            update_option($module_name . '_is_activated', false);
            update_option($module_name . '_license_key', false);
            update_option($module_name . '_activated_at', false);
            update_option($module_name . '_customer_name', false);
            update_option($module_name . '_customer_email', false);
        }

        /**
         * Validate current license - Always return true to bypass validation.
         */
        public static function validate_current_license(string $module_name): bool
        {
            // Simulate successful validation
            update_option($module_name . '_last_validate', json_encode(['date' => date('Y-m-d')]));
            return true;
        }
    }
}
