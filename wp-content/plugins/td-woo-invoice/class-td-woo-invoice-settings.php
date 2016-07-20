<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 24.07.2015
 * Time: 18:27
 *
 * @package td-woo-invoice/TDWooInvoiceSettings
 */

/**
 * Class TD_Woo_Invoice_Settings
 *
 * Helper class - used for order invoices
 * - get the order data to be saved into a custom post type (invoice) meta data
 * - get/set the invoice next number
 * - check helper for current user restriction
 */
final class TD_Woo_Invoice_Settings {

	/**
	 * Data source used for saving options.
	 * @var string
	 */
	private static $ds = TD_Woo_Invoice::DATA_SOURCE_NAME;

	/**
	 * Allowed roles for activating and running plugin/
	 * @var array
	 */
	private static $allowed_roles = array( 'administrator', 'shop_manager', 'customer' );

	/**
	 * Admin roles (a group of the allowed roles).
	 * @var array
	 */
	private static $admin_roles = array( 'administrator', 'shop_manager' );

	/**
	 * The role of the current not restricted user (set by the is_current_user_allowed()).
	 * @var null
	 */
	private static $user_role = null;

	// Invoice first default number.
	const START_INVOICE_NUMBER = 1;


	/**
	 * Extract the current order data to be saved to the invoice custom post type.
	 * The order data are saved as they are, unmodified and unsanitized.
	 * The sanitize operation is done on getting data for the invoice template.
	 *
	 * @param WC_Order $current_order - The current order.
	 *
	 * @return array
	 */
	static function get_current_order_data( $current_order ) {

		$result = array(
			'user_id'               => $current_order->get_user_id(),

			// Formatted total order - header invoice.
			'order_formatted_total' => $current_order->get_formatted_order_total(),

			// Items' information.
			'order_items_total'     => $current_order->get_order_item_totals(),

			'order_id'              => $current_order->get_order_number(),
			'order_date'            => $current_order->order_date,
			'customer_id'           => $current_order->customer_user,
			'customer_note'         => $current_order->customer_note,

			// Billing information.
			'billing_first_name'    => $current_order->billing_first_name,
			'billing_last_name'     => $current_order->billing_last_name,
			'billing_company'       => $current_order->billing_company,
			'billing_address_1'     => $current_order->billing_address_1,
			'billing_address_2'     => $current_order->billing_address_2,
			'billing_city'          => $current_order->billing_city,
			'billing_postcode'      => $current_order->billing_postcode,
			'billing_country_code'  => $current_order->billing_country,
			'billing_country'       => $current_order->billing_country ? WC()->countries->countries[ $current_order->billing_country ] : '',
			'billing_state'         => $current_order->billing_state,
			'billing_email'         => $current_order->billing_email,
			'billing_phone'         => $current_order->billing_phone,
			'payment_method'        => $current_order->payment_method_title,

			// Shipping information.
			'shipping_first_name'   => $current_order->shipping_first_name,
			'shipping_last_name'    => $current_order->shipping_last_name,
			'shipping_company'      => $current_order->shipping_company,
			'shipping_address_1'    => $current_order->shipping_address_1,
			'shipping_address_2'    => $current_order->shipping_address_2,
			'shipping_city'         => $current_order->shipping_city,
			'shipping_postcode'     => $current_order->shipping_postcode,
			'shipping_country_code' => $current_order->shipping_country,
			'shipping_country'      => $current_order->shipping_country ? WC()->countries->countries[ $current_order->shipping_country ] : '',
			'shipping_state'        => $current_order->shipping_state,
			'shipping_method'       => $current_order->get_shipping_method(),
			'order_shipping'        => $current_order->order_shipping,
			'order_shipping_tax'    => $current_order->order_shipping_tax,
			'shipping_methods'      => $current_order->get_shipping_methods(),

			// Settings used to get price formatting.
			'order_currency'                => $current_order->get_order_currency(),
			'wc_price_decimal_separator'    => wc_get_price_decimal_separator(),
			'wc_price_thousand_separator'   => wc_get_price_thousand_separator(),
			'wc_price_decimals'             => wc_get_price_decimals(),
			'wc_price_format'               => get_woocommerce_price_format(),

			// Applied fees.
			'fees' => $current_order->get_fees(),

			// Applied taxes (summary).
			'tax_totals' => $current_order->get_tax_totals(),

			// Applied taxes (details).
			'taxes' => $current_order->get_taxes(),

			// Applied discounts.
			'discount_ex_tax'   => $current_order->get_total_discount(),
			'discount'          => $current_order->get_total_discount( false ),

			// Total computed refund.
			'total_refund' => $current_order->get_total_refunded(),
		);

		$order_items = $current_order->get_items();
		if ( count( $order_items ) ) {
			foreach ( $order_items as $order_item ) {
				$result['items'][] = array(
					'item_data'                   => $order_item,
					'item_formated_line_subtotal' => $current_order->get_formatted_line_subtotal( $order_item ),
				);
			}
		} else {
			$result['items'] = array();
		}

		return $result;
	}


	/**
	 * - get the private allowed roles that facilitate access to an invoice
	 *
	 * @return array
	 */
	static function get_allowed_roles() {
		return self::$allowed_roles;
	}


