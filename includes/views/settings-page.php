<script type="text/javascript">
    function removeImage(){
        var elem = document.getElementById('custom-logo-wrap');
        elem.parentNode.removeChild(elem);
        document.getElementById('hiddenField').value = '';
    }
</script>
<style>
    textarea{
        width:670px;
        height:90px;
    }
    input[type='text']{
        width:670px;
        margin-bottom:7px;
    }
</style>
<div class="wrap">
<h3>Woocommerce PDF Invoices Settings</h3>
<p>Please fill in the settings below. Woocommerce PDF Invoices generates a PDF invoice based upon the customer order and attaches it to the confirmation email.</p>

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
                <input required type="text" size="40" name="be_woocommerce_pdf_invoices[company_name]" value="<?php echo $options['company_name']; ?>" /><br/>
                <div style="font-style:italic;">
                    <span style="color:grey;">Add your company name here.</span>
                <div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Company slogan:</strong></th>
            <td>
                <input type="text" size="40" name="be_woocommerce_pdf_invoices[company_slogan]" value="<?php echo $options['company_slogan']; ?>" /><br/>
                <div style="font-style:italic;">
                    <span style="color:grey;">Add your company slogan here. You can leave it blank.</span>
                <div>
            </td>
        </tr>
        
        <tr valign="top">
        <th scope="row"><strong>Custom logo:</strong></th>
            <td>
                <input style="width:660px; margin-bottom:7px;" type="file" name="logo" accept="image/*" /><br />
                <div style="font-style:italic;">
                    <span style="color:grey;">Add your custom company logo. You can leave it blank, the plugin will use your company name.</span>
                <div>
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
                <textarea required style="width:670px;" name="be_woocommerce_pdf_invoices[address]" ><?php echo esc_textarea($options['address']); ?></textarea><br/>
                <div style="font-style:italic;">
                    <span style="color:grey;">Add your company address here.</span>
                <div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Additional company information:</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_company_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_company_info']); ?></textarea><br/>
                <div style="font-style:italic;">
                    <span style="color:grey;">Add some additional company information like a email address, telephone number, company number and tax number. You can leave it blank.</span>
                <div>
            </td>
        </tr>

        <tr valign="top">
        <th scope="row"><strong>Refunds policy, conditions etc.:</strong></th>
            <td>
                <textarea name="be_woocommerce_pdf_invoices[extra_info]" rows=6 cols=120 ><?php echo esc_textarea($options['extra_info']);?></textarea><br/>
                <div style="font-style:italic;">
                    <span style="color:grey;">Add some policies, conditions etc. It will be placed beyond the products table. You can leave it blank.</span>
                <div>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
</div>