<?php

class BEWPI_Invoice {

function generate_invoice($val, $id, $order){
    if($id == 'customer_invoice')
    {
        include(BEWPI_PLUGIN_DIR . '/mpdf/mpdf.php');

        $options = get_option('be_woocommerce_pdf_invoices');

        $items = $order->get_items();

        $today = date('d');

        $month = date('m');

        $year = date('Y');
        
        //$current_date = date('d-m-Y');

        $order_number = str_replace("#", "", $order->get_order_number());

        $invoice_number = $year . $order_number;

        $purchase_note = $order->customer_note;

        $billing_address = $order->get_formatted_billing_address();
        
        $shipping_address = $order->get_formatted_shipping_address();

        $order_total = $order->get_total();

        $total_tax = $order->get_total_tax();

        $order_subtotal = $order_total - $total_tax;

        $mpdf = new mPDF('win-1252','A4','','',20,15,48,25,10,10); 
        $mpdf->useOnlyCoreFonts = true; // false is default
        $mpdf->SetProtection(array('print'));
        $mpdf->SetDisplayMode('fullpage');

        // Get all items and output in table rows and datacells.
        $all_items;
        foreach ( $items as $item ) {
            $item_subtotal = $order->get_item_subtotal($item);
            $item_total = $item['qty'] * $item_subtotal;
            $formatted_item_subtotal = woocommerce_price($item_subtotal);
            $formatted_item_total = woocommerce_price($item_total);

            $all_items .= "<tr><td align='center'>" . $item['product_id'] . "</td>" .
            "<td align='center'>" . $item['qty'] . "</td>" .
            "<td>" . $item['name'] . "</td>" .
            "<td align='right'>" . $formatted_item_subtotal . "</td>" .
            "<td align='right'>" . $formatted_item_total . "</td>" .
            "</tr>";
        }

        // The HTML PDF invoice
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
                    echo $options['address']; ?><br />
                    <?php echo $options['zip_code'] . " " . $options['city']; ?><br />
                    <?php echo $options['country']; ?><br />
                </td>
                <td width="50%" style="text-align: right;">
                    <span style="font-size:22pt;">
                        <?php echo __( 'INVOICE', 'woocommerce-pdf-invoices' ); ?><br />
                    </span>
                </td>
            </tr>
            <tr>
                <td>
                    <?php echo __( 'Tel: ', 'woocommerce-pdf-invoices' ) . $options['telephone']; ?><br />
                    <?php echo __( 'Email: ', 'woocommerce-pdf-invoices' ) . $options['email']; ?>
                </td>
            </tr>
            <tr>
                <td width="50%">
                    <?php echo $options['extra_company_info']; ?><br />
                </td>
                <td width="50%" style="font-size: 9pt; text-align:right;">
                    <?php echo sprintf( __( 'INVOICE NUMBER: ', 'woocommerce-pdf-invoices' )); echo $invoice_number; ?><br />
                    <?php echo sprintf( __( 'DATE: %02d-%02d-%04d', 'woocommerce-pdf-invoices' ), $today, $month, $year); ?>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table width="100%">
            <tr>
                <td style="50%">
                    <span style="font-weight: bold;"><?php echo __( 'TO:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $billing_address; ?>
                </td>
                <td style="50%">
                    <span style="font-weight: bold;"><?php echo __( 'SHIP TO:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $shipping_address; ?>
                </td>
            </tr>
        </table>
        <br/>
        <table>
            <tr>
                <td>
                    <span style="font-weight: bold;"><?php echo __( 'Notes:', 'woocommerce-pdf-invoices' ); ?></span><br />
                    <?php echo $purchase_note; ?>
                </td>
            </tr>
        </table>
        <br/>
        <br/>
        <table class="items" width="100%" style="font-size: 9pt; border-collapse: collapse;" cellpadding="8">
            <thead>
                <tr style="font-weight: bold">
                    <td width="15%"><?php echo __( 'SKU', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="10%"><?php echo __( 'QUANTITY', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="45%"><?php echo __( 'DESCRIPTION', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="15%"><?php echo __( 'UNIT PRICE', 'woocommerce-pdf-invoices' ); ?></td>
                    <td width="15%"><?php echo __( 'TOTAL', 'woocommerce-pdf-invoices' ); ?></td>
                </tr>
            </thead>
            <tbody>
                <!-- ITEMS HERE -->
                <?php echo $all_items; ?>
                <!-- END ITEMS HERE -->
                <tr>
                    <td class="blanktotal" colspan="3" rowspan="6"></td>
                    <td class="totals"><?php echo __( 'SUBTOTAL', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($order_subtotal); ?></td>
                </tr>
                <tr>
                    <td class="totals"><?php echo __( 'SALES TAX', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($total_tax); ?></td>
                </tr>
                <tr>
                    <td class="totals"><?php echo __( 'SHIPPING & HANDLING', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($order->get_shipping()); ?></td>
                </tr>
                <tr>
                    <td class="totals"><?php echo __( 'TOTAL DUE', 'woocommerce-pdf-invoices' ); ?></td>
                    <td class="totals"><?php echo woocommerce_price($order->get_total()); ?></td>
                </tr>
            </tbody>
        </table>
        <br />
        <br />
        <table style="text-align: left; font-style: italic;">
            <tr>
                <td>
                    <?php echo $options['extra_info']; ?>
                </td>
            </tr>
        </table>
        </htmlpageheader>
        <htmlpagefooter name="myfooter">
            <div style="font-size: 9pt; text-align: center; padding-top: 3mm; ">
                <?php echo sprintf( __( 'Page %s of %s', 'woocommerce-pdf-invoices' ), "{PAGENO}", "{nb}"); ?>
            </div>
        </htmlpagefooter>

        <sethtmlpageheader name="myheader" value="on" show-this-page="1" />
        <sethtmlpagefooter name="myfooter" value="on" />
        </body>
        </html>

        <?php
        $output = ob_get_contents();
        ob_end_clean();

        // Yeah baby, do the trick!
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