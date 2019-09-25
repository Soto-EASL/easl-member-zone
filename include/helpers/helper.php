<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

function easl_mz_get_manager() {
	return EASL_MZ_Manager::get_instance();
}

function easl_mz_get_asset_url( $filename = '' ) {
	return easl_mz_get_manager()->asset_url( $filename );
}

function easl_mz_is_member_logged_in() {
	return EASL_MZ_Manager::get_instance()->getSession()->has_member_active_session();
}

function easl_member_logout_url() {
	return add_query_arg( 'mz_logout', 1, get_site_url() );
}

function easl_mz_get_current_session_data() {
	return EASL_MZ_Manager::get_instance()->getSession()->get_current_session_data();
}

function easl_mz_setcookie( $name, $value, $expire = 0, $secure = false, $httponly = false ) {
	if ( ! headers_sent() ) {
		setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, $httponly );
	} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		headers_sent( $file, $line );
		trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE );
	}
}

function easl_mz_enqueue_select_assets() {
	wp_enqueue_style( 'select2', easl_mz_get_asset_url( 'lib/select2/css/select2.min.css' ), array(), '4.0.10' );
	wp_enqueue_script( 'select2', easl_mz_get_asset_url( 'lib/select2/js/select2.min.js' ), array(), '4.0.10', true );
}

function easl_mz_enqueue_datepicker_assets() {
	wp_enqueue_style( 'jquery-ui', easl_mz_get_asset_url( 'lib/jquery-ui-1.11.4/jquery-ui.min.css' ), array(), '1.11.4' );
	wp_enqueue_script( 'jquery-ui-datepicker' );
}