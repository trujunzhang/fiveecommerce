<?php
/**
 * Plugin Name: tagDiv Woo Invoice
 * Plugin URI: http://forum.tagdiv.com/tagdiv-woo-invoice-plugin/
 * Description: tagDiv WOO Invoices
 * Author: tagDiv
 * Version: 1.0
 * Author URI: http://tagdiv.com
 *
 * @package td-woo-invoice\td-woo-invoice
 */

/*
 * update_option( TD_Woo_Invoice::DATA_SOURCE_NAME, '');
 * die;
 */



// Important! The required files are here but they are necessary only when the plugin
// initialization finishes (the invoice custom type is created).
require_once 'class-td-woo-invoice-settings.php';
require_once 'class-td-woo-invoice-panel-settings.php';


define( 'TD_WOO_INVOICE_VERSION', '1.1' );
define( 'TD_WOO_INVOICE_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'TD_WOO_INVOICE_PLUGIN_PATH', dirname( __FILE__ ) );


add_action( 'td_wp_booster_loaded', 'td_woo_invoice_plugin_init' );
/**
 * It does a series of checks before plugin initialization.
 * - check the woocommerce installation.
 * - check the matching between plugin version and the theme version (they should match to continue).
 * - check the current user role.
 */
function td_woo_invoice_plugin_init() {

	// Woocommerce installation checking!
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) === false ) {

		if ( is_plugin_active( plugin_basename( __FILE__ ) ) ) {

			add_action( 'admin_notices', 'td_woo_invoice_woocommerce_msg' );
			/**
			 * Admin notice function to inform user to active WooCommerce for properly using the TagDiv Label plugin.
			 */
			function td_woo_invoice_woocommerce_msg() {
				?>

				<div class="error">
					<p><?php echo '<strong style="color:red">The TagDiv Invoice plugin needs the WooCommerce plugin to be activated first!</strong>' ?></p>
					<p><?php echo 'File message: ' . __FILE__ ?></p>
				</div>

			<?php
			}
		}

		return;
	}

	// Theme version checking
	// We have the aurora api but version check fail.
	if ( ! defined( 'TD_AURORA_VERSION' ) or TD_AURORA_VERSION !== TD_WOO_INVOICE_VERSION ) {

		add_action( 'admin_notices', 'td_woo_invoice_aurora_msg' );
		/**
		 * Admin notice function to inform user to active WooCommerce for properly using the TagDiv Label plugin.
		 */
		function td_woo_invoice_aurora_msg() {
			?>

			<div class="error">
				<p><?php echo '<strong>Please update the theme or the plugin to the same version of the Aurora plugin API. Now, the Theme Aurora version is: ' . TD_AURORA_VERSION . ' and plugin version: ' . TD_WOO_INVOICE_VERSION . '!</strong>' ?></p>
				<p><?php echo '<strong style="color:red">TagDiv Invoice plugin is not active</strong> to prevent unexpected behaviours.' ?></p>
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

	if ( ! TD_Woo_Invoice_Settings::is_current_user_allowed() ) {
		/*
		 * no info error should be returned here - problems at logging out
		 * tdx_util::error(__FILE__, "The current user restricted!", __METHOD__);
		 */

		return;
	}

	// The plugin is ready to init.
	TD_Woo_Invoice::td_init();
}


/**
 * - registration activation hook
 */
register_activation_hook( __FILE__, 'td_register_activation_hook' );
/**
 * It registers the invoice cpt for adding user capabilities and flashing rewrite rules (for permalinks).
 * The capabilities should be set only at cpt successfully registration.
 * Important! This wp hook is just at plugin activation, and therefore cpt registration is done again on wp 'init' hook.
 */
function td_register_activation_hook() {

	if ( ! TD_Woo_Invoice_Settings::is_current_user_admin() ) {

		// This info error is just for test - it should be removed.
		 tdx_util::error( __FILE__, 'The activation requires the current user having an admin role (administrator or shop_manager)!', __METHOD__ );

		// Used die; to not continue plugin activation and allowing info massage to be shown.
		// Without die; the plugin will activate, which is not good at all, and also the message can't be shown.
		die;
	}

	$registered_obj = TD_Woo_Invoice::td_register_cpt_invoice();

	if ( is_wp_error( $registered_obj ) ) {

		/* @var $registered_obj WP_Error */
		tdx_util::error( __FILE__,
			"The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' can't be registered!",
			array(
				'method'         => __METHOD__,
				'error_codes'    => print_r( $registered_obj->get_error_codes() ),
				'error_messages' => print_r( $registered_obj->get_error_messages() ),
			)
		);

		// Used die; to not continue plugin activation and allowing info massage to be shown.
		// Without die; the plugin will activate, which is not good at all, and also the message can't be shown.
		die;

		// The cpt registration doesn't get error and it's not already registered (the response is not null).
	} else if ( ! is_null( $registered_obj ) ) {

		foreach ( TD_Woo_Invoice_Settings::get_allowed_roles() as $role_id ) {
			$role = get_role( $role_id );

			if ( ! is_null( $role ) ) {
				// The only capability for the 'customer' role.
				if ( 'customer' === $role_id ) {
					$role->add_cap( 'read_private_td_invoices' );
					continue;
				}

				$role->add_cap( 'edit_td_invoice' );
				$role->add_cap( 'read_td_invoice' );
				$role->add_cap( 'delete_td_invoice' );

				$role->add_cap( 'edit_td_invoices' );
				$role->add_cap( 'edit_others_td_invoices' );
				$role->add_cap( 'publish_td_invoices' );
				$role->add_cap( 'read_private_td_invoices' );

				$role->add_cap( 'read' );
				$role->add_cap( 'delete_td_invoices' );
				$role->add_cap( 'delete_private_td_invoices' );
				$role->add_cap( 'delete_published_td_invoices' );
				$role->add_cap( 'delete_others_td_invoices' );
				$role->add_cap( 'edit_private_td_invoices' );
				$role->add_cap( 'edit_published_td_invoices' );

				$role->add_cap( 'create_td_invoices' );
			}
		}

		flush_rewrite_rules();
	}
}


/**
 * - registration deactivation hook
 */
register_deactivation_hook( __FILE__, 'td_register_deactivation_hook' );
/**
 * As the user capabilities are registered on the wp 'register_activation_hook' hook,
 * they are deactivated on the wp 'register_deactivation_hook' hook, flashing rewrite rule obviously.
 */
function td_register_deactivation_hook() {

	foreach ( TD_Woo_Invoice_Settings::get_allowed_roles() as $role_id ) {
		$role = get_role( $role_id );

		if ( ! is_null( $role ) ) {
			if ( 'customer' === $role_id ) {
				$role->remove_cap( 'read_private_td_invoices' );
				continue;
			}

			$role->remove_cap( 'edit_td_invoice' );
			$role->remove_cap( 'read_td_invoice' );
			$role->remove_cap( 'delete_td_invoice' );

			$role->remove_cap( 'edit_td_invoices' );
			$role->remove_cap( 'edit_others_td_invoices' );
			$role->remove_cap( 'publish_td_invoices' );
			$role->remove_cap( 'read_private_td_invoices' );

			$role->remove_cap( 'delete_td_invoices' );
			$role->remove_cap( 'delete_private_td_invoices' );
			$role->remove_cap( 'delete_published_td_invoices' );
			$role->remove_cap( 'delete_others_td_invoices' );
			$role->remove_cap( 'edit_private_td_invoices' );
			$role->remove_cap( 'edit_published_td_invoices' );

			$role->remove_cap( 'create_td_invoices' );
		}
	}

	// The flushing rewrite rules.
	flush_rewrite_rules();
}

/**
 * Class TD_Woo_Invoice
 *
 * - main plugin class
 * - the entry point of using it, should be just 'td_init' function
 */
abstract class TD_Woo_Invoice {

	// Data source name used by plugin classes for saving options.
	const DATA_SOURCE_NAME = __CLASS__;

	// Slug of the invoice custom post type.
	const SLUG_CUSTOM_TYPE = 'td_invoice';


	/**
	 * - the class should not be used instantiated
	 */
	private function __construct() {
	}


	/**
	 * - tries registering cpt for invoice and register the plugin if succeeded
	 */
	static function td_init() {

		// If the invoice cpt is already registered, just continue to register the plugin
		// (it means the cpt was already registered by the activation of the plugin - @see register_activation_hook).
		if ( isset( $GLOBALS['wp_post_types'][ TD_Woo_Invoice::SLUG_CUSTOM_TYPE ] ) ) {
			TD_Woo_Invoice::td_register_plugin();

			return;
		}

		// The cpt registration must be done on the 'init' hook, for having the cpt accessible.
		add_action( 'init', 'td_register_posttype' );
		/**
		 * Register invoice custom post type.
		 */
		function td_register_posttype() {

			$registered_obj = TD_Woo_Invoice::td_register_cpt_invoice();

			if ( is_wp_error( $registered_obj ) ) {

				/* @var $registered_obj WP_Error */
				tdx_util::error( __FILE__,
					"The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' can't be registered!",
					array(
						'method'         => __METHOD__,
						'error_codes'    => print_r( $registered_obj->get_error_codes() ),
						'error_messages' => print_r( $registered_obj->get_error_messages() ),
					)
				);

				// The cpt registration doesn't get error and it's not already registered (response not null).
			} else if ( ! is_null( $registered_obj ) ) {
				TD_Woo_Invoice::td_register_plugin();
			}
		}
	}


	/**
	 * Tries to register the cpt invoice.
	 * @return object|WP_Error|null
	 */
	static function td_register_cpt_invoice() {
		if ( isset( $GLOBALS['wp_post_types'][ TD_Woo_Invoice::SLUG_CUSTOM_TYPE ] ) ) {
			tdx_util::error( __FILE__, "The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' already registered!", __METHOD__ );

			return null;
		}

		return register_post_type( TD_Woo_Invoice::SLUG_CUSTOM_TYPE,
			array(
				'labels'              => array(
					'name'          => __( 'Invoices' ),
					'singular_name' => __( 'Invoice' ),
				),
				'public'              => true,
				'exclude_from_search' => true,

				/*
				'show_in_nav_menu' => false,
				'show_in_menu' => false,
				*/

				'capability_type'     => 'td_invoice',
				'map_meta_cap'        => true,

				// It suppress 'editor' and 'title' capabilities.
				'supports'            => false,
			)
		);
	}


	/**
	 * Register the plugin main functionality (the panel and the hooks).
	 * Suppress functionality if the plugin is not initialized.
	 */
	static function td_register_plugin() {

		// Stop if the invoice cpt is not registered yet.
		if ( ! isset( $GLOBALS['wp_post_types'][ TD_Woo_Invoice::SLUG_CUSTOM_TYPE ] ) ) {
			tdx_util::error( __FILE__, "The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' should be registered first!", __METHOD__ );

			return;
		}

		TD_Woo_Invoice_Panel_Settings::register_panel( TD_Woo_Invoice::DATA_SOURCE_NAME, 'td_panel_woo' );
		TD_Woo_Invoice::td_register_wp_hooks();

		// Generate/regenerate the invoice.
		// It should be permitted just for admin roles.
		if ( isset( $_GET['td_woo_generate_invoice'] ) and TD_Woo_Invoice_Settings::is_current_user_admin() ) {
			$order_number = $_GET['td_woo_generate_invoice'];
			TD_Woo_Invoice::td_save_invoice_data( $order_number );
		}
	}


	/**
	 * - register the hooks
	 * - suppress functionality if the plugin is not initialized
	 */
	static function td_register_wp_hooks() {

		// Stop if the invoice cpt is not registered yet.
		if ( ! isset( $GLOBALS['wp_post_types'][ TD_Woo_Invoice::SLUG_CUSTOM_TYPE ] ) ) {
			tdx_util::error( __FILE__, "The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' should be registered first!", __METHOD__ );

			return;
		}

		// - wp cpt hook - remove publish meta box for the invoice ctp.
		add_action( 'admin_menu', 'td_remove_publish_box' );

		/**
		 * Remove the publish meta box.
		 * The invoices must be created (generated) just using an woo order (edit order panel),
		 * and not using 'Add New' invoice cpt capability.
		 */
		function td_remove_publish_box() {
			remove_meta_box( 'submitdiv', TD_Woo_Invoice::SLUG_CUSTOM_TYPE, 'side' );
		}

		// - wp cpt hook - management columns of the custom post type
		add_filter( 'manage_edit-td_invoice_columns', 'td_manage_edit_td_invoice_columns' );
		/**
		 * Insert 2 columns into the invoice custom post type editor.
		 * @param array $columns - Columns of the invoice cpt editor.
		 * @return array
		 */
		function td_manage_edit_td_invoice_columns( $columns ) {
			$result = array();

			foreach ( $columns as $column_key => $column_value ) {
				if ( 'title' === $column_key ) {
					$result[ $column_key ]             = __( 'Title (Invoice number - Invoice order number - Invoice date)', 'td_invoice' );
					$result['td_column_invoice_order'] = __( 'Invoice Order', 'td_invoice' );
				} else {
					$result[ $column_key ] = $column_value;
				}
			}

			return $result;
		}

		// - wp cpt hook - add links onto the custom columns
		add_action( 'manage_td_invoice_posts_custom_column', 'td_manage_td_invoice_posts_custom_column', 10, 2 );
		/**
		 * Add custom content (a link) onto the invoice order column, in the invoice manage editor.
		 * @param string $column    - Column identifier.
		 * @param int    $post_id   - The post id.
		 */
		function td_manage_td_invoice_posts_custom_column( $column, $post_id ) {

			$td_post = get_post( $post_id );
			if ( ! is_null( $td_post )
			     and $td_post->post_status !== 'trash'
			         and 'td_column_invoice_order' === $column
			) {

				$order_url = admin_url() . '/post.php?action=edit&post=' . $td_post->post_parent;

				echo '<a href="' . esc_url( $order_url ) . '" target="_blank">' . __( 'To Order', 'td_woo_invoice' ) . '</a>';
			}
		}

		// - woo hook - add invoice to 'myaccount' customer
		add_filter( 'woocommerce_my_account_my_orders_actions', 'td_woo_invoice_woocommerce_my_account_my_orders_actions', 10, 2 );
		/**
		 * Add invoice button in my account customer section.
		 * Each customer should be able to see its invoices, after their generation and if their correspondent order
		 * has the necessary status set from invoice panel.
		 *
		 * @param array    $actions - Actions involved for customer into my account section.
		 * @param WC_Order $order   - The current woocommerce order.
		 *
		 * @return array
		 */
		function td_woo_invoice_woocommerce_my_account_my_orders_actions( $actions, $order ) {
			/*
			 * var_dump($order);die;
			 */

			// To be sure that we have an VC_Order.
			if ( ! empty( $order ) and is_object( $order ) and get_class( $order ) == 'WC_Order' ) {
				$order_invoice_id = get_post_meta( $order->id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, true );

				if ( ! empty( $order_invoice_id ) ) {

					$td_invoice_post = get_post( $order_invoice_id );
					if ( ! is_null( $td_invoice_post ) ) {
						/*
						 * echo $td_invoice_post->post_status; die.
						 */

						$meta_values = get_post_meta( $order_invoice_id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE );

						if ( ! empty( $meta_values ) and is_array( $meta_values ) and count( $meta_values ) ) {
							$invoice_settings = $meta_values[0];

							if ( ! empty( $invoice_settings['panel_settings'] ) and ! empty( $invoice_settings['panel_settings']['status_order_when_invoice_in_myaccount'] ) ) {

								$status_order_when_invoice_in_my_account = $invoice_settings['panel_settings']['status_order_when_invoice_in_myaccount'];

								if ( wc_is_order_status( $status_order_when_invoice_in_my_account )
								     and 'wc-' . $order->status === $status_order_when_invoice_in_my_account
								) {

									$actions[] = array(
										'url'  => get_post_permalink( $order_invoice_id ),
										'name' => __( 'TD Invoice', 'td_woo_invoice' ),
									);
								}
							}
						}
					}
				}
			}

			return $actions;
		}

		// - wp hook - redirect single template of the custom post type (the invoice)
		add_filter( 'single_template', 'td_woo_invoice_single_template' );
		/**
		 * Set the single wp template for invoice custom post type.
		 *
		 * @param string $single_template - wp template.
		 *
		 * @return string
		 */
		function td_woo_invoice_single_template( $single_template ) {

			global $post;

			if ( TD_Woo_Invoice::SLUG_CUSTOM_TYPE === $post->post_type ) {
				$single_template = TD_WOO_INVOICE_PLUGIN_PATH . '/templates/single-td-invoice-template.php';
			}

			return $single_template;
		}

		// - woo hook - add woocommerce admin actions
		add_action( 'woocommerce_admin_order_actions', 'td_woo_invoice_woocommerce_admin_order_actions' );
		/**
		 * Add button invoice viewer to woocommerce order admin section.
		 *
		 * @param string $content - The current content to be shown.
		 *
		 * @return mixed
		 */
		function td_woo_invoice_woocommerce_admin_order_actions( $content ) {

			global $post;
			if ( ! $post ) {
				return $content;
			}

			$current_order = wc_get_order( $post->ID );
			if ( ! $current_order ) {
				return $content;
			}

			$order_invoice_id = get_post_meta( $current_order->id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, true );
			if ( ! empty( $order_invoice_id ) ) {

				$td_post = get_post( $order_invoice_id );

				if ( ! is_null( $td_post ) ) {
					$td_button_link = get_post_permalink( $order_invoice_id );
				    $td_admin_invoice_button = '<a id="" class="button tips" href="' . $td_button_link . '" data-tip="' . __( 'View Invoice', 'td_woo_invoice' ) . '" target="_blank" style="line-height:18px; font-size: 10px; height: 26px; padding: 3px 7.5px;">INVOICE</a>';

					echo $td_admin_invoice_button;
				}
			}

			return $content;
		}

		add_action( 'add_meta_boxes', 'td_woo_invoice_add_meta_boxes' );
		/**
		 * Add meta boxes on the woocommerce orders (post type shop_order).
		 */
		function td_woo_invoice_add_meta_boxes() {

			global $post;
			if ( ! $post ) {
				return;
			}

			$current_order = wc_get_order( $post->ID );
			if ( ! $current_order ) {
				return;
			}

			add_meta_box( 'td_woo_invoice_metabox', 'tagDiv Invoices', 'td_woo_invoice_metabox_callback', 'shop_order', 'side' );
			/**
			 * Add meta box on 'shop_order' woocommerce cpt, for viewing and generating/regenerating the invoice.
			 *
			 * @param WP_Post $post - current custom post type.
			 */
			function td_woo_invoice_metabox_callback( $post ) {

				$order_invoice_id = get_post_meta( $post->ID, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, true );
				if ( ! empty( $order_invoice_id ) and ! is_null( get_post( $order_invoice_id ) ) ) {

					$view_invoice_link = get_post_permalink( $order_invoice_id );

					$view_invoice_button = '<a class="button" href="' . $view_invoice_link . '" target="_blank" style="margin-right: 12px; margin-top: 6px;">' . __( 'View Invoice', 'td_woo_invoice' ) . '</a>';

					echo $view_invoice_button;

					$regenerate_invoice_link   = home_url( '/?td_woo_generate_invoice=' . $post->ID );
					$regenerate_invoice_button = '<a class="button" href="' . $regenerate_invoice_link . '" target="_blank" style="margin-top: 6px;">' . __( 'Regenerate Invoice', 'td_woo_invoice' ) . '</a>';

					echo $regenerate_invoice_button;

				} else {

					global $pagenow;

					if ( 'post-new.php' === $pagenow ) {
						echo 'The new order must be saved prior generating its corresponding invoice!';
					} else {
						$generate_invoice_link   = home_url( '/?td_woo_generate_invoice=' . $post->ID );
						$generate_invoice_button = '<a class="button" href="' . $generate_invoice_link . '" style="margin-top: 6px;">' . __( 'Generate Invoice', 'td_woo_invoice' ) . '</a>';

						echo $generate_invoice_button;
					}
				}
			}
		}
	}


	/**
	 * Generate/regenerate invoice
	 *
	 * @param int $order_number - the order number.
	 */
	static function td_save_invoice_data( $order_number ) {

		// Stop if the invoice cpt is not registered yet.
		if ( ! isset( $GLOBALS['wp_post_types'][ TD_Woo_Invoice::SLUG_CUSTOM_TYPE ] ) ) {
			tdx_util::error( __FILE__, "The custom post type '" . TD_Woo_Invoice::SLUG_CUSTOM_TYPE . "' should be registered first!", __METHOD__ );

			return;
		}

		$current_order = wc_get_order( $order_number );
		if ( ! $current_order ) {
			tdx_util::error( __FILE__, "The order number $order_number does not exist!", __METHOD__ );

			return;
		}

		// Meta data that will be used for inserted/updated invoice post type.
		$td_invoice_post = array(
			'post_type'     => TD_Woo_Invoice::SLUG_CUSTOM_TYPE,
			'post_status'   => 'private',
			'post_parent'   => $order_number,
		);

		$td_invoice_next_number = null;
		$td_invoice_date        = null;

		/*
		 * Order post has meta info for its current generated invoice.
		 *      1. Meta info (invoice id) must be checked
		 * to see if it really exists that post. If it exists, the existing invoice post is updated, and the title and the content are preserved, there being
		 * saved the original invoice number - its order number - and its created date.
		 *      2. If meta info isn't present or there's any post with that id, the new invoice post is inserted and those all info are generated (title and content)
        */

		$order_invoice_id = get_post_meta( $current_order->id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, true );
		if ( ! empty( $order_invoice_id ) ) {

			$td_post = get_post( $order_invoice_id );
			if ( is_null( $td_post ) ) {
				$order_invoice_id = null;
			} else {
				$td_invoice_post['ID']           = $order_invoice_id;
				$td_invoice_post['post_title']   = $td_post->post_title;
				$td_invoice_post['post_content'] = $td_post->post_content;
			}
		}

		if ( empty( $order_invoice_id ) ) {
			/*
			 * Post_title and post_content keep the invoice number and the invoice date, helping in invoice management (at search).
			 */

			$td_invoice_next_number = TD_Woo_Invoice_Settings::get_invoice_next_number();

			if ( is_null( $td_invoice_next_number ) ) {
				tdx_util::error( __FILE__, "The invoice next number couldn't be generated!", __METHOD__ );

				return;
			}

			$date_format = tdx_options::get_option( TD_Woo_Invoice::DATA_SOURCE_NAME, 'custom_date_format' );
			if ( empty( $date_format ) ) {
				$date_format = get_option( 'date_format' );
			}

			$td_invoice_date = date( $date_format );

			$td_invoice_info = $td_invoice_next_number . ' - ' . $order_number . ' - ' . $td_invoice_date;

			$td_invoice_post['post_title']   = $td_invoice_info;
			$td_invoice_post['post_content'] = $td_invoice_info;
		}

		// The invoice post is inserted/updated.
		$td_invoice_post_id = wp_insert_post( $td_invoice_post );

		if ( 0 === $td_invoice_post_id ) {

			TD_Woo_Invoice_Settings::revert_invoice_number();

			tdx_util::error( __FILE__, "The invoice for $order_number order number can't be inserted/updated!", __METHOD__ );

			return;

		} else {

			// The post is successfully inserted/update, so it's safe to update its metadata.
			$post_meta = array(

				// 'template_version' it's a mandatory meta field
				'template_version'  => TD_WOO_INVOICE_VERSION,

				'order_data'        => TD_Woo_Invoice_Settings::get_current_order_data( $current_order ),
				'panel_settings'    => TD_Woo_Invoice_Panel_Settings::get_panel_settings(),
			);

			if ( empty( $order_invoice_id ) ) {

				$post_meta['invoice_number'] = $td_invoice_next_number;
				$post_meta['invoice_date']   = $td_invoice_date;

				update_post_meta( $order_number, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, $td_invoice_post_id );

			} else {
				$existing_meta_values = get_post_meta( $td_invoice_post_id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE );
				if ( ! empty( $existing_meta_values ) and is_array( $existing_meta_values ) and ( count( $existing_meta_values ) > 0 ) ) {

					// Keep the existing invoice number and invoice date.
					$post_meta['invoice_number'] = $existing_meta_values[0]['invoice_number'];
					$post_meta['invoice_date']   = $existing_meta_values[0]['invoice_date'];
				}
			}
			update_post_meta( $td_invoice_post_id, TD_Woo_Invoice::SLUG_CUSTOM_TYPE, $post_meta );
		}

		/*
		 * wp_redirect( get_edit_post_link( $order_number, '' ) );
		 */

		wp_redirect( get_post_permalink( $td_invoice_post_id ) );

		exit;
	}
}


