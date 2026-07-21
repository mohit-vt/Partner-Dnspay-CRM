<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();

// Manually set module as active
$CI->db->where('module_name', 'google_drive')->update('modules', ['active' => 1]);

// Ensure the table exists
if (!$CI->db->table_exists(db_prefix() . 'google_drives')) {
    $CI->db->query("
        CREATE TABLE `" . db_prefix() . "google_drives` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `staffid` INT(11) NOT NULL,
            `driveid` VARCHAR(256) NOT NULL,
            `title` VARCHAR(256) NOT NULL,
            `description` VARCHAR(1024) NULL,
            `type` VARCHAR(256) NOT NULL,
            `date` DATETIME NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ";
    ");
}

log_message('error', 'Google Drive module installed successfully.');
