<?php

/**
 * Plugin Name: WooCommerce PDF Invoices
 * Description: Generate PDF invoice and automatically attach to WooCommerce email type of your choice.
 * Version: 1.1.1
 * Author: Bas Elbers
 * License: GPL2
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define("BEWPI_VERSION", "1.1.1");
define("BEWPI_PLUGIN_DIR", plugin_dir_path(__FILE__)); 
define("BEWPI_PLUGIN_URL", plugins_url( '/' , __FILE__ ));

require_once BEWPI_PLUGIN_DIR . 'includes/plugin.php';
require_once BEWPI_PLUGIN_DIR . 'includes/class-invoice.php';
new BEWPI_Invoice;

if((is_admin()) && (!defined("DOING_AJAX") || !DOING_AJAX)) {
	
	// ADMIN SECTION
	require BEWPI_PLUGIN_DIR . 'includes/class-admin.php';
	new BEWPI_Admin();
}

?>