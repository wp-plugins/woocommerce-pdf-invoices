<?php

class BEWPI_Invoice {

function __construct(){
    add_filter( 'woocommerce_email_attachments', array($this, 'generate_invoice'),10,3 );
}

// SEQUENTIAL INVOICE NUMBERS
function set_sequential_invoice_number( $post_id, $invoice_number_start ) {
    global $wpdb;
    $invoice_number = get_post_meta( $post_id, '_bewpi_invoice_number', true );

    if($invoice_number_start == ""){
        $invoice_number_start = 1;
    }

    if($invoice_number == ""){
        // attempt the query up to 3 times for a much higher success rate if it fails (due to Deadlock)
        $success = false;
        for ( $i = 0; $i < 3 && ! $success; $i++ ) {
            // this seems to me like the safest way to avoid order number clashes
            $query = $wpdb->prepare( "
                INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value)
                    SELECT %d, '_bewpi_invoice_number', IF( MAX( CAST( meta_value as UNSIGNED ) ) IS NULL, %d, MAX( CAST( meta_value as UNSIGNED ) ) + 1 )
                    FROM {$wpdb->postmeta}
                    WHERE meta_key='_bewpi_invoice_number'",
                    $post_id, $invoice_number_start );
            $success = $wpdb->query( $query );
        }
    }
}

function get_invoice_number($post_id){
    // for some reason this doesn't work the first time.
    //return get_post_meta( $post_id, '_bewpi_invoice_number', true );

    global $wpdb;
    $results = $wpdb->get_results("SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = '_bewpi_invoice_number'");

    if(count($results) == 1){
        return $results[0]->meta_value;
    }
}

function get_formatted_invoice_number($number, $option){
    $today = date('d');
    $month = date('m');
    $year = date('Y');
    $number = sprintf("%04s", $number);

    switch($option){
        case 1:
            $invoice_number = $number;
            break;
        case 2:
            $invoice_number = $year.$month.$today."-".$number;
            break;
        case 3:
            $year = substr($year, -2);
            $invoice_number = $year."-".$number;
            break;
    }
    return $invoice_number;
}
// END SEQUENTIAL INVOICE NUMBERS

