<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'sa_workspaces')) {
    $CI->db->query('CREATE TABLE ' . db_prefix() . "sa_workspaces (
  	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `name` TEXT NOT NULL,
      `timezone` VARCHAR(255) NULL,
      `super_admin` INT(11) NULL,
      `addedfrom` INT(11) NULL,
      `dateadded` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('is_default' ,db_prefix() . 'sa_workspaces')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_workspaces`
  ADD COLUMN `is_default` TINYINT(1) NOT NULL DEFAULT "0";');
}

if (!$CI->db->table_exists(db_prefix() . 'sa_workspace_members')) {
    $CI->db->query('CREATE TABLE ' . db_prefix() . "sa_workspace_members (
  	  `id` INT(11) NOT NULL AUTO_INCREMENT,
      `workspace_id` INT(11) NULL,
	  `type` VARCHAR(25) NOT NULL,
      `member_id` INT(11) NULL,
      `addedfrom` INT(11) NULL,
      `dateadded` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('workspace_logo' ,db_prefix() . 'sa_workspaces')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_workspaces`
  ADD COLUMN `workspace_logo` TEXT NULL;');
}

if (!$CI->db->field_exists('notes' ,db_prefix() . 'sa_workspaces')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_workspaces`
  ADD COLUMN `notes` TEXT NULL;');
}

if (!$CI->db->table_exists(db_prefix() . 'sa_accounts')) {
    $CI->db->query('CREATE TABLE ' . db_prefix() . "sa_accounts (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `name` TEXT NOT NULL,
      `client_id` INT(11) NULL,
      `type` VARCHAR(25) NULL,
      `status` VARCHAR(25) NULL,
      `addedfrom` INT(11) NULL,
      `dateadded` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'sa_analytics')) {
    $CI->db->query('CREATE TABLE ' . db_prefix() . "sa_analytics (
      `id` INT(11) NOT NULL AUTO_INCREMENT,
      `account_id` INT(11) NULL,
      `time` DATETIME NOT NULL,
      `type` VARCHAR(255) NULL,
      `value` VARCHAR(255) NULL,
      `addedfrom` INT(11) NULL,
      `dateadded` DATETIME NOT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->field_exists('object_id' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `object_id` INT(11) NULL;');
}

if (!$CI->db->field_exists('rel_id' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `rel_id` INT(11) NULL,
  ADD COLUMN `rel_type` VARCHAR(25) NULL;');
}

if (!$CI->db->field_exists('sub_type' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `sub_type` VARCHAR(255) NULL;');
}

if (!$CI->db->field_exists('access_token' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `access_token` TEXT NULL,
  ADD COLUMN `page_id` TEXT NULL,
  ADD COLUMN `workspace_id` INT(11) NULL,
  ADD COLUMN `user_id` TEXT NULL
  ;');
}

if (!$CI->db->field_exists('import' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `import` TINYINT(1) NOT NULL DEFAULT 0
  ;');
}


add_option('sa_facebook_app_id');
add_option('sa_facebook_app_secret');
add_option('sa_facebook_graph_version', 'v21.0');

add_option('sa_instagram_app_id');
add_option('sa_instagram_app_secret');
add_option('sa_instagram_graph_version', 'v21.0');

add_option('sa_tiktok_client_key');
add_option('sa_tiktok_client_secret');

if (!$CI->db->field_exists('category' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `category` TEXT NULL 
  ;');
}


if (!$CI->db->field_exists('avatar_url' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `avatar_url` TEXT NULL, 
  ADD COLUMN `expires_in` TEXT NULL, 
  ADD COLUMN `refresh_token` TEXT NULL 
  ;');
}

if (!$CI->db->field_exists('description' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `description` TEXT NULL
  ;');
}

if (!$CI->db->field_exists('status' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `status` TINYINT(1) NOT NULL DEFAULT 0
  ;');
}

if (!$CI->db->field_exists('active' ,db_prefix() . 'sa_accounts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_accounts`
  ADD COLUMN `active` TINYINT(1) NOT NULL DEFAULT 0
  ;');
}
add_option('sa_twitter_client_id');
add_option('sa_twitter_client_secret');

if (!$CI->db->field_exists('gender' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `gender` VARCHAR(25) NULL,
  ADD COLUMN `age` VARCHAR(25) NULL,
  ADD COLUMN `language` VARCHAR(25) NULL,
  ADD COLUMN `country` VARCHAR(25) NULL,
  ADD COLUMN `reaction` VARCHAR(25) NULL,
  ADD COLUMN `post_type` VARCHAR(25) NULL
  ;');
}

if (!$CI->db->field_exists('stories_performance' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `stories_performance` VARCHAR(50) NULL,
  ADD COLUMN `engagement` VARCHAR(25) NULL,
  ADD COLUMN `subscriber` VARCHAR(25) NULL
  ;');
}


if (!$CI->db->field_exists('device' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `device` VARCHAR(50) NULL
  ;');
}

if (!$CI->db->field_exists('is_subscriber' ,db_prefix() . 'sa_analytics')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_analytics`
  ADD COLUMN `is_subscriber` VARCHAR(50) NULL
  ;');
}

if (!$CI->db->field_exists('base_workspace_id' ,db_prefix() . 'staff')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'staff`
  ADD COLUMN `base_workspace_id` INT(11) NOT NULL DEFAULT 0
  ;');
}

if (!$CI->db->field_exists('display_charts' ,db_prefix() . 'sa_workspaces')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'sa_workspaces`
  ADD COLUMN `display_charts` TEXT NULL
  ;');
}

if (!$CI->db->field_exists('base_workspace_id' ,db_prefix() . 'contacts')) {
    $CI->db->query('ALTER TABLE `' . db_prefix() . 'contacts`
  ADD COLUMN `base_workspace_id` INT(11) NOT NULL DEFAULT 0
  ;');
}

add_option('sa_youtube_client_id');
add_option('sa_youtube_client_secret');

