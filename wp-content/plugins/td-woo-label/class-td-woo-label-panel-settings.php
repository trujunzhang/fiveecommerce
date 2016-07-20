<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 24.07.2015
 * Time: 18:27
 *
 * @package td-woo-label\TD_Woo_Label_Panel_Settings
 */

/**
 * Class TD_Woo_Label_Panel_Settings
 *
 * Helper class - used for panel settings
 *
 * - register panel with default settings
 * - get the panel fields
 * - get the panel settings
 *
 * - this should be abstract too ...
 */
final class TD_Woo_Label_Panel_Settings {

	/**
	 * Localization fields (usually strings).
	 *
	 * @var array
	 */
	static $localization_fields = array(
//		'l10n_shipping_details' => 'Shipping details',
//		'l10n_shipping_method'  => 'Shipping method',

		'l10n_shipping_full_name'   => 'Name:',
		'l10n_shipping_first_name'  => 'First name:',
		'l10n_shipping_last_name'   => 'Last name:',
		'l10n_shipping_company'     => 'Company:',
		'l10n_shipping_address_1'   => 'Address:',
		'l10n_shipping_address_2'   => 'Address 2:',
		'l10n_shipping_state'       => 'State/City:',
		'l10n_shipping_city'        => 'City:',
		'l10n_shipping_postcode'    => 'Postcode:',
		'l10n_shipping_country'     => 'Country:',

		'l10_billing_order'         => 'Order:',
		'l10_billing_phone'         => 'Phone:',
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
				'td-panel-woo-label' => array(
					'text'      => 'LABELS',
					'ico_class' => 'td-ico-label',
					'file'      => TD_WOO_LABEL_PLUGIN_PATH . '/panel/td-panel-label.php',
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
	 * - get all panel setting
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
	 * - current printing formats (for now, just browser printing and dymo label).
	 *
	 * @return array
	 */
	static function get_printing_formats() {
		$result = array(
			array(
				'val'  => '',
				'text' => 'Default (browser printing)',
			),
			array(
				'val'  => 'dymo',
				'text' => 'DYMO printing',
			),
		);

		return $result;
	}


	/**
	 * - available qr code urls.
	 *
	 * @return array
	 */
	static function get_shipping_image() {
		$result = array(
			array(
				'val'  => '',
				'text' => 'Default (no image)',
			),
			array(
				'val'  => 'seller_logo',
				'text' => 'Seller Logo',
			),
			array(
				'val'  => 'order_url',
				'text' => 'QR - Order URL',
			),
			array(
				'val'  => 'map_url',
				'text' => 'QR - Map Address URL',
			),
			array(
				'val'  => 'custom_url',
				'text' => 'QR - Custom URL',
			),
		);

		return $result;
	}
}
