<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_101 extends App_module_migration
{
    public function up()
    {

        $CI = &get_instance();
        add_option('sketchboard_purchase_code', '');
        add_option('sketchboard_purchase_is_valid', 0);
        
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

        $sketchboard = db_prefix() . 'sketchboard';
        if (!$CI->db->field_exists('project_id', $sketchboard)) {
            $CI->db->query("ALTER TABLE `" . $sketchboard . "` ADD `project_id` INT(11) DEFAULT NULL  AFTER `id`;");
        }
        if (!$CI->db->field_exists('is_public_edit', $sketchboard)) {
            $CI->db->query("ALTER TABLE `" . $sketchboard . "` ADD `is_public_edit` INT(11) DEFAULT '0'  AFTER `created_by`;");
        }
        if (!$CI->db->field_exists('hash', $sketchboard)) {
            $CI->db->query("ALTER TABLE `" . $sketchboard . "` ADD `hash` varchar(100) DEFAULT '0'  AFTER `name`;");
        }
    }
}