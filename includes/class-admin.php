<?php
class BEWPI_Admin {

    function __construct() {
        add_action( 'admin_init', array($this, 'register_settings' ));
        add_action( 'admin_menu', array($this, 'add_menu_item'));
        add_action( 'admin_notices', array($this, 'woocommerce_pdf_invoices_admin_notices' ));
    }

    function woocommerce_pdf_invoices_admin_notices() {
        settings_errors( 'woocommerce_pdf_invoices_notices' );
    }

    function register_settings() {
        register_setting( 'be_woocommerce_pdf_invoices', 'be_woocommerce_pdf_invoices', array($this, 'validate_settings'));
    }

    function add_menu_item() {
        $page = add_submenu_page( 'woocommerce', 'Invoices by Bas Elbers', 'Invoices', 'manage_options', 'bewpi', array($this, 'show_settings_page') );

        // Nieuwe functionaliteit
        add_action( 'admin_print_styles-' . $page, array($this, 'woocommerce_pdf_invoices_admin_styles' ));
        // Einde
    }

    // Nieuwe functionaliteit
    function woocommerce_pdf_invoices_admin_styles() {
        wp_enqueue_style( 'AdminStylesheet', plugins_url()."/woocommerce-pdf-invoices/assets/css/admin-styles.css" );
    }
    // Einde

    function validate_settings($settings) { 
    $old_settings = get_option('be_woocommerce_pdf_invoices');
    $errors;

    if((isset($_FILES['logo']['name'])) && ($_FILES["logo"]["error"]==0))
    {
        if($_FILES['logo']['size'] <= 50000){
            if(preg_match('/(jpg|jpeg|png)$/', $_FILES['logo']['type'])){   
                $override = array('test_form' => false);
                $file = wp_handle_upload( $_FILES['logo'], $override );
                $settings['file_upload'] = $file['url'];
            }else{
                add_settings_error('woocommerce_pdf_invoices_notices', esc_attr( 'settings_updated' ), __('Please upload image with extension jpg, jpeg or png.'), 'error');
                $errors++;
            }
        }else{
            add_settings_error('woocommerce_pdf_invoices_notices', esc_attr( 'settings_updated' ), __('Please upload image less then 50kB.'), 'error');
            $errors++;
        }
    }
    
    if(!$errors){
        add_settings_error('woocommerce_pdf_invoices_notices', esc_attr( 'settings_updated' ), __('Settings saved.'), 'updated');
    }

    return $settings;
    }

    public function show_settings_page() {
        $options = bewpi_get_options();
        include BEWPI_PLUGIN_DIR . 'includes/views/settings-page.php';
    }
}