<table class="company two-column">
    <tbody>
    <tr>
        <td class="logo">
            <?php if ( !empty( $this->template_options['bewpi_company_logo'] ) ) { ?>
                <img class="company-logo" src="<?php echo $this->template_options['bewpi_company_logo']; ?>"/>
            <?php } else { ?>
                <h1 class="company-logo"><?php echo $this->template_options['bewpi_company_name']; ?></h1>
            <?php } ?>
        </td>
        <td class="info">
            <?php echo nl2br( $this->template_options['bewpi_company_address'] ); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="two-column customer">
    <tbody>
    <tr>
        <td class="address small-font">
            <b><?php _e( 'Invoice to', $this->textdomain ); ?></b><br/>
            <?php echo $this->order->get_formatted_billing_address(); ?>
        </td>
        <td class="address small-font">
            <b><?php _e( 'Ship to', $this->textdomain ); ?></b><br/>
            <?php echo $this->order->get_formatted_shipping_address(); ?>
        </td>
    </tr>
    </tbody>
</table>
<table class="invoice-head">
    <tbody>
    <tr>
        <td class="invoice-details">
            <h1 class="title"><?php _e( 'Invoice', $this->textdomain ); ?></h1>
            <span class="number" style="color: <?php echo $this->template_options['bewpi_color_theme']; ?>;"><?php echo $this->get_formatted_number(); ?></span><br/>
            <span class="small-font"><?php echo $this->get_formatted_invoice_date(); ?></span><br/><br/>
            <span class="small-font"><?php printf( __( 'Order Number %s', $this->textdomain ), $this->order->get_order_number() ); ?></span><br/>
            <span class="small-font"><?php printf( __( 'Order Date %s', $this->textdomain ), $this->get_formatted_order_date() ); ?></span><br/>
        </td>
        <td class="total-amount" bgcolor="<?php echo $this->template_options['bewpi_color_theme']; ?>">
				<span>
					<h1 class="amount"><?php echo wc_price( $this->order->get_total(), array( 'currency' => $this->order->get_order_currency() ) ); ?></h1>
					<p class="small-font"><?php echo $this->template_options['bewpi_intro_text']; ?></p>
				</span>
        </td>
    </tr>
    </tbody>
</table>