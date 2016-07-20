<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 24.07.2015
 * Time: 18:27
 *
 * @package td-woo-invoice\TD_Woo_Invoice_Panel_Settings
 */

/**
 * Class TD_Woo_Invoice_Panel_Settings
 *
 * Helper class - used for panel settings
 *
 * - register panel with default settings
 * - get the panel fields
 * - get the panel settings
 *
 * - this should be abstract too ...
 */
final class TD_Woo_Invoice_Panel_Settings {

	/**
	 * Settings fields.
	 * @var array
	 */
	static $setting_fields = array(
		'invoice_numbering_next_number',
		'invoice_numbering_prefix',
		'invoice_numbering_suffix',

		'status_order_when_invoice_in_myaccount',

		// The date_format is here because the localization fields are usually strings.
		'custom_date_format',
	);

	/**
	 * Content fields.
	 * @var array
	 */
	static $content_fields = array(
		'content_seller_logo',
		'content_seller_name',
		'content_seller_details',
		'content_terms',
		'content_signature_image',
		'content_signature_name',
		'content_signature_account',
	);

	/**
	 * Localization fields (usually strings).
	 * @var array
	 */
	static $localization_fields = array(
		'l10n_invoice'          => 'Invoice',
		'l10n_invoice_date'     => 'Invoice Date',
		'l10n_invoice_no'       => 'Invoice #',

		'l10n_invoice_to'       => 'Invoice To',
		'l10n_payment_method'   => 'Payment Method',

		'l10n_shipping_details' => 'Shipping Details',
		'l10n_shipping_method'  => 'Shipping Method',

		'l10n_product'          => 'Product',
		'l10n_unit_price'       => 'Unit Price',
		'l10n_net_price'        => 'Total Net Price',
		'l10n_quantity'         => 'Quantity',

		'l10n_discount'         => 'Discount',
		'l10n_tax_total'        => 'Total Tax',
		'l10n_tax_percent'      => 'Percent Tax',

		'l10n_total'            => 'Total',

		'l10n_shipping'         => 'Shipping',
		'l10n_cart_subtotal'    => 'Subtotal',

		'l10n_total_refund'     => 'Total Refund',

		'l10n_terms'            => 'Terms',
	);

	/**
	 * The data source to save/get panel settings.
	 * It's initialized at registration panel.
	 * @var null
	 */
	private static $ds = null;


	/**
	 * - this is just to attention you the class shouldn't be used instantiated.
	 */
	private function __construct() {
	}


	/**
	 * - register a panel
	 * - a panel must be registered before its usage
	 *
	 * @param {string} $ds The data source where the panel saves.
	 * @param {string} $panel_id The panel id unique id.
	 */
	static function register_panel( $ds, $panel_id ) {

		self::$ds = $ds;

		tdx_options::register_data_source( self::$ds );

		tdx_api_panel::add( $panel_id, array(
				'td-panel-woo-invoice' => array(
					'text'      => 'INVOICES',
					'ico_class' => 'td-ico-invoice',
					'file'      => TD_WOO_INVOICE_PLUGIN_PATH . '/panel/td-panel-invoice.php',
					'type'      => 'in_plugin',
				),
			)
		);
		tdx_api_panel::update_panel_spot( $panel_id, array(
			'title'    => 'TagDIV WooCommerce Plugins',
			'subtitle' => '~ our woo plugins is all you need for your eCommerce start ~',
		) );

		// Set default values for panel.
		self::set_panel_default_settings();
	}

	/**
	 * - predefine default values for the panel settings
	 */
	private static function set_panel_default_settings() {

		// Predefined status of the order when the invoice is available to the buyer.
		$status_order = tdx_options::get_option( self::$ds, 'status_order_when_invoice_in_myaccount' );
		if ( empty( $status_order ) ) {
			tdx_options::update_option_in_cache( self::$ds, 'status_order_when_invoice_in_myaccount', 'wc-completed' );
		}

		// Predefined value for the next invoice number.
		$invoice_numbering_next_number = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number' );
		if ( empty( $invoice_numbering_next_number ) ) {

			$next_number = TD_Woo_Invoice_Settings::START_INVOICE_NUMBER;

			$invoice_numbering_next_number_save = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number_save' );
			if ( ! empty( $invoice_numbering_next_number_save ) ) {
				$next_number = $invoice_numbering_next_number_save;
			}

			tdx_options::update_options_in_cache( self::$ds, array(
				'invoice_numbering_next_number'      => $next_number,
				'invoice_numbering_next_number_save' => $next_number,
			) );
		} else {
			tdx_options::update_option_in_cache( self::$ds, 'invoice_numbering_next_number_save', $invoice_numbering_next_number );
		}

		// Predefined values for the localization fields.
		foreach ( self::$localization_fields as $field_key => $field_value ) {
			$localization_field = tdx_options::get_option( self::$ds, $field_key );
			if ( empty( $localization_field ) ) {
				tdx_options::update_option_in_cache( self::$ds, $field_key, $field_value );
			}
		}

		tdx_options::flush_options();
	}

	/**
	 * - get all panel setting (usually used to keep settings in cpt invoice metadata)
	 *
	 * @return array
	 */
	static function get_panel_settings() {
		$result = array();

		if ( is_null( self::$ds ) ) {
			tdx_util::error( __FILE__, 'The panel must be registered before use!', __METHOD__ );

			return $result;
		}

		foreach ( self::$setting_fields as $field ) {
			$result[ $field ] = tdx_options::get_option( self::$ds, $field );
		}

		foreach ( self::$content_fields as $field ) {
			$result[ $field ] = tdx_options::get_option( self::$ds, $field );
		}

		foreach ( self::$localization_fields as $field_key => $field_value ) {
			$result[ $field_key ] = tdx_options::get_option( self::$ds, $field_key );
		}

		return $result;
	}

	/**
	 * - get statuses of an woo standard order (for now, just used in the panel template - td_panel_invoice.php)
	 *
	 * @return array
	 */
	static function get_order_statuses() {
		$result = array();

		$order_statuses = wc_get_order_statuses();
		foreach ( $order_statuses as $key => $val ) {
			$result[] = array(
				'val'  => $key,
				'text' => $val,
			);
		}

		return $result;
	}
}
