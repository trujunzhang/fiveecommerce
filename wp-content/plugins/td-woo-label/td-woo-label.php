<?php
/**
 * Plugin Name: tagDiv Woo Label
 * Plugin URI: http://forum.tagdiv.com/tagdiv-woo-label-plugin/
 * Description: tagDiv WOO Labels
 * Author: tagDiv
 * Version: 1.0
 * Author URI: http://tagdiv.com
 *
 * @package td-woo-label\td-woo-label
 */

/*
 * update_option( td_woo_label::DATA_SOURCE_NAME, '');
 * die;
 */



require_once 'class-td-woo-label-panel-settings.php';


define( 'TD_WOO_LABEL_VERSION', '1.1' );
define( 'TD_WOO_LABEL_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TD_WOO_LABEL_PLUGIN_PATH', dirname( __FILE__ ) );


add_action( 'td_wp_booster_loaded', 'td_woo_label_plugin_init' );
/**
 * It does a series of checks before plugin initialization.
 * - check the woocommerce installation.
 * - check the matching between plugin version and the Aurora API version (they should match to continue).
 * - check the current user role.
 */
function td_woo_label_plugin_init() {

	// Woocommerce installation checking!
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) === false ) {

		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			add_action( 'admin_notices', 'td_woo_label_woocommerce_msg' );
			/**
			 * Admin notice function to inform user to active WooCommerce for properly using the TagDiv Label plugin.
			 */
			function td_woo_label_woocommerce_msg() {
				?>

				<div class="error">
					<p><?php echo '<strong style="color:red">The TagDiv Label plugin needs the WooCommerce plugin to be activated first!</strong>' ?></p>
					<p><?php echo 'File message: ' . __FILE__ ?></p>
				</div>

			<?php
			}
		}

		return;
	}

	// Theme version checking
	// We have the Aurora API but version check fail.
	if ( ! defined( 'TD_AURORA_VERSION' ) or TD_AURORA_VERSION !== TD_WOO_LABEL_VERSION ) {

		add_action( 'admin_notices', 'td_woo_label_aurora_msg' );
		/**
		 * Admin notice function to inform user to active WooCommerce for properly using the TagDiv Label plugin.
		 */
		function td_woo_label_aurora_msg() {
			?>

			<div class="error">
				<p><?php echo '<strong>Please update the theme or the plugin to the same version of the Aurora plugin API. Now, the Theme Aurora version is: ' . TD_AURORA_VERSION . ' and plugin version: ' . TD_WOO_LABEL_VERSION . '!</strong>' ?></p>
				<p><?php echo '<strong style="color:red">TagDiv Label plugin is not active</strong> to prevent unexpected behaviours.' ?></p>
				<p><?php echo 'File message: ' . __FILE__ ?></p>
			</div>

		<?php
		}

		// The plugin is deactivated if it's already active.
		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		}

		return;
	}

	if ( ! TD_Woo_Label::is_current_user_admin() ) {
		/*
		 * no info error should be returned here - problems at logging out
		 * tdx_util::error(__FILE__, "The current user restricted!", __METHOD__);
		 */

		return;
	}

	add_action( 'admin_enqueue_scripts', 'td_woo_label_admin_enqueue_scripts' );
	/**
	 * Registers the js script: DYMO, QRCode library and the plugin js scripts.
	 */
	function td_woo_label_admin_enqueue_scripts() {

		wp_enqueue_script( 'td_woo_label_dymo', TD_WOO_LABEL_PLUGIN_URL . '/js/DYMO.Label.Framework.latest.js' , array(), false, false );
		wp_enqueue_script( 'td_woo_label_qrcode', TD_WOO_LABEL_PLUGIN_URL . '/js/jquery.qrcode.js' , array( 'jquery' ) , false, true );
		wp_enqueue_script( 'td_woo_label_plugin', TD_WOO_LABEL_PLUGIN_URL . '/js/tdWooLabelPlugin.js' , array( 'jquery', 'td_woo_label_dymo', 'td_woo_label_qrcode' ) , false, true );
	}

	// The plugin is ready to init.
	TD_Woo_Label::td_init();
}

/**
 * Class TD_Woo_Label
 *
 * - main plugin class
 * - the entry point of using it, should be just 'td_init' function
 */
abstract class TD_Woo_Label {

	// Data source name used by plugin classes for saving options.
	const DATA_SOURCE_NAME = __CLASS__;

	/**
	 * Admin roles used allowed to print labels.
	 * @var array
	 */
	private static $admin_roles = array( 'administrator', 'shop_manager' );


