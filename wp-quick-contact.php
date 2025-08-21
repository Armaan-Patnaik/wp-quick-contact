<?php
/**
 * Plugin Name: WP Quick Contact
 * Description: Lightweight AJAX contact form with nonce checks and admin settings.
 * Version: 0.1.0
 * Author: Armaan Patnaik
 * Text Domain: wpqc
 */
if ( ! defined( 'ABSPATH' ) ) exit;
require_once plugin_dir_path(__FILE__) . 'includes/class-wpqc-form.php';
add_action('plugins_loaded', ['WPQC_Form', 'init']);
