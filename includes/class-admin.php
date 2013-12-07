<?php

class BEWPI_Admin {

	function __construct() {
		add_action( 'admin_init', array($this, 'register_settings' ));
		add_action( 'admin_menu', array($this, 'add_menu_item'));

		require BEWPI_PLUGIN_DIR . 'includes/class-invoice.php';
		add_filter( 'woocommerce_email_attachments', array('BEWPI_Invoice', 'generate_invoice'),10,3 );
	}

	function register_settings() {
    	register_setting( 'be_woocommerce_pdf_invoices', 'be_woocommerce_pdf_invoices', array($this, 'validate_settings'));
	}

	function add_menu_item() {
    	add_submenu_page( 'woocommerce', 'Invoices by Bas Elbers', 'Invoices', 'manage_options', 'bewpi', array($this, 'show_settings_page') );
	}

	function validate_settings($settings) 
	{ 
    $old_settings = get_option('be_woocommerce_pdf_invoices');

    if(isset($_FILES['logo']))
    {
        if ($_FILES['logo']['size'] <= 30000) 
        {     
            if ( preg_match('/(jpg|jpeg|png)$/', $_FILES['logo']['type']) ) 
            {
                $override = array('test_form' => false);
                $file = wp_handle_upload( $_FILES['logo'], $override );
                $settings['file_upload'] = $file['url'];
            }
        }
    }
    return $settings;
	}

	public function show_settings_page() {
		$options = bewpi_get_options();
		include BEWPI_PLUGIN_DIR . 'includes/views/settings-page.php';
	}
}