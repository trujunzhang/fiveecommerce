<html>
<head>

	<style>
		@import url(http://fonts.googleapis.com/css?family=Roboto:400,500,700,400italic,500italic,700italic);

		body {
			font-family: 'Roboto', sans-serif;
			font-size: 16px;
			line-height: 21px;
			width: 1000px;
			margin: 0 auto;
		}

		* {
			-webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
			-moz-box-sizing: border-box;    /* Firefox, other Gecko */
			box-sizing: border-box;         /* Opera/IE 8+ */
		}

		img {
			max-width: 100%;
			display: block;
			width : auto;
			height: auto;
		}

		ul {
			margin: 0;
			padding: 0;
			list-style: none;
		}

		small {
			font-size: 13px;
		}

		.row {
			display: inline-block;
			width: 100%;
			margin-bottom: 30px;
			clear: both;
		}

		.columns2 {
			width: 50%;
			float: left;
			min-height: 1px;
		}

		.columns3 {
			width: 33.33333333%;
			float: left;
			padding: 18px 0 18px 23px;
			min-height: 1px;
		}

		.header-section {
			padding: 10px 0 10px 13px;
		}
		.logo {
			margin-bottom: 30px;
			min-height: 38px;
		}
		.logo img {
			max-height: 100px;
			margin: 0;
		}
		.seller-name {
			font-size: 24px;
			font-weight: bold;
			margin: 0 0 10px;
		}
		.invoice-title {
			color: #6699cc;
			font-size: 56px;
			font-weight: 500;
			line-height: 1;
			margin: 50px 0 17px 0;
		}
		.invoice-details {
			position: relative;
			background-color: #eaf5ff;
		}
		.invoice-text {
			display: block;
		}
		.invoice-info {
			font-weight: bold;
			font-size: 17px;
		}
		.invoice-info del {
			font-weight: normal;
			font-size: 12px;
		}

		.title {
			font-weight: bold;
		}
		.buyer-name {
			font-size: 22px;
			line-height: 26px;
			font-weight: bold;
			margin: 10px 0 8px;
		}
		.shipping-method {
			margin-top: 10px;
			font-size: 14px;
		}

		.content-header,
		.content-footer,
		.product-item {
			width: 100%;
			display: table;
			table-layout: fixed;
		}
		.content-header {
			padding: 13px;
			font-size: 13px;
			line-height: 1;
		}
		.content-header span,
		.content-footer span,
		.product-item span {
			display: table-cell;
			vertical-align: top;
		}
		.content-body {
			border-bottom: 1px solid #eaf5ff;
		}
		.content-body .product-item:nth-child(odd) {
			background-color: #eaf5ff;
		}
		.product-item {
			font-size: 13px;
			padding: 12px 13px;
		}

		/* table header */
		.name {
			width: 36%;
			text-align: left;
			overflow: hidden;
		}
		.unit-price,
		.tax-total {
			width: 11%;
			text-align: right;
			overflow: hidden;
		}
		.tax-percent,
		.quantity {
			width: 9%;
			text-align: right;
			overflow: hidden;
		}
		.net-price,
		.total {
			width: 12%;
			text-align: right;
			overflow: hidden;
		}


		div .amount {
			display: block;
		}


		.content-footer {
			margin-top: 9px;
		}
		ins {
			text-decoration: none;
		}
		.content-footer .section-text {
			width: 50%;
		}
		.content-footer .section-info {
			width: 50%;
			padding: 6px 0;
		}
		.content-footer .section {
			border: 0;
		}
		.section {
			text-align: right;
			display: table;
			width: 100%;
			padding-right: 13px;
			position: relative;
			font-weight: bold;
		}
		.section small {
			position: absolute;
			top: 30px;
			right: 0;
			width: 100%;
			padding-right: 13px;
			font-weight: normal;
		}
		.section .amount {
			float: right;
		}

		.section-text {
			padding: 6px 13px 0;
			display: inline-block;
		}

		.section-info {
			padding: 6px 0 40px;
			vertical-align: top;
			display: inline-block;
			min-width: 100px;
		}
		.section-total {
			background-color: #eaf5ff;
			font-weight: normal;
			font-size: 13px;
			padding: 10px 13px;
			margin: 20px 0 10px;
		}

		.section-total .section-text {
			font-weight: bold;
			font-size: 26px;
		}

		.section-total .amount {
			font-weight: bold;
			font-size: 26px;
			width: 100%;
		}
		.section-total del {
			display: block;
		}

		.section-total .amount:nth-child(2) {
			font-weight: bold;
			font-size: 16px;
		}

		.signature {
			text-align: center;
			margin: 15px 0 30px;
		}
		.signature-image img {
			margin-bottom: 26px;
			max-height: 100px;
			display: inline-block;
		}
		.signature-name {
			font-size: 14px;
		}
		.signature-account {
			font-weight: bold;
		}

		.footer-note {
			font-size: 13px;
			line-height: 16px;
			display: block;
			width: 100%;
			clear: both;
		}
		.footer-terms {
			padding: 0 13px;
		}
		.footer-line {
			display: block;
			background-color: #eaf5ff;
			height: 5px;
			margin-top: 5px;
		}

		/* print button */
		.print-button {
			background-color: #222;
			color: #fff;
			padding: 16px 40px;
			display: table;
			text-decoration: none;
			margin: 20px 0 20px auto;
		}
		.print-button:hover {
			opacity: 0.9;
		}


		@media print {
			@page {
				margin: 1cm;
			}

			.footer-note {
				position: fixed;
				bottom: 0;
			}

			.print-button {
				display: none;
			}
		}
	</style>

</head>

<body>
<!-- header section -->
<header class="header-section">

	<a id="printing" class="print-button" href="#print" onclick="window.print(); return false;" title="<?php _e('Print the invoice', 'td_woo_invoice'); ?>"><?php _e('Print the invoice', 'td_woo_invoice'); ?></a>

	<!-- invoice header -->
	<div class="row">

		<!-- seller details -->
		<div class="columns2">
			<div class="logo">
				<?php
				if (!empty($invoice_settings['panel_settings']['content_seller_logo']))
					echo '<img src="' . $invoice_settings['panel_settings']['content_seller_logo'] . '" />';
				?>
			</div>
			<div class="seller-name"><?php echo $invoice_settings['panel_settings']['content_seller_name'] ?></div>
			<div class="seller-details"><?php echo $invoice_settings['panel_settings']['content_seller_details'] ?></div>
		</div>

		<!-- invoice -->
		<div class="columns2">
			<!-- invoice title -->
			<div class="invoice-title"><?php echo $invoice_settings['panel_settings']['l10n_invoice'] ?></div>

			<!-- invoice details -->
			<div class="invoice-details row">
				<!-- invoice total -->
				<div class="columns3">
					<span class="invoice-text"><?php echo $invoice_settings['panel_settings']['l10n_total'] ?>:</span>
					<span class="invoice-info"><?php echo $invoice_settings['order_data']['order_formatted_total'] ?></span>
				</div>

				<!-- invoice date -->
				<div class="columns3">
					<span class="invoice-text"><?php echo $invoice_settings['panel_settings']['l10n_invoice_date'] ?>:</span>
					<span class="invoice-info"><?php echo $invoice_settings['invoice_date'] ?></span>
				</div>

				<!-- invoice number -->
				<div class="columns3">
					<span class="invoice-text"><?php echo $invoice_settings['panel_settings']['l10n_invoice_no'] ?>:</span>
					<span class="invoice-info"><?php echo $invoice_settings['invoice_number'] ?></span>
				</div>
			</div>
		</div>
	</div>

	<!-- buyer details -->
	<div class="row">

		<!-- invoice to -->
		<div class="columns2">
			<div class="title"><?php echo $invoice_settings['panel_settings']['l10n_invoice_to'] ?>:</div>
			<div class="buyer-name">
				<?php
				echo $invoice_settings['order_data']['billing_first_name'] . ' ' . $invoice_settings['order_data']['billing_last_name'] . '<br/>';

				if ( ! empty( $invoice_settings['order_data']['billing_company'] ) ) {
					echo $invoice_settings['order_data']['billing_company'];
				}
				?>
			</div>
			<ul class="buyer-details">
				<li><?php echo $invoice_settings['order_data']['billing_address_1'] ?></li>
				<li><?php echo $invoice_settings['order_data']['billing_state'] . ' ' . $invoice_settings['order_data']['billing_city'] . ' ' . $invoice_settings['order_data']['billing_postcode'] ?></li>
				<li><?php echo $invoice_settings['order_data']['billing_country'] . ' ' . $invoice_settings['order_data']['billing_country_code'] ?></li>
				<li><?php echo $invoice_settings['order_data']['billing_email'] ?></li>
				<li><?php echo $invoice_settings['order_data']['billing_phone'] ?></li>
			</ul>
		</div>

		<!-- shipping details -->
		<div class="columns2">
			<div class="title"><?php echo $invoice_settings['panel_settings']['l10n_shipping_details'] ?>:</div>
			<div class="buyer-name">
				<?php
				echo $invoice_settings['order_data']['shipping_first_name'] . ' ' . $invoice_settings['order_data']['shipping_last_name'] . '<br/>';

				if ( ! empty( $invoice_settings['order_data']['shipping_company'] ) ) {
					echo $invoice_settings['order_data']['shipping_company'];
				}
				?>
			</div>
			<ul class="buyer-details">
				<li><?php echo $invoice_settings['order_data']['shipping_address_1'] ?></li>
				<li><?php echo $invoice_settings['order_data']['shipping_state'] . ' ' . $invoice_settings['order_data']['shipping_city'] .' ' . $invoice_settings['order_data']['shipping_postcode'] ?></li>
				<li><?php echo $invoice_settings['order_data']['shipping_country'] . ' ' . $invoice_settings['order_data']['shipping_country_code'] ?></li>
			</ul>

			<?php
				if ( ! empty( $invoice_settings['order_data']['shipping_method'] ) ) {
				?>

					<div class="shipping-method"><b><?php echo $invoice_settings['panel_settings']['l10n_shipping_method'] ?>:</b> <?php echo $invoice_settings['order_data']['shipping_method'] ?></div>

				<?php
				}
			?>
		</div>

	</div>
</header>

<!-- content section -->
<div class="content-section">
	<!-- content header -->
	<div class="content-header title">
		<span class="name"><?php echo $invoice_settings['panel_settings']['l10n_product'] ?></span>
		<span class="unit-price"><?php echo $invoice_settings['panel_settings']['l10n_unit_price'] ?></span>
		<span class="quantity"><?php echo $invoice_settings['panel_settings']['l10n_quantity'] ?></span>
		<span class="net-price"><?php echo $invoice_settings['panel_settings']['l10n_net_price'] ?></span>
		<span class="tax-total"><?php echo $invoice_settings['panel_settings']['l10n_tax_total'] ?></span>
		<span class="tax-percent"><?php echo $invoice_settings['panel_settings']['l10n_tax_percent'] ?></span>
		<span class="total"><?php echo $invoice_settings['panel_settings']['l10n_total'] ?></span>
	</div>

	<?php

	ob_start();

	if ( count( $invoice_settings['order_data'] ) ) {

		// The formatting price arguments.
		$td_price_args = array(
			'currency'          => $invoice_settings['order_data']['order_currency'],
			'decimal_separator' => $invoice_settings['order_data']['wc_price_decimal_separator'],
			'thousand_separator'=> $invoice_settings['order_data']['wc_price_thousand_separator'],
			'decimals'          => $invoice_settings['order_data']['wc_price_decimals'],
			'price_format'      => $invoice_settings['order_data']['wc_price_format'],
		);

		$order_item_total = $invoice_settings['order_data']['order_items_total'];

		?>

		<!-- table products section -->
		<div class="content-body">
			<?php
			foreach ( $invoice_settings['order_data']['items'] as $item ) {
				?>

				<div class="product-item">
					<span class="name"><?php echo $item['item_data']['name'] ?></span>
					<span class="unit-price"><?php

						// check division by zero
						if ( ! empty( $item['item_data']['qty'] )) {
							echo wc_price( round( $item['item_data']['line_subtotal'] / $item['item_data']['qty'], 2 ), $td_price_args);
						} else {
							echo '-';
						}
					?>
					</span>
					<span class="quantity"><?php echo $item['item_data']['qty'] ?></span>
					<span class="net-price"><?php echo wc_price( round( $item['item_data']['line_subtotal'], 2 ), $td_price_args) ?></span>
					<span class="tax-total"><?php echo  wc_price( $item['item_data']['line_subtotal_tax'], $td_price_args) ?></span>
					<span class="tax-percent"><?php

						// check division by zero
						if ( ! empty( $item['item_data']['line_subtotal'] )) {
							echo round( ( $item['item_data']['line_subtotal_tax'] / $item['item_data']['line_subtotal'] ) * 100 , 2 ) . '%';
						} else {
							echo '-';
						}
					?>
					</span>
					<span class="total"><?php echo $item['item_formated_line_subtotal'] ?></span>
				</div>

				<?php
			}
		?>

		</div>




		<?php

		// Subsections.
		if ( isset( $order_item_total ) ) {


			// Order Total of the order total item settings, is marked as being checked, to be skipped
			// when it is checked at the bottom of the invoice template. It will be shown later, at the bottom.
			if ( isset( $order_item_total['order_total'] ) ) {
				$order_item_total['order_total']['td_checked'] = true;
			}



			// Subtotal section.
			if ( isset( $order_item_total['cart_subtotal'] ) ) {

				// Cart Subtotal of the order total item settings, is marked as being checked, to be skipped
				// when it is checked at the bottom of the invoice template.
				$order_item_total['cart_subtotal']['td_checked'] = true;
				?>

				<div class="section">
					<span class="section-text">

						<?php
						if ( isset( $invoice_settings['panel_settings']['l10n_cart_subtotal'] ) ) {
							echo $invoice_settings['panel_settings']['l10n_cart_subtotal'];
						} else {
							echo $order_item_total['cart_subtotal']['label'];
						}
						?>

					</span>
					<span class="section-info"><?php echo $order_item_total['cart_subtotal']['value'] ?></span>
				</div>

			<?php
			}


			// Discount section.
			if ( isset( $order_item_total['discount'] ) and isset( $invoice_settings['order_data']['discount'] ) and isset( $invoice_settings['order_data']['discount_ex_tax'] ) ) {

				// Discount of the order total item settings, is marked as being checked, to be skipped
				// when it is checked at the bottom of the invoice template.
				$order_item_total['discount']['td_checked'] = true;
				?>

				<!-- table discount section -->
				<div class="content-body">
					<div class="product-item product-item-border">
						<span class="name"><?php echo $invoice_settings['panel_settings']['l10n_discount'] ?></span>
						<span class="unit-price"></span>
						<span class="quantity"></span>
						<span class="net-price"><?php echo wc_price( $invoice_settings['order_data']['discount_ex_tax'], $td_price_args ); ?></span>
						<span class="tax-total"><?php echo wc_price( $invoice_settings['order_data']['discount'] - $invoice_settings['order_data']['discount_ex_tax'], $td_price_args ) ?></span>
						<span class="tax-percent"></span>
						<span class="total"><?php echo wc_price( $invoice_settings['order_data']['discount'], $td_price_args ) ?></span>
					</div>
				</div>

				<div class="section">
					<span class="section-text">

						<?php
						if ( isset( $invoice_settings['panel_settings']['l10n_discount'] ) ) {
							echo $invoice_settings['panel_settings']['l10n_discount'];
						} else {
							echo $order_item_total['discount']['label'];
						}
						?>

					</span>
					<span class="section-info"><?php echo $order_item_total['discount']['value'] ?></span>
				</div>

			<?php

			}


			// Shipping section.
			if ( ! empty( $invoice_settings['order_data']['order_shipping'] )) {

				// Shipping of the order total item settings, is marked as being checked, to be skipped
				// when it is checked at the bottom of the invoice template.
				if ( isset( $order_item_total['shipping'] ) ) {
					$order_item_total['shipping']['td_checked'] = true;
				}
				?>

				<!-- table shipping section -->
				<div class="content-body">
					<div class="product-item product-item-border">
						<span class="name"><?php echo $invoice_settings['panel_settings']['l10n_shipping'] ?></span>
						<span class="unit-price"></span>
						<span class="quantity"></span>
						<span class="net-price"><?php echo wc_price( $invoice_settings['order_data']['order_shipping'], $td_price_args ); ?></span>
						<span class="tax-total"><?php echo wc_price( $invoice_settings['order_data']['order_shipping_tax'], $td_price_args ) ?></span>
						<span class="tax-percent"></span>
						<span class="total"><?php echo wc_price( $invoice_settings['order_data']['order_shipping'] + $invoice_settings['order_data']['order_shipping_tax'], $td_price_args ) ?></span>
					</div>
				</div>
				<div class="section">
					<span class="section-text">
						<?php
						if ( isset( $invoice_settings['panel_settings']['l10n_shipping'] ) ) {
							echo $invoice_settings['panel_settings']['l10n_shipping'];
						}
						?>
					</span>
					<span class="section-info"><?php echo wc_price( $invoice_settings['order_data']['order_shipping'] + $invoice_settings['order_data']['order_shipping_tax'], $td_price_args ) . '&nbsp;<small>via ' . $invoice_settings['order_data']['shipping_method'] ?></small></span>
				</div>

				<?php
			}


			// fee sections
			if ( ! empty( $invoice_settings['order_data']['fees'] )) {

				// Fees of the order total item settings, are marked as being checked, to be skipped
				// when they are checked at the bottom of the invoice template.
				foreach ( $order_item_total as $order_item_key => $order_item_value ) {
					if ( ( strpos( $order_item_key, 'fee_' ) !== false ) and ( strpos( $order_item_key, 'fee_' ) === 0 ) ) {
						$order_item_total[$order_item_key]['td_checked'] = true;
					}
				}

				?>

				<!-- table fee section -->
				<div class="content-body">

					<?php
					foreach ( $invoice_settings['order_data']['fees'] as $fee ) {
						?>

						<div class="product-item product-item-border">
							<span class="name"><?php echo( empty ( $fee['name'] ) ? 'fee' : $fee['name'] ) ?></span>
							<span class="unit-price"></span>
							<span class="quantity"></span>
							<span class="net-price"><?php echo wc_price( $fee['line_subtotal'], $td_price_args ); ?></span>
							<span class="tax-total"><?php echo wc_price( $fee['line_subtotal_tax'], $td_price_args ) ?></span>
							<span class="tax-percent"></span>
							<span class="total"><?php echo wc_price( $fee['line_subtotal'] + $fee['line_subtotal_tax'], $td_price_args ) ?></span>
						</div>

					<?php
					}
					?>

				</div>

				<?php
			}
		}
		?>

		<!-- content footer -->
		<div class="content-footer">
			<div class="row">
				<div class="columns2"></div>
				<div class="columns2">

					<?php
					if ( isset( $order_item_total ) ) {

						// Show the sections that are not marked as checked.

						foreach ( $order_item_total as $order_item_key => $order_item_value ) {
							if ( ! isset( $order_item_value['td_checked'] ) and $order_item_key !== 'order_total' ) {

								$order_item_value['td_checked'] = true;

								?>

								<div class="section">
										<span class="section-text">

											<?php
											if ( isset( $invoice_settings['panel_settings'][ 'l10n_' . $order_item_key ] ) ) {
												echo $invoice_settings['panel_settings'][ 'l10n_' . $order_item_key ];
											} else {
												echo $order_item_value['label'];
											}
											?>

										</span>
									<span class="section-info"><?php echo $order_item_value['value'] ?></span>
								</div>

							<?php
							}
						}


						// total order

						if ( isset( $order_item_total['order_total'] ) ) {
							?>

							<div class="section section-total">
								<span class="section-text"><?php echo $invoice_settings['panel_settings']['l10n_total'] ?>:</span>
								<span class="section-info"><?php echo $order_item_total['order_total']['value'] ?></span>
							</div>

						<?php
						}


						// total refund

						if ( isset( $invoice_settings['order_data']['total_refund'] )) {
							?>

							<div class="section">
								<span class="section-text"><?php echo $invoice_settings['panel_settings']['l10n_total_refund'] ?>:</span>
								<span class="section-info"><?php echo wc_price( -1 * abs( floatval( $invoice_settings['order_data']['total_refund'] )), $td_price_args) ?></span>
							</div>

						<?php
						}


						// taxes

						if ( isset( $invoice_settings['order_data']['tax_totals'] )) {
							foreach ($invoice_settings['order_data']['tax_totals'] as $tax_total_key => $tax_total_value) {

								$compound_tax = '';
								if ( 1 == $tax_total_value->is_compound ) {
									$compound_tax = 'compound';
								}
								?>

								<div class="section">
									<span class="section-text"><?php echo $tax_total_value->label . ' (' . WC_Tax::get_rate_percent( $tax_total_value->rate_id ) . " $compound_tax)" ?>:</span>
									<span class="section-info"><?php echo wc_price( $tax_total_value->amount, $td_price_args) ?></span>
								</div>

								<?php
							}
						}
					}
					?>
				</div>
			</div>
		</div>

	<?php
	}

	$content = ob_get_clean();
	echo $content;

	?>

</div>



<!-- footer section -->
<footer class="footer-section">
	<!-- signature section -->
	<div class="row">
		<!-- empty column -->
		<div class="columns2"></div>

		<!-- signature details -->
		<div class="signature columns2">
			<div class="signature-image">

				<?php
				if (!empty($invoice_settings['panel_settings']['content_signature_image']))
					echo '<img src="' . $invoice_settings['panel_settings']['content_signature_image'] . '" />';
				?>

				<div class="signature-name"><?php echo $invoice_settings['panel_settings']['content_signature_name'] ?></div>
				<div class="signature-account"><?php echo $invoice_settings['panel_settings']['content_signature_account'] ?></div>
			</div>
		</div>

		<!-- note section -->
		<div class="footer-note">

			<?php
			if (!empty($invoice_settings['panel_settings']['content_terms'])) {
			?>

				<span class="footer-terms"><b><?php echo $invoice_settings['panel_settings']['l10n_terms'] ?>:</b> <?php echo $invoice_settings['panel_settings']['content_terms'] ?></span>
				<span class="footer-line"></span>

			<?php
			}
			?>

		</div>
</footer>
</body>
</html>