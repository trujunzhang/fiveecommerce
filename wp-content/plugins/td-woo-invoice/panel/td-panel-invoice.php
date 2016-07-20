<?php
/**
 * Invoice settings.
 *
 * @package td-woo-invoice\td-panel-invoice
 */

echo td_panel_generator::box_start( 'Settings' );

?>

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Invoice Numbering</span>

		<p>Each invoice should have a unique number (prefix - index counter - suffix).</p>
	</div>
</div>

<!-- paste your code here -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Next number</span>

		<p>It's the numeric indexed counter of the next invoice number. Modify it carefully, because moving it backward
			you will actually overload your next generated invoices, and vice-versa, moving it forward you'll get a gap
			in your list invoices, next invoice having your number.</p>
	</div>
	<div class="td-box-control-full" id="td_invoice_numbering_next_number">
		<?php
		echo td_panel_generator::input( array(
			'ds'          => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id'   => 'invoice_numbering_next_number',
			'placeholder' => TD_Woo_Invoice_Settings::get_next_number(),
		) );
		?>
	</div>
</div>

<script>
	jQuery(window).ready(function ($) {
		$('#td_invoice_numbering_next_number').find('input').val('');
	});
</script>

<!-- paste your code here -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Prefix</span>

		<p>The prefix will start the invoice number. It's optional</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'invoice_numbering_prefix',
		) );
		?>
	</div>
</div>

<!-- paste your code here -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Suffix</span>

		<p>The suffix will trail the invoice number. It's optional</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'invoice_numbering_suffix',
		) );
		?>
	</div>
</div>


<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Other settings</span>

		<p>There are a couple of settings used to customize the invoice.</p>
	</div>
</div>

<!-- paste your code here -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Order status</span>

		<p>Status of the order when its generated invoice is accessible to buyer.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::dropdown( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'status_order_when_invoice_in_myaccount',
			'values'    => TD_Woo_Invoice_Panel_Settings::get_order_statuses(),
		) );
		?>
	</div>
</div>


<?php echo td_panel_generator::box_end(); ?>


<!-- Content Settings -->
<?php echo td_panel_generator::box_start( 'Content', false ); ?>

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Content Settings</span>

		<p>Some content settings that should be completed for having a real invoice, even they are optional</p>
	</div>
</div>

<!-- LOGO UPLOAD -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Seller LOGO</span>

		<p>Upload the seller logo [.png] or [.jpg]<br>Because of the current template, it will be scaled at max half of the invoice width or max 100px height.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::upload_image( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'content_seller_logo',
		) );
		?>
	</div>
</div>


<!-- paste your code here -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Seller name</span>

		<p>The seller name represents how the seller wants to be called on the invoice.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'content_seller_name',
		) );
		?>
	</div>
</div>


<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Seller details</span>

		<p>Seller details should contain all seller additional information that should be presented on the invoice.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::textarea( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'content_seller_details',
		) );
		?>
	</div>
</div>


<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Signature name</span>

		<p>It's the representative of the seller, usually a person name, who'll sign the invoice.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'          => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id'   => 'content_signature_name'
		) );
		?>
	</div>
</div>


<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Signature account</span>

		<p>The signature account is the position of the representative who signs the invoice.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'          => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id'   => 'content_signature_account'
		) );
		?>
	</div>
</div>


<!-- SIGNATURE UPLOAD -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Signature</span>

		<p>Upload the signature image [.png] or [.jpg]<br>For nice looking invoices try to upload a signature image
		that perfectly integrated into the current invoice template.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::upload_image( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'content_signature_image',
		) );
		?>
	</div>
</div>

<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Terms</span>

		<p>The terms represents supplementary information laying at the bottom of the invoice.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::textarea( array(
			'ds'        => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id' => 'content_terms',
		) );
		?>
	</div>
</div>



<?php echo td_panel_generator::box_end(); ?>


<!-- Localization -->
<?php echo td_panel_generator::box_start( 'Localization', false ); ?>

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Localization</span>

		<p>Localization section allows you customizing translations for almost every field in the invoice template.
		Adding a custom translations here will ensure you that next generated invoices will use them. These information
		are saved for each invoice, those from the first generation, or the last regeneration, being used for template invoice
		rendering. For reusing these updated translations, an invoice must be regenerated!</p>
	</div>
</div>

<!-- Date: format -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">DATE FORMAT</span>

		<p>Default date format <?php echo get_option( 'date_format' ); ?> of your current Wordpress settings. <a
				href="http://php.net/manual/en/function.date.php">Read more</a> about the date format (it's the same
			with the php date function)</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'          => TD_Woo_Invoice::DATA_SOURCE_NAME,
			'option_id'   => 'custom_date_format',
			'placeholder' => get_option( 'date_format' ),
		) );
		?>
	</div>
</div>

<?php
ob_start();

foreach ( td_woo_invoice_panel_settings::$localization_fields as $field_key => $field_value ) {

	?>

	<div class="td-box-row">
		<div class="td-box-description">
			<span class="td-box-title"><?php echo $field_value ?></span>
		</div>
		<div class="td-box-control-full">
			<?php
			echo td_panel_generator::input( array(
				'ds'          => TD_Woo_Invoice::DATA_SOURCE_NAME,
				'option_id'   => $field_key,
				'placeholder' => $field_value,
			) );
			?>
		</div>
	</div>

<?php
}

ob_flush();

echo td_panel_generator::box_end();

?>
