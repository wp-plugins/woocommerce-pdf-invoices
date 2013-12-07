<?php

function bewpi_get_options(){
	static $options;

	if(!$options){
		$defaults = array(
        'company_name' => get_option('woocommerce_email_from_name'),
        'company_slogan' => '',
        'file_upload' => '',
        'address' => '',
        'zip_code' => '',
        'city' => '',
        'country' => '',
        'telephone' => '',
        'email' => '',
        'extra_company_info' => '',
        'extra_info' => '',
	    );

		// Get options from database.
	    $db_option = get_option('be_woocommerce_pdf_invoices', array());

	    if(!$db_option) {
			update_option('be_woocommerce_pdf_invoices', $defaults);
		}

		$options = wp_parse_args($db_option, $defaults);
	}

    return $options;
}

?>