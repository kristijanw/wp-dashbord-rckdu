<?php

/**
 * Rezervacije Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Rezervacije Child
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define Constants
 */
define('CHILD_THEME_VERSION', '1.0.0');
define('CHILD_TEMPLATE_ROOT', get_stylesheet_directory() . '/templates/');

define('PRO_ACF_USER_PREFIX', 'user_');


/**
 * Define all files that needs to be included in child theme
 */
require_once(get_stylesheet_directory() . '/includes/helpers.php');
require_once(get_stylesheet_directory() . '/includes/settings-sidebar.php');
require_once(get_stylesheet_directory() . '/includes/settings-theme.php');

require_once(get_stylesheet_directory() . '/classes/room-class.php');
require_once(get_stylesheet_directory() . '/classes/staff-class.php');
require_once(get_stylesheet_directory() . '/classes/table-class.php');
require_once(get_stylesheet_directory() . '/classes/reservation-class.php');
require_once(get_stylesheet_directory() . '/classes/responsibilities-class.php');

require_once(get_stylesheet_directory() . '/includes/exportXLSX.php');
