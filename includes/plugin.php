<?php

function bewpi_get_options(){
    static $options;

    if(!$options){
        $defaults = array(
        'email_type' => '',
        'attach_to_new_order' => '',
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
        'vat_rates' => '',
        'display_customer_notes' => '',
        'display_SKU' => '',
        'invoice_number' => '',
        'invoice_number_start' => '1',
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

function bewpi_load_textdomain() {
    load_plugin_textdomain('woocommerce-pdf-invoices', false, 'woocommerce-pdf-invoices/lang/' );
}

add_action('plugins_loaded', 'bewpi_load_textdomain');

?>