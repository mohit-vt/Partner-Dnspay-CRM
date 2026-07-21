<?php

defined('BASEPATH') or exit('No direct script access allowed');

function affiliate_session_boot()
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';

    // Only activate for affiliate module pages
    if (strpos($uri, '/affiliate/') === false) {
        return; // Do NOTHING for admin/staff/other pages
    }

    $CI = &get_instance();

    // Load affiliate session config
    $CI->config->load('affiliate_session', TRUE);
    $cfg = $CI->config->item('affiliate_session');

    if (!empty($cfg)) {
        foreach ($cfg as $k => $v) {
            $CI->config->set_item($k, $v);
        }
    }

    // DO NOT load session manually here
    // DO NOT destroy the main CI session
    // CI will load session normally after pre_system
}