	/**
	 * - the class should not be used instantiated
	 */
	private function __construct() {
	}


	/**
	 * Registers tagDiv panel and wp hooks.
	 */
	static function td_init() {

		TD_Woo_Label_Panel_Settings::register_panel( TD_Woo_Label::DATA_SOURCE_NAME, 'td_panel_woo' );
		TD_Woo_Label::td_register_wp_hooks();

		if ( isset( $_GET['td_woo_label'] ) ) {
			$order_number = $_GET['td_woo_label'];
			TD_Woo_Label::td_get_template_label( $order_number );
		}
	}


	/**
	 * - register the hooks
	 */
	static function td_register_wp_hooks() {
		add_action( 'add_meta_boxes', 'td_woo_label_add_meta_boxes' );
		/**
		 * Add meta boxes on the woocommerce orders (post type shop_order).
		 */
		function td_woo_label_add_meta_boxes() {

			global $post;
			if ( ! $post ) {
				return;
			}

			$current_order = wc_get_order( $post->ID );
			if ( ! $current_order ) {
				return;
			}

			add_meta_box( 'td_woo_label_metabox', 'tagDiv Labels', 'td_woo_label_metabox_callback', 'shop_order', 'side' );
			/**
			 * Add meta box on 'shop_order' woocommerce cpt, for viewing shipping labels.
			 *
			 * @param WP_Post $post - current custom post type.
			 */
			function td_woo_label_metabox_callback( $post ) {

				$current_order = wc_get_order( $post->ID );
				if ( ! $current_order ) {
					return;
				}

				// Info for default browser printing.
				$default_formatted_label_info = '';

				// Info for DYMO priting.
				$dymo_formatted_label_info = '';

				$shipping_company = $current_order->shipping_company;
				$shipping_first_name = $current_order->shipping_first_name;
				$shipping_last_name = $current_order->shipping_last_name;

				$shipping_address_1 = $current_order->shipping_address_1;
				$shipping_address_2 = $current_order->shipping_address_2;

				$shipping_state = $current_order->shipping_state;
				$shipping_city = $current_order->shipping_city;
				$shipping_country = $current_order->shipping_country;
				$shipping_postcode = $current_order->shipping_postcode;

				$billing_phone = $current_order->billing_phone;

				$panel_shipping_full_name = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_full_name' );
				$default_formatted_label_info .= '<div class="shipping_full_name"><span>' . $panel_shipping_full_name . '</span></div>';

				if ( ! empty( $shipping_first_name ) ) {
					$panel_shipping_first_name = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_first_name' );
					$default_formatted_label_info .= '<div class="shipping_first_name"><span>' . $panel_shipping_first_name . '</span>' . $shipping_first_name . '</div>';
					$dymo_formatted_label_info .= $shipping_first_name;
				}

				if ( ! empty( $shipping_last_name ) ) {
					$panel_shipping_last_name = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_last_name' );
					$default_formatted_label_info .= '<div class="shipping_last_name"><span>' . $panel_shipping_last_name . '</span>' . $shipping_last_name . '</div>';
					$dymo_formatted_label_info .= ' ' . $shipping_last_name;
				}

				if ( ! empty( $shipping_company ) ) {
					$panel_shipping_company = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_company' );
					$default_formatted_label_info .= '<div class="shipping_company"><span>' . $panel_shipping_company . '</span>' . $shipping_company . '</div>';
					$dymo_formatted_label_info .= '\n' . $shipping_company;
				}

				if ( ! empty( $shipping_address_1 ) ) {
					$panel_shipping_address_1 = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_address_1' );
					$default_formatted_label_info .= '<div class="shipping_address_1"><span>' . $panel_shipping_address_1 . '</span>' . $shipping_address_1 . '</div>';
					$dymo_formatted_label_info .= '\n' . $shipping_address_1;
				}

				if ( ! empty( $shipping_address_2 ) ) {
					$panel_shipping_address_2 = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_address_2' );
					$default_formatted_label_info .= '<div class="shipping_address_2"><span>' . $panel_shipping_address_2 . '</span>' . $shipping_address_2 . '</div>';
					$dymo_formatted_label_info .= '\n' . $shipping_address_2;
				}

				if ( ! empty( $shipping_state ) ) {
					$panel_shipping_state = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_state' );
					$default_formatted_label_info .= '<div class="shipping_state"><span>' . $panel_shipping_state . '</span>' . $shipping_state . '</div>';
					$dymo_formatted_label_info .= '\n' . $shipping_state;
				}

				if ( ! empty( $shipping_city ) ) {
					$panel_shipping_city = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_city' );
					$default_formatted_label_info .= '<div class="shipping_city"><span>' . $panel_shipping_city . '</span>' . $shipping_city . '</div>';
					$dymo_formatted_label_info .= ' ' . $shipping_city;
				}

				if ( ! empty( $shipping_postcode ) ) {
					$panel_shipping_postcode = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_postcode' );
					$default_formatted_label_info .= '<div class="shipping_postcode"><span>' . $panel_shipping_postcode . '</span>' . $shipping_postcode . '</div>';
					$dymo_formatted_label_info .= ' ' . $shipping_postcode;
				}

				if ( ! empty( $shipping_country ) ) {
					$panel_shipping_country = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10n_shipping_country' );
					$shipping_country_value = WC()->countries->countries[ $shipping_country ] . '(' . $shipping_country . ')';

					$default_formatted_label_info .= '<div class="shipping_country"><span>' . $panel_shipping_country . '</span>' . $shipping_country_value . '</div>';
					$dymo_formatted_label_info .= '\n' . $shipping_country_value;
				}

				if ( ! empty( $current_order->id ) ) {

					$panel_billing_order = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10_billing_order' );

					$default_formatted_label_info .= '<div class="shipping_order"><span>' . $panel_billing_order . '</span>' .  $current_order->id . '</div>';
					$dymo_formatted_label_info .= '\n\n' . $panel_billing_order . ' ' . $current_order->id;
				}

				if ( ! empty( $billing_phone ) ) {

					$panel_billing_phone = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'l10_billing_phone' );

					$default_formatted_label_info .= '<div class="billing_phone"><span>' . $panel_billing_phone . '</span>' . $billing_phone . '</div>';
					$dymo_formatted_label_info .= '\n\n' . $panel_billing_phone . ' ' . $billing_phone;
				}

				$printing_format = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'printing_format' );
				$custom_url = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'custom_url' );
				$shipping_image = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'shipping_image' );

				$shipping_image_url = '';
				switch ( $shipping_image ) {
					case 'order_url' :
						$shipping_image_url = $current_order->get_view_order_url();
						break;
					case 'map_url' :
						$shipping_image_url = $current_order->get_shipping_address_map_url();
						break;
				}

				$seller_logo = '';
				$seller_logo_url = tdx_options::get_option( TD_Woo_Label::DATA_SOURCE_NAME, 'seller_logo' );

				if ( ! empty( $seller_logo_url ) ) {
					$seller_logo = base64_encode( file_get_contents( $seller_logo_url ) );
				}

				/*
				 * A message info is shown when the label info isn't set.
				 */
				if ( ( '' === $default_formatted_label_info ) or ( '' === $dymo_formatted_label_info ) ) {

					echo 'Order shipping details information must be set before printing labels.';

				} else {
					ob_start();

					// The js variables are added to the global 'tdWooLabel' js variable which contains all information needed for shipping.
					?>

					<script>

						window.tdWooLabel = {
							tdWooLabelDefaultInfo       : '<?php echo $default_formatted_label_info ?>',
							tdWooLabelDYMOInfo          : '<?php echo $dymo_formatted_label_info ?>',

							tdWooLabelPrintingFormat    : '<?php echo $printing_format ?>',
							tdWooLabelSellerLogo        : '<?php echo $seller_logo ?>',
							tdWooLabelCustomURL         : '<?php echo $custom_url ?>',
							tdWooLabelShippingImage     : '<?php echo $shipping_image ?>',
							tdWooLabelShippingImageURL  : '<?php echo $shipping_image_url ?>',

							tdWooLabelTemplateURL       : '<?php echo TD_WOO_LABEL_PLUGIN_URL ?>'
						}

					</script>

					<?php
					$buffer = ob_get_clean();
					echo $buffer;

					$generate_label_button = '<a class="button td-shipping-label" href="#" style="margin-top: 6px;">' . __( 'Shipping Labels', 'td_woo_label' ) . '</a>';

					echo  $generate_label_button;
				}
			}
		}
	}


	/**
	 * - helper function used to check if the current user role is in
	 *
	 * @return bool
	 */
	static function is_current_user_admin() {
		// Current user checking.
		global $current_user;

		get_currentuserinfo();
		$user_roles = $current_user->roles;

		$user_role = array_shift( $user_roles );

		if ( in_array( $user_role, self::$admin_roles ) ) {

			return true;
		}

		return false;
	}
}
