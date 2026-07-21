<?php defined('BASEPATH') or exit('No direct script access allowed');

function affiliate_session_boot()
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';

    // Only trigger for affiliate routes
    if (strpos($uri, '/affiliate/') === false) {
        return;
    }

    $config_file = APPPATH . 'config/affiliate_session.php';

    if (!file_exists($config_file)) {
        return;
    }

    // Load the CI instance manually because session not yet loaded
    require $config_file; // loads $config array

    if (!isset($config) || !is_array($config)) {
        return;
    }

    // Get CI config instance
    $CFG = load_class('Config', 'core');

    // Apply overrides BEFORE session library loads
    foreach ($config as $key => $value) {
        $CFG->set_item($key, $value);
    }
}
