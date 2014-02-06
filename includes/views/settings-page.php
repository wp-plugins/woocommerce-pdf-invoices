<script type="text/javascript">
    function removeImage(){
        var elem = document.getElementById('custom-logo-wrap');
        elem.parentNode.removeChild(elem);
        document.getElementById('hiddenField').value = '';
    }
</script>
<div class="wrap">
<h2>WooCommerce PDF Invoices</h2>
<form method="post" action="options.php" enctype="multipart/form-data">
    <?php settings_fields( 'be_woocommerce_pdf_invoices' );
    do_settings_sections( 'be_woocommerce_pdf_invoices' );
    $today = date('d');
    $month = date('m');
    $year = date('Y');?>
    <h3>General</h3>
    <style>
    br {display:none;}
    </style>
    <table class="form-table">
    <tr valign="top">
        <th scope="row"><strong>Email</strong></th>
            <td>
                <select id="selectEmailType" name="be_woocommerce_pdf_invoices[email_type]">
                    <option>-- Select --</option>
                    <option value="customer_processing_order" <?php selected($options['email_type'], 'customer_processing_order'); ?>>Customer processing order</option>
                    <option value="customer_completed_order" <?php selected($options['email_type'], 'customer_completed_order'); ?>>Customer completed order</option>
                    <option value="customer_invoice" <?php selected($options['email_type'], 'customer_invoice'); ?>>Customer invoice</option>
                </select>
                <div class="notes">
                    Select the type of email to attach the invoice to.
                </div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Attach to New Order email type</strong></th>
            <td>
                <select id="selectAdminNewOrder" name="be_woocommerce_pdf_invoices[attach_to_new_order]">
                    <option value="new_order" <?php selected($options['attach_to_new_order'], 'new_order'); ?>>Yes</option>
                    <option value="no" <?php selected($options['attach_to_new_order'], 'no'); ?>>No</option>
                </select>
                <div class="notes">
                    Determine to attach the invoice to the "New Order" email type for bookkeeping purposes. 
                </div>
            </td>
        </tr>
        <th scope="row"><strong>Invoice number format &amp; starting point</strong></th><br/>
            <td>
                <select name="be_woocommerce_pdf_invoices[invoice_number]">
                    <option value="1" <?php selected($options['invoice_number'], '1'); ?>><?php echo "0001"; ?></option>
                    <option value="2" <?php selected($options['invoice_number'], '2'); ?>><?php echo $year.$month.$today."-0001"; ?></option>
                    <option value="3" <?php selected($options['invoice_number'], '3'); ?>><?php echo substr($year, -2)."-0001"; ?></option>
                </select>
                <input id="startAt" type="text" name="be_woocommerce_pdf_invoices[invoice_number_start]" value="<?php echo $options['invoice_number_start']; ?>" />
                <div class="notes">Choose how to generate the invoice number by selecting one of the example formats. Optionally add number to start count.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>VAT rates</strong></th>
            <td>
                <input type="text" name="be_woocommerce_pdf_invoices[vat_rates]" value="<?php echo $options['vat_rates'];?>"/><br/>
                <div class="notes">Add tax rates seperated by comma. These rates will be calculated based upon the subtotal price.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Display customer notes</strong></th>
            <td>
                <select id="selectDisplayCustomerNotes" name="be_woocommerce_pdf_invoices[display_customer_notes]">
                    <option value="yes" <?php selected($options['display_customer_notes'], 'yes'); ?>>Yes</option>
                    <option value="no" <?php selected($options['display_customer_notes'], 'no'); ?>>No</option>
                </select>
                <div class="notes">
                    Choose to display customer notes. 
                </div>
            </td>
        </tr>
        <th scope="row"><strong>Display SKU</strong></th>
            <td>
                <select id="selectDisplaySKU" name="be_woocommerce_pdf_invoices[display_SKU]">
                    <option value="yes" <?php selected($options['display_SKU'], 'yes'); ?>>Yes</option>
                    <option value="no" <?php selected($options['display_SKU'], 'no'); ?>>No</option>
                </select>
                <div class="notes">
                    Choose to display SKU into table. 
                </div>
            </td>
        </tr>
    </table>
    <h3>Template</h3>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><strong>Company name</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[company_name]" value="<?php echo $options['company_name']; ?>" /><br/>
                <div class="notes">Add your company name here.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Company slogan</strong></th>
            <td>
                <input type="text" size="40" name="be_woocommerce_pdf_invoices[company_slogan]" value="<?php echo $options['company_slogan']; ?>" /><br/>
                <div class="notes">Add your company slogan here. You can leave it blank.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Custom logo</strong></th>
            <td>
                <input id="uploadFile" type="file" name="logo" accept="image/*" /><br />
                <div class="notes">Add your custom company logo. Please upload image with a size behond 50kB. You don't have to upload any, the plugin will use your company name.</div>
                <?php if($options['file_upload'] != ''){ ?>
                <div id="custom-logo-wrap">
                    <img id="custom-logo" src="<?php echo esc_attr($options['file_upload']); ?>" /><br/ >
                    <a href="#" title="Remove custom logo" onclick="removeImage();">
                        <img id="delete" src="<?php echo plugins_url().'/woocommerce/assets/images/icons/delete_10.png'?>" />
                    </a>
                </div>
                <?php } ?>
                <input type="hidden" id="hiddenField" name="be_woocommerce_pdf_invoices[file_upload]" value="<?php echo esc_attr($options['file_upload']); ?>" />
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Company address</strong></th>
            <td>
                <textarea required name="be_woocommerce_pdf_invoices[address]" ><?php echo esc_textarea($options['address']); ?></textarea><br/>
                <div class="notes">Add your company address here.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Additional company information</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_company_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_company_info']); ?></textarea><br/>
                <div class="notes">Add some additional company information like a email address, telephone number, company number and tax number. You can leave it blank.</div>
            </td>
        </tr>
        <tr valign="top">
        <th scope="row"><strong>Refunds policy, conditions etc.</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_info']);?></textarea><br/>
                <div class="notes">Add some policies, conditions etc. It will be placed beyond the products table. You can leave it blank.</div>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
    <p>
        Thank you for using my FREE plugin. If you have any suggestions, please use the 
        <a href="http://wordpress.org/support/plugin/woocommerce-pdf-invoices"/>support forum</a> or feel free to contact me right away.
        Please <a href="http://wordpress.org/support/view/plugin-reviews/woocommerce-pdf-invoices?rate=5#postform"/>rate</a> with 5 stars as a service on return.
    </p>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=baselbers%40hotmail%2ecom&lc=NL&item_name=WooCommerce%20PDF%20Invoices&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted">
        <img alt="Donate Button" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" />
    </a>
</form>
</div>