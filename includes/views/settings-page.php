<script type="text/javascript">
    function removeImage(){
        var elem = document.getElementById('custom-logo-wrap');
        elem.parentNode.removeChild(elem);
        document.getElementById('hiddenField').value = '';
    }
</script>
<div class="wrap">
<h2>Woocommerc PDF Invoices</h2>
<h3>Woocommerce PDF Invoices Settings</h3>
<p>Please fill in the settings below. Woocommerce PDF Invoices generates a PDF invoice based upon the customer order and attaches it to the confirmation email. 
    You can leave the custom logo blank, the plugin will use your company name.</p>

<form method="post" action="options.php" enctype="multipart/form-data">
    <?php 
    settings_fields( 'be_woocommerce_pdf_invoices' );
    do_settings_sections( 'be_woocommerce_pdf_invoices' );
    $plugin_url = plugins_url();
    ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row"><strong>Company name:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[company_name]" value="<?php echo $options['company_name']; ?>" />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company slogan:</strong></th>
            <td>
                <input type="text" size="40" name="be_woocommerce_pdf_invoices[company_slogan]" value="<?php echo $options['company_slogan']; ?>" />
            </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><strong>Custom logo:</strong></th>
            <td>
                <input type="file" name="logo" accept="image/*" /><br />
                <?php if($options['file_upload'] != ''){ ?>
                <style>
                #delete{
                    position: absolute; top: 16px; right: 0px; background: #f7f7f7; border: 1px solid #d5d5d5; padding: 1px; display: none;
                }
                #custom-logo-wrap:hover #delete{
                    display: block;
                } 
                </style>
                <div id="custom-logo-wrap" style="position: relative; display: inline-block; padding-top: 16px;">
                    <img id="custom-logo" src="<?php echo esc_attr($options['file_upload']); ?>" /><br/ >
                    <a href="#" title="Remove custom logo" onclick="removeImage();">
                        <img id="delete" src="<?php echo $plugin_url.'/woocommerce/assets/images/icons/delete_10.png'?>" />
                    </a>
                </div>
                <?php } ?>
                <input type="hidden" id="hiddenField" name="be_woocommerce_pdf_invoices[file_upload]" value="<?php echo esc_attr($options['file_upload']); ?>" />
            </td>
        </tr>
         
        <tr valign="top">
        <th scope="row"><strong>Company address:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[address]" value="<?php echo esc_attr($options['address']); ?>" /><br />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company ZIP code:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[zip_code]" value="<?php echo esc_attr($options['zip_code']); ?>" />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company city:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[city]" value="<?php echo esc_attr($options['city']); ?>" />
            </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><strong>Company country:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[country]" value="<?php echo esc_attr($options['country']); ?>" />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company telephone number:</strong></th>
            <td>
                <input type="text" size="40" name="be_woocommerce_pdf_invoices[telephone]" value="<?php echo esc_attr($options['telephone']); ?>" />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company email:</strong></th>
            <td>
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[email]" value="<?php echo esc_attr($options['email']); ?>" />
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company information:</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_company_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_company_info']); ?></textarea><br/>
                <span style="font-style:italic; font-weight:bold;">Note: </span><span style="font-style:italic; color:grey;">Use <?php echo htmlspecialchars("<b />"); ?> to get a line break.</span>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Payment information:</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_info']);?></textarea><br/>
                <span style="font-style:italic; font-weight:bold;">Note: </span><span style="font-style:italic; color:grey;">Use <?php echo htmlspecialchars("<b />"); ?> to get a line break.</span>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>