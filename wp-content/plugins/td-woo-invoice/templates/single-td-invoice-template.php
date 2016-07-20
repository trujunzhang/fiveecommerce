<?php
/**
 * Created by PhpStorm.
 * User: tagdiv
 * Date: 21.07.2015
 * Time: 10:53
 *
 * @package td-woo-invoice/single-td-invoice
 */

global $post;
$invoice_settings = td_woo_invoice_settings::get_invoice_data( $post );

if ( empty( $invoice_settings ) ) {
	/*
	 * wp_redirect(home_url());
	 */

	// If no header is sent, redirect to 404.
	if ( ! headers_sent() ) {

		/* @var $wp_query WP_Query */
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 );
		exit();
	}
} else {
	/*

	print_r($invoice_settings);

	if ( !empty($invoice_settings['template_version']) and file_exists( TD_WOO_INVOICE_PLUGIN_PATH . '/templates/template-' . $invoice_settings['template_version'] . '.php') ) {
		// Load the invoice template, used at generation or at the last regeneration invoice.
		include_once( 'template-' . $invoice_settings['template_version'] . '.php' );
	} else {
		// Load the last (the current) template.
		include_once( 'template-' . TD_WOO_INVOICE_VERSION . '.php' );
	}

	 */

	td_woo_invoice_settings::sanitized_invoice_data( $invoice_settings );

	include_once( 'template.php' );
}
