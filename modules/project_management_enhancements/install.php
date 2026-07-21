<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Directly set the module as activated without requiring license validation
add_option('zpme_is_activated', TRUE); // Set to TRUE to mark it as activated
add_option('zpme_license_key', 'bypassed'); // Assign a placeholder license key
add_option('zpme_activated_at', date('Y-m-d H:i:s')); // Store the current activation timestamp
add_option('zpme_last_validate', date('Y-m-d H:i:s')); // Store the current timestamp as last validation
add_option('pme_migrated_database', FALSE);

// Perform database migration only if it hasn't been done yet
if (!get_option('pme_migrated_database')) {

    $CI = &get_instance();
    $table_exist = $CI->db->query("SHOW TABLES LIKE '%task_types'")->num_rows();

    if ($table_exist <= 0) {
        // Include the migration file and execute it
        require_once APP_MODULES_PATH . 'project_management_enhancements/migrations/100_version_100.php';

        $migration = new Migration_Version_100();
        $migration->up();
    }

    // Update the option to indicate the database migration is complete
    update_option('pme_migrated_database', TRUE);
}
