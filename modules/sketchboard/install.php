<?php

defined('BASEPATH') or exit('No direct script access allowed');

$CI = &get_instance();


// Check and create 'sketchboard' table
if (!$CI->db->table_exists(db_prefix() . 'sketchboard')) {
    
    $CI->db->query('CREATE TABLE `' . db_prefix() . 'sketchboard` (
        `id` int(11) NOT NULL,
        `project_id` int(11) DEFAULT NULL,
        `name` varchar(100) DEFAULT NULL,
        `hash` varchar(100) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `sketch_data` longtext DEFAULT NULL,
        `created_by`int(11) DEFAULT 0,
        `is_public_edit`int(11) DEFAULT 0,
        `date_created` datetime NOT NULL,
        `date_updated` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sketchboard`
        ADD PRIMARY KEY (`id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sketchboard`
        MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
}






