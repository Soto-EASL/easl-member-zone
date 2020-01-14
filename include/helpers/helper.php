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

function easl_member_new_membership_form_url( $renew = false ) {
	$url = get_field( 'membership_plan_url', 'option' );
	if ( $renew ) {
		$url = add_query_arg( 'mz_renew', 1, $url );
	} else {
		$url = add_query_arg( 'mz_add', 1, $url );
	}


	return $url;
}

function easl_membership_page_url() {
	return get_field( 'member_profile_url', 'option' );
}

function easl_membership_thanks_page_url() {
	return get_field( 'membership_confirm_page_url', 'option' );
}

function easl_membership_checkout_url() {
	$checkout_page = get_field( 'membership_checkout_url', 'option' );

	return $checkout_page;
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

function easl_mz_mail_form_name( $from_name ) {
	$from_name = 'EASL Memberzone';

	return $from_name;
}

function easl_mz_get_member_image_src( $member_id, $member_picture ) {
	$member_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );;
	if ( $member_picture ) {
		$member_image = add_query_arg( array(
			'mz_action' => 'member_image',
			'member_id' => $member_id
		), get_site_url() );
	}

	return $member_image;
}

function easl_mz_get_note_download_url( $note ) {
	$ssl_scheme = is_ssl() ? 'https' : 'http';
	$url        = add_query_arg( array(
		'mz_action' => 'membership_note',
		'note_id'   => $note['id'],
	), get_site_url() );

	return $url;
}

function easl_mz_jhep_menu_link( $atts ) {
	if ( empty( $atts['href'] ) ) {
		return $atts;
	}
	if ( $atts['href'] == '#jhep_link' ) {
		$atts['href'] = easl_mz_get_jhep_link();
	}
	if ( $atts['href'] == '#jhep_test_link' ) {
		$atts['href'] = easl_mz_get_jhep_link( true );
	}

	return $atts;
}

function easl_mz_country_short( $c1, $c2 ) {
	if ( $c1['member_count'] == $c2['member_count'] ) {
		return 0;
	}

	return ( $c1['member_count'] > $c2['member_count'] ) ? - 1 : 1;
}