function generate_invoice($val, $id, $order){
    $options = get_option('be_woocommerce_pdf_invoices');
    
    if($id == $options['email_type'] || $id == $options['attach_to_new_order']){

        $order_number = str_replace("#", "", $order->get_order_number());

        // Update db with sequential invoice number
        $this->set_sequential_invoice_number($order_number, $options['invoice_number_start']);

        // The library for generating HTML PDF files
        include(BEWPI_PLUGIN_DIR . '/mpdf/mpdf.php');

        // Some PDF settings..
        $mpdf = new mPDF('win-1252','A4','','',20,15,48,25,10,10); 
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDisplayMode('fullpage');
        
        // Dates
        $today = date('d');
        $month = date('m');
        $year = date('Y');
        //$current_date = date('d-m-Y');

        // Addresses
        $billing_address = $order->get_formatted_billing_address();
        $shipping_address = $order->get_formatted_shipping_address();

        // Money money moneeeeey
        $order_total = $order->get_total();
        $total_tax = $order->get_total_tax();
        $order_subtotal = $order_total - $total_tax;

        // Get the vat rates
        if($options['vat_rates']){ $vat_rates = explode(',', $options['vat_rates']); }

        // For displaying the table the wright way
        $rowspan = count($vat_rates) + 4;

        // Get invoice number from db and create format
        $number = $this->get_invoice_number($order_number);
        $invoice_number = $this->get_formatted_invoice_number($number, $options['invoice_number']);

        // Yeah! Let's do it!
        ob_start();
        ?>
        <html>
        <head>
        <style>
        body {
            font-family: 'calibri';
            font-size: 10pt;
        }
        p {    
            margin: 0pt;
        }
        td { 
            vertical-align: top; 
        }
        .items td {
            border-left: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        table thead td { background-color: #EEEEEE;
            text-align: center;
            border: 0.1mm solid #000000;
        }
        .items td.blanktotal {
            background-color: #FFFFFF;
            border: 0mm none #000000;
            border-top: 0.1mm solid #000000;
            border-right: 0.1mm solid #000000;
        }
        .items td.totals {
            text-align: right;
            border: 0.1mm solid #000000;
        }
        </style>
        </head>
        <body>
        <htmlpageheader name="myheader">
        <table width="100%">
            <tr>
                <td width="50%">
                    <?php if($options['file_upload'] != ''){ ?>
                        <img src="<?php echo $options['file_upload']; ?>"/><br /><br />
                    <?php }
                    else
                    { ?>
                        <span style="font-size: 16pt; font-weight: bold;"><?php echo $options['company_name']; ?></span><br />
                        <?php echo $options['company_slogan']; ?><br /><br />
                    <?php }
                    echo nl2br($options['address']); ?><br />
                </td>
                <td width="50%" style="text-align: right;">
                    <span style="font-size:22pt;">
                        <?php _e( 'INVOICE', 'woocommerce-pdf-invoices' ); ?><br />
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <?php echo nl2br($options['extra_company_info']); ?><br />
                </td>
                <td width="50%" style="font-size: 9pt; text-align:right;">
                    <?php _e( 'INVOICE NUMBER: ', 'woocommerce-pdf-invoices' ); echo $invoice_number; ?><br />
                    <?php printf( __( 'DATE: %02d-%02d-%04d', 'woocommerce-pdf-invoices' ), $today, $month, $year ); ?>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table width="100%">
            <tr>
                <td style="50%">
                    <span style="font-weight: bold;"><?php _e( 'TO:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $billing_address; ?>
                </td>
                <td style="50%">
                    <span style="font-weight: bold;"><?php _e( 'SHIP TO:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $shipping_address; ?>
                </td>
            </tr>
        </table>
        <br/>
        <?php if($options['display_customer_notes'] == 'yes'){ ?>
        <table>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?php _e( 'Notes:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $purchase_note; ?>
                </td>
            </tr>
        </table>
        <?php } ?>
        <br/>
        <br/>
        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr style="font-weight: bold">
                    <?php if($options['display_SKU'] == 'yes'){ 
                    $colspan = 3; ?>
                    <td width="15%"><?php _e( 'SKU', 'woocommerce-pdf-invoices' ); ?></td>
                    <?php } else { $colspan = 2; } ?>
                    <td width="45%"><?php _e( 'Description', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="10%"><?php _e( 'Quantity', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="15%"><?php _e( 'Unit price', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="15%"><?php _e( 'Total', 'woocommerce-pdf-invoices' ); ?></td>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php 
                $total_order_discount = $order->get_total_discount();
                $order_subtotal_excl_tax =- $total_order_discount;

                foreach ( $order->get_items() as $item ) {
                    $product = get_product($item['product_id']);

                    $item_tax = $order->get_item_tax($item, true);
                    $item_unit_price_incl_tax = $order->get_item_subtotal($item, false, false);
                    $item_unit_price_excl_tax = $item_unit_price_incl_tax - $item_tax;
                    $item_total_price_excl_tax = $item['qty'] * $item_unit_price_excl_tax;

                    $order_subtotal_excl_tax += $item_total_price_excl_tax;
                    ?>

                    <tr>
                        <?php if($options['display_SKU'] == 'yes'){ ?>
                        <td align='center'><?php echo $product->get_sku(); ?></td>
                        <?php } ?>
                        <td><?php echo $item['name']; ?></td>
                        <td align='center'><?php echo $item['qty']; ?></td>
                        <td align='right'><?php echo woocommerce_price($item_unit_price_excl_tax); ?></td>
                        <td align='right'><?php echo woocommerce_price($item_total_price_excl_tax); ?></td>
                    </tr>
                <?php } ?>
                <!-- END ITEMS HERE -->
                <tr>
                    <td class="blanktotal" colspan="<?php echo $colspan; ?>" rowspan="<?php echo $rowspan; ?>"></td>
                    <td class="totals"><?php _e( 'Discount', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($total_order_discount); ?></td>
                </tr>
                <tr>
                    <td class="totals"><?php _e( 'Subtotal', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($order_subtotal_excl_tax); ?></td>
                </tr>
                <tr>
                    <td class="totals"><?php _e( 'Shipping', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($order->get_shipping()); ?></td>
                </tr>
                <?php
                if($vat_rates != 0){
                    foreach($vat_rates as $rate){ ?>
                        <tr>
                            <td class="totals"><?php printf( __( 'VAT %s%%', 'woocommerce-pdf-invoices'), $rate ); ?></td>
                            <td class="totals"><?php echo woocommerce_price(($order->get_total() / (100+$rate)) * $rate); ?></td>
                        </tr>
                <?php }} ?>
                <tr>
                    <td class="totals"><strong><?php _e( 'Grand Total', 'woocommerce-pdf-invoices' ); ?></strong></td>
                    <td class="totals"><?php echo woocommerce_price($order->get_total()); ?></td>
                </tr>
            </tbody>
        </table>
        <br />
        <br />
        <table style="text-align: left; font-style: italic;">
            <tr>
                <td>
                    <?php echo nl2br($options['extra_info']); ?>
                </td>
            </tr>
        </table>
        </htmlpageheader>
        <htmlpagefooter name="myfooter">
            <div style="font-size: 9pt; text-align: center; padding-top: 3mm; ">
                <?php printf( __( 'Page %s of %s', 'woocommerce-pdf-invoices' ), "{PAGENO}", "{nb}"); ?>
            </div>
        </htmlpagefooter>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        </body>
        </html>

        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // Do the trick!
        $mpdf->WriteHTML($output);

        // Get upload folder and create filename.
        $uploads_dir = WP_CONTENT_DIR . '/uploads';
        $filename = '/' . $invoice_number . '.pdf';
        $full_path = $uploads_dir.$filename;

        // Upload invoice
        $mpdf->Output($full_path, 'F');

        return $full_path;
    }
    else
    {
        return "";
    }
}
}