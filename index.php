<?php

/**
 * Plugin Name: Woocommerce PDF Invoices
 * Description: Generate PDF invoice and automatically attach to Woocommerce confirmation email.
 * Version: 1.0.1
 * Author: Bas Elbers
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define("BEWPI_VERSION", "1.0.0");
define("BEWPI_PLUGIN_DIR", plugin_dir_path(__FILE__)); 
define("BEWPI_PLUGIN_URL", plugins_url( '/' , __FILE__ ));

require_once BEWPI_PLUGIN_DIR . 'includes/plugin.php';
require BEWPI_PLUGIN_DIR . 'includes/class-admin.php';
new BEWPI_Admin();

?>