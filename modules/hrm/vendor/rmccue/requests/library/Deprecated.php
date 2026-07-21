<?php
/**
 * Deprecated functionality.
 *
 * @package Requests
 */

/**
 * Deprecated functionality.
 *
 * @package Requests
 */
class Requests_Deprecated {
    /**
     * Handle deprecated function usage.
     *
     * @param string $function Function name
     * @param string $message  Message to display
     * @param string $version  Version deprecated in
     * @return void
     */
    public static function notice($function, $message, $version) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            trigger_error(
                sprintf(
                    '%1$s is deprecated since version %2$s! %3$s',
                    $function,
                    $version,
                    $message
                ),
                E_USER_DEPRECATED
            );
        }
    }
}
