<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|   http://codeigniter.com/user_guide/general/hooks.html
|
*/

if (! function_exists('e')) {
    /**
     * Encode HTML special characters in a string.
     *
     * @param bool  $doubleEncode
     * @param mixed $value
     *
     * @return string
     */
    function e($value, $doubleEncode = true)
    {
        if ($value instanceof BackedEnum) {
            $value = $value->value;
        }

        return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }
}

/**
 * @since  2.3.0
 * NEW Global hooks function
 */
function hooks()
{
    global $hooks;
    return $hooks;
}

/**
 * ---------------------------------------------------------------
 *  ✅ FIXED: Affiliate session hook moved to pre_controller
 * ---------------------------------------------------------------
 */

/**
 * The remaining hooks stay unchanged
 */

$hook['pre_system'][] = [
    'class'    => 'EnhanceSecurity',
    'function' => 'protect',
    'filename' => 'EnhanceSecurity.php',
    'filepath' => 'hooks',
    'params'   => [],
];

$hook['pre_system'][] = [
    'class'    => 'App_Autoloader',
    'function' => 'register',
    'filename' => 'App_Autoloader.php',
    'filepath' => 'hooks',
    'params'   => [],
];

$hook['pre_system'][] = [
    'class'    => 'InitModules',
    'function' => 'handle',
    'filename' => 'InitModules.php',
    'filepath' => 'hooks',
    'params'   => [],
];
$hook['pre_controller'][] = [
    'class'    => '',
    'function' => 'affiliate_session_boot',
    'filename' => 'affiliate_session.php',
    'filepath' => 'hooks',
    'params'   => [],
];


$hook['pre_controller_constructor'][] = [
    'class'    => '',
    'function' => '_app_init',
    'filename' => 'InitHook.php',
    'filepath' => 'hooks',
];

$hook['post_controller_constructor'][] = [
    'class'    => '',
    'function' => 'admin_theme_head_component',
    'filename' => 'flatadmintheme_helper.php',
    'filepath' => 'modules/flatadmintheme/helpers'
];

$hook['post_controller'] = function () {
    $ci = get_instance();

    if (! $ci->input->is_ajax_request()) {
        $currentUrl = current_full_url();

        $skip = [
            'pusher_auth',
            'download/preview_image',
            'download/preview_video',
            'download/file'
        ];

        $remember = true;

        foreach ($skip as $haystack) {
            if (strpos($currentUrl, $haystack) !== false) {
                $remember = false;
                break;
            }
        }

        // Skip affiliate module (prevents overwriting affiliate_session)
        if (strpos($currentUrl, '/affiliate/') !== false) {
            return;
        }

        if ($remember) {
            $ci->session->set_userdata('_prev_url', $currentUrl);
        }
    }
};

if (file_exists(APPPATH . 'config/my_hooks.php')) {
    include_once APPPATH . 'config/my_hooks.php';
}
