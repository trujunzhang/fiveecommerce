<?php
/**
 * Label settings.
 *
 * @package td-woo-label\td-panel-label
 */
?>

<?php echo td_panel_generator::box_start( 'Localization', true ); ?>

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Localization</span>

		<p>Localization section allows you customizing translations for almost every field in the 'label template'.</p>
	</div>
</div>

<!-- Printer -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Printing format</span>

		<p>Choose one of the current implemented printing formats.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::dropdown( array(
			'ds'        => TD_Woo_Label::DATA_SOURCE_NAME,
			'option_id' => 'printing_format',
			'values'    => TD_Woo_Label_Panel_Settings::get_printing_formats(),
		) );
		?>
	</div>
</div>

<!-- QRCode URL -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Printed Image</span>

		<p>The image that should be printed next to the shipping information. It can be the Seller Logo or a QR image code (WooCommerce predefined URL-s or the custom url).</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::dropdown( array(
			'ds'        => TD_Woo_Label::DATA_SOURCE_NAME,
			'option_id' => 'shipping_image',
			'values'    => TD_Woo_Label_Panel_Settings::get_shipping_image(),
		) );
		?>
	</div>
</div>

<!-- LOGO UPLOAD -->
<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Seller LOGO</span>

		<p>Upload the shipping label image [.png] or [.jpg]<br>It will be scaled in accordance with the current printing format.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::upload_image( array(
			'ds'        => TD_Woo_Label::DATA_SOURCE_NAME,
			'option_id' => 'seller_logo',
		) );
		?>
	</div>
</div>

<div class="td-box-row">
	<div class="td-box-description">
		<span class="td-box-title">Custom URL</span>
		<p>The custom URL that can be used for QR shipping image.</p>
	</div>
	<div class="td-box-control-full">
		<?php
		echo td_panel_generator::input( array(
			'ds'          => TD_Woo_Label::DATA_SOURCE_NAME,
			'option_id'   => 'custom_url',
			'placeholder' => 'http://www.tagdiv.com',
		) );
		?>
	</div>
</div>

<!-- Localization section -->

<div class="td-box-row">
	<div class="td-box-description td-box-full">
		<span class="td-box-title">Shipping section</span>

		<p>All fields are for shipping information, these being used on the label template in the default browser printing format.</p>
	</div>
</div>

<?php
ob_start();

foreach ( td_woo_label_panel_settings::$localization_fields as $field_key => $field_value ) {

	?>

	<div class="td-box-row">
		<div class="td-box-description">
			<span class="td-box-title"><?php echo $field_value ?></span>
		</div>
		<div class="td-box-control-full">
			<?php
			echo td_panel_generator::input( array(
				'ds'          => TD_Woo_Label::DATA_SOURCE_NAME,
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