	/**
	 * - helper function used to check if the current user role is restricted (does not exist in the predefined $allowed_roles)
	 *
	 * @return bool
	 */
	static function is_current_user_allowed() {
		// Current user checking.
		global $current_user;

		get_currentuserinfo();
		$user_roles = $current_user->roles;

		self::$user_role = array_shift( $user_roles );

		if ( in_array( self::$user_role, self::$allowed_roles ) ) {
			/*
			 * tdx_util::error(__FILE__, "The allowed roles are just administrator, shop_manager and customer", __METHOD__);
			 */
			return true;
		}

		return false;
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


	/**
	 * Get the invoice data to be rendered into the template
	 *
	 * @param WP_Post $post  - the post whose meta will be returnet.
	 *
	 * @return array - the meta post
	 */
	static function get_invoice_data( $post ) {

		// $post checking
		if ( ! $post ) {
			tdx_util::error( __FILE__, "Ups! The post isn't set", __METHOD__ );

			return array();
		}

		// Check user restriction.
		if ( ! TD_Woo_Invoice_Settings::is_current_user_allowed() ) {
			tdx_util::error( __FILE__, 'Ups! The current user is restricted', __METHOD__ );

			return array();
		}

		// Meta values checking.
		$meta_values = get_post_meta( $post->ID, TD_Woo_Invoice::SLUG_CUSTOM_TYPE );
		if ( empty( $meta_values ) or ! is_array( $meta_values ) or ! count( $meta_values ) ) {
			tdx_util::error( __FILE__, "Ups! The post meta isn't set", __METHOD__ );

			return array();
		}

		/*
		 * print_r($meta_values);
		 */

		// Allows only the order user, the administrator and the shop manager.
		global $current_user;
		if ( ! isset( $meta_values[0]['order_data']['user_id'] )
		     or ( ( $current_user->ID !== $meta_values[0]['order_data']['user_id'] )
		          and ! is_null( self::$user_role )
		              and ( 'customer' === self::$user_role ) )
		) {

			tdx_util::error( __FILE__, 'Ups! The current user tries getting foreign info', __METHOD__ );

			return array();
		}

		return $meta_values[0];
	}


	/**
	 * @param $data
	 */
	static function sanitized_invoice_data( &$data ) {
		/*
		 * Panel settings.
		 */
		if ( ! isset( $invoice_settings['panel_settings']['content_seller_name'] ) ) {
			$invoice_settings['panel_settings']['content_seller_name'] = '';
		}

		if ( ! isset( $invoice_settings['panel_settings']['content_seller_details'] ) ) {
			$invoice_settings['panel_settings']['content_seller_details'] = '';
		}



		/*
		 * Order data - fee section.
		 */

		// Empty array if it isn't set or is set and not array.
		if ( ! isset( $data['order_data']['fees'] ) or ! is_array( $data['order_data']['fees'] ) ) {

			$data['order_data']['fees'] = array();

		} else {

			// The fee elements are unset if they haven't the next mandatory keys.
			$mandatory_keys = array( 'name', 'line_subtotal', 'line_subtotal_tax' );

			foreach ( $data['order_data']['fees'] as $fee_key => &$fee_value ) {
				if ( count ( $mandatory_keys ) !== count( array_intersect( $mandatory_keys, array_keys( $fee_value ) ) ) ) {
					unset( $data['order_data']['fees'][ $fee_key ] );
				}
			}
		}
	}


	/**
	 * Used to keep the invoice next number at an accurate value
	 * (when an invoice custom post type couldn't be save, next number is't valid and must be decremented)
	 */
	static function revert_invoice_number() {
		$invoice_numbering_next_number = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number' );
		if ( ! empty( $invoice_numbering_next_number ) ) {

			if ( $invoice_numbering_next_number > self::START_INVOICE_NUMBER ) {
				$previous_number = $invoice_numbering_next_number - 1;
			} else {
				$previous_number = self::START_INVOICE_NUMBER;
			}

			tdx_options::set_data_to_datasource( self::$ds, array(
				'invoice_numbering_next_number'      => $previous_number,
				'invoice_numbering_next_number_save' => $previous_number,
			) );
		}
	}


	/**
	 * Get the formatted next number of the invoice
	 *
	 * @param string $join_label - [optional] the glue of the formatted result.
	 *
	 * @return string - the formatted number
	 */
	static function get_invoice_next_number( $join_label = '' ) {
		$invoice_numbering_next_number = self::set_next_number();
		$invoice_numbering_prefix      = tdx_options::get_option( self::$ds, 'invoice_numbering_prefix' );
		$invoice_numbering_suffix      = tdx_options::get_option( self::$ds, 'invoice_numbering_suffix' );

		return $invoice_numbering_prefix . $join_label . $invoice_numbering_next_number . $join_label . $invoice_numbering_suffix;
	}


	/**
	 * Get the number ready for the next invoice
	 *
	 * @return string - the invoice number
	 */
	static function get_next_number() {
		$invoice_numbering_next_number = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number' );

		return $invoice_numbering_next_number;
	}


	/**
	 * Set the next number of the invoice
	 * If the 'invoice_numbering_next_number' is set, the next number is its incremented value
	 *      but if it isn't set but the 'invoice_numbering_next_number_save' is set, the next number is its incremented value
	 *          but if it isn't set also, the next number is START_INVOICE_NUMBER incremented
	 * Returns the number that was set
	 *
	 * @return int|string - the next number that was set
	 */
	private static function set_next_number() {
		$next_number = self::START_INVOICE_NUMBER;

		$invoice_numbering_next_number = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number' );
		if ( empty( $invoice_numbering_next_number ) ) {

			$invoice_numbering_next_number_save = tdx_options::get_option( self::$ds, 'invoice_numbering_next_number_save' );
			if ( ! empty( $invoice_numbering_next_number_save ) ) {
				$next_number = $invoice_numbering_next_number_save;
			}
		} else {
			$next_number = $invoice_numbering_next_number;
		}

		tdx_options::set_data_to_datasource( self::$ds, array(
			'invoice_numbering_next_number'      => $next_number + 1,
			'invoice_numbering_next_number_save' => $next_number + 1,
		) );

		return $next_number;
	}
}

