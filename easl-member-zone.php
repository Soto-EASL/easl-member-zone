<?php
/*
Plugin Name: EASL Member Zone
Description: The plugin contains the functionality for EASL Member zone
Version: 1.0.2
Author: Soto
Author URI: http://www.gosoto.co/
Text Domain: easlmz
License: GPLv2 or later
*/
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'EASL_MZ_VERSION', '1.0.5' );
//define( 'EASL_MZ_VERSION', time() );

class EASL_MZ_Manager {
	/**
	 * Core singleton class
	 * @var self - pattern realization
	 */
	private static $_instance;

	/**
	 * @var EASL_MZ_Session_Handler
	 */
	protected $session;
	/**
	 * @var EASL_MZ_API
	 */
	protected $api;
	/**
	 * @var EASL_MZ_Ajax_Handler
	 */
	protected $ajax;

	protected $messages = array();

	/**
	 * List of paths.
	 *
	 * @since 1.0
	 * @var array
	 */
	private $paths = array();

	/**
	 * Constructor loads API functions, defines paths and adds required wp actions
	 *
	 * @since  1.0
	 */
	private function __construct() {
		$this->set_paths();
		$this->autoload();
		$this->load_vars();
		// Add hooks
		add_action( 'plugins_loaded', array( $this, 'plugins_loaded', ), 9 );
		add_action( 'vc_after_mapping', array( $this, 'vc_shortcodes', ), 10 );
		add_action( 'init', array( $this->session, 'init', ), 0 );
		add_action( 'init', array( $this, 'init', ), 8 );
		add_action( 'wp_enqueue_scripts', array( $this, 'assets', ), 11 );

		add_action( 'easl_mz_memberzone_page_content', array( $this, 'memberzone_page_content' ) );

		add_action( 'template_redirect', array( $this, 'logged_member_actions' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	/**
	 * Setter for paths
	 *
	 * @param $paths
	 *
	 * @since  1.0
	 * @access protected
	 *
	 */
	protected function set_paths() {
		$dir         = dirname( __FILE__ );
		$paths       = array(
			'APP_ROOT'        => $dir,
			'CORE_DIR'        => $dir . '/include/core',
			'SHORTCODES_DIR'  => $dir . '/include/shortcodes',
			'HELPERS_DIR'     => $dir . '/include/helpers',
			'TEMPLATES_DIR'   => $dir . '/include/templates',
			'CRM_VIEWS'       => $dir . '/include/crm-views',
			'THIRD_PARTY'     => $dir . '/include/third-party',
			'ASSETS_DIR'      => $dir . '/assets',
			'ASSETS_DIR_NAME' => 'assets',
		);
		$this->paths = $paths;
	}


	/**
	 * Load required classes and helpers
	 *
	 * @param $paths
	 *
	 * @since  1.0
	 * @access protected
	 *
	 */
	protected function autoload() {
		require $this->path( 'HELPERS_DIR', 'helper.php' );
		require $this->path( 'HELPERS_DIR', 'crm-dropdown-lists.php' );
		require $this->path( 'HELPERS_DIR', 'crm-helpers.php' );
		require $this->path( 'CORE_DIR', 'session-handler.php' );
		require $this->path( 'CORE_DIR', 'mz-request.php' );
		require $this->path( 'CORE_DIR', 'crm-api.php' );
		require $this->path( 'CORE_DIR', 'ajax.php' );
		require $this->path( 'APP_ROOT', 'include/customizer/customizer.php' );
	}

	protected function load_vars() {
		$this->session = EASL_MZ_Session_Handler::get_instance();
		$this->api     = EASL_MZ_API::get_instance();
		$this->ajax    = EASL_MZ_Ajax_Handler::get_instance();
	}

	/**
	 * Callback function WP plugin_loaded action hook. Loads locale
	 *
	 * @since  1.0
	 * @access public
	 */
	public function plugins_loaded() {
		// Setup locale
		load_plugin_textdomain( 'easlmz', false, $this->path( 'APP_ROOT', 'locale' ) );
	}

	/**
	 * Callback function for WP init action hook.
	 *
	 * @return void
	 * @since  1.0
	 * @access public
	 *
	 */
	public function init() {
		$this->add_options_page();
		$this->handle_member_login();
		$this->handle_member_logout();
		$this->handle_mz_actions();

		if ( easl_mz_is_member_logged_in() ) {
			add_action( 'template_redirect', array( $this, 'maybe_disable_wp_rocket_cache' ) );
		}
	}

	public function memberzone_page_content() {
		include $this->path( 'TEMPLATES_DIR', 'main.php' );
	}

	public function body_class( $classes = array() ) {
		if ( easl_mz_is_member_logged_in() ) {
			$classes[] = 'easl-mz-member-logged-in';
		}

		return $classes;
	}

	public function logged_member_actions() {
		if ( easl_mz_is_member_logged_in() ) {
			add_action( 'wpex_hook_main_before', array( $this, 'expiring_message' ) );
		}
	}

	public function expiring_message() {
		include $this->path( 'TEMPLATES_DIR', 'expiring-message.php' );
	}

	public function maybe_disable_wp_rocket_cache() {
		if ( ! defined( 'DONOTCACHEPAGE' ) ) {
			define( 'DONOTCACHEPAGE', true );
		}

		if ( ! defined( 'DONOTROCKETOPTIMIZE' ) ) {
			define( 'DONOTROCKETOPTIMIZE', true );
		}

		return true;
	}

	public function handle_mz_actions() {
		if ( empty( $_REQUEST['mz_action'] ) ) {
			return false;
		}
		$action = trim( $_REQUEST['mz_action'] );
		switch ( $action ) {
			case 'change_member_picture':
				$this->change_member_picture();
				break;
			case 'create_membership':
				$this->create_membership();
				break;
			case 'payment_feedback':
				$this->handle_payment_feedback();
				break;
			case 'member_image':
				$this->get_member_image();
				break;
			case 'membership_note':
				$this->get_membership_note();
				break;
		}
	}

	public function handle_member_login() {
		if ( empty( $_POST['mz_member_login'] ) || empty( $_POST['mz_member_password'] ) ) {
			return false;
		}
		$member_login    = $_POST['mz_member_login'];
		$member_password = $_POST['mz_member_password'];
		$redirect        = get_field( 'member_profile_url', 'option' );

		if ( ! empty( $member_password['mz_redirect_url'] ) ) {
			$redirect = $member_password['mz_redirect_url'];
		}

		$auth_response_status = $this->api->get_auth_token( $member_login, $member_password, true );
		if ( ! $auth_response_status ) {
			$this->set_message( 'login_error', 'Invalid username or password.' );

			return false;
		}
		// Member authenticated
		do_action( 'easl_mz_member_authenticated', $member_login, $this->api->get_credential_data( true ), $redirect );

		$member_id = $this->api->get_member_id();
		if ( $member_id ) {
			$this->session->add_data( 'member_id', $member_id );
			$this->session->save_session_data();
		}

		do_action( 'easl_mz_member_looged_id' );

		if ( ! $redirect ) {
			$redirect = site_url();
		}
		if ( wp_redirect( $redirect ) ) {
			exit;
		}

	}

	public function handle_member_logout() {
		if ( empty( $_REQUEST['mz_logout'] ) ) {
			return false;
		}
		if ( ! easl_mz_is_member_logged_in() ) {
			return false;
		}
		do_action( 'easl_mz_member_before_log_out' );

		$this->session->unset_auth_cookie();
		$this->api->clear_credentials();

		do_action( 'easl_mz_member_logged_out' );

		wp_redirect( site_url() );
		exit();
	}

	public function change_member_picture() {
		if ( empty( $_POST['mz_member_id'] || empty( $_FILES['mz_picture_file'] ) ) || ( $_FILES['mz_picture_file']['error'] !== UPLOAD_ERR_OK ) ) {
			return false;
		}
		$member_id = $_POST['mz_member_id'];
		if ( ! easl_mz_is_member_logged_in() ) {
			$this->set_message( 'member_profile_picture', 'You are not allowed to change your profile picture.' );

			return;
		}
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->set_message( 'member_profile_picture', 'You are not allowed to change your profile picture.' );

			return;
		}
		$file_data = file_get_contents( $_FILES['mz_picture_file']['tmp_name'] );
		if ( ! $this->api->update_member_picture( $member_id, $file_data ) ) {
			$this->set_message( 'member_profile_picture', 'Could not update profile picture.' );

			return;
		}
		$this->set_message( 'member_profile', 'Profile picture updated.' );
	}

	public function create_membership() {
		if ( empty( $_POST['mz_member_id'] ) || empty( $_POST['membership_category'] ) ) {
			return false;
		}
		$member_id              = $_POST['mz_member_id'];
		$member_email           = $_POST['mz_member_email'];
		$member_cat             = $_POST['membership_category'];
		$member_name            = $_POST['mz_member_name'];
		$first_name             = $_POST['mz_member_fname'];
		$last_name              = $_POST['mz_member_lname'];
		$renew                  = $_POST['mz_renew'];
		$current_end_date       = $_POST['mz_current_end_date'];
		$current_cat            = $_POST['mz_current_cat'];
		$billing_mode           = $_POST['billing_mode'];
		$jhephardcopy_recipient = $_POST['jhephardcopy_recipient'];

		$require_proof = false;
		if ( in_array( $member_cat, array(
			'trainee_jhep',
			'trainee',
			'nurse_jhep',
			'nurse',
			'allied_pro_jhep',
			'allied_pro'
		) ) ) {
			$require_proof = true;
		}
		$jhep_hard_copy = false;
		if ( in_array( $member_cat, array(
			'regular_jhep',
			'corresponding_jhep',
			'trainee_jhep',
			'nurse_jhep',
			'patient_jhep',
			'emeritus_jhep',
			'allied_pro_jhep'
		) ) ) {
			$jhep_hard_copy = true;
		}

		$membership_cat_name = easl_mz_get_membership_category_name( $member_cat );
		if ( ! $membership_cat_name ) {
			$this->set_message( 'membership_error', 'Membership category not found.' );

			return;
		}


		if ( ! easl_mz_is_member_logged_in() ) {
			$this->set_message( 'membership_error', 'You are not allowed to change your profile picture.' );

			return;
		}
		$current_member_id = $this->session->ge_current_member_id();
		if ( ! $current_member_id ) {
			$current_member_id = $this->api->get_member_id();

			if ( $current_member_id ) {
				$this->session->add_data( 'member_id', $current_member_id );
				$this->session->save_session_data();
			}
		}
		if ( ! $current_member_id || ( $current_member_id != $member_id ) ) {
			$this->set_message( 'membership_error', 'You are not allowed to change your profile picture.' );

			return;
		}

		$membership_name = $member_name . ' - ' . $membership_cat_name;

		$membership_cat_fee = easl_mz_get_membership_fee( $member_cat );

		$initial_date = false;
		if ( $current_end_date ) {
			if ( strtotime( $current_end_date ) < time() ) {
				$current_end_date = 'now';
			}
		} else {
			$current_end_date = 'now';
		}
		if ( $renew == 'yes' ) {
			$initial_date = new DateTime( $current_end_date );
			$initial_date->modify( '+1 day' );
		} else {
			$initial_date = new DateTime( 'now' );
		}

		$membership_start_day = $initial_date->format( 'Y-m-d' );
		$initial_date->modify( '+1 year' );
		$membership_end_date = $initial_date->format( 'Y-m-d' );

		$billing_type = '';
		if ( ! empty( $_POST['membership_payment_type'] ) ) {
			$billing_type = $_POST['membership_payment_type'];
		}
		if ( ! in_array( $billing_type, array( 'offline_payment', 'ingenico_epayments' ) ) ) {
			$billing_type = 'ingenico_epayments';
		}

		$status = 'in_progress';
		if ( $renew && ( $current_end_date != 'now' ) ) {
			$status = 'active';
		}

		switch ( $_POST['membership_payment_type'] ) {
			case 'ingenico_epayments':
				$billing_type = 'online_cc_indiv';
				break;
			case 'offline_payment':
				$billing_type = 'offline_payment';
				$status       = 'in_progress';
				break;
		}

		if ( ! in_array( $billing_mode, array( 'c1', 'c2', 'other' ) ) ) {
			$billing_mode = 'c1';
		}
		$billing_address = array();
		if ( $billing_mode == 'other' ) {
			$billing_address['street']    = ! empty( $_POST['billing_address_street'] ) ? $_POST['billing_address_street'] : '';
			$billing_address['city']      = ! empty( $_POST['billing_address_city'] ) ? $_POST['billing_address_city'] : '';
			$billing_address['state']     = ! empty( $_POST['billing_address_state'] ) ? $_POST['billing_address_state'] : '';
			$billing_address['postalcod'] = ! empty( $_POST['billing_address_postalcode'] ) ? $_POST['billing_address_postalcode'] : '';
			$billing_address['country']   = ! empty( $_POST['billing_address_street'] ) ? $_POST['billing_address_country'] : '';
			$billing_address['georeg']    = easl_mz_get_geo_reg( $billing_address['country'] );
		}

		if ( ! in_array( $jhephardcopy_recipient, array( 'c1', 'c2', 'other' ) ) ) {
			$jhephardcopy_recipient = 'c1';
		}

		$membership_api_data = array(
			'name'           => $membership_name,
			'category'       => $member_cat,
			'status'         => $status,
			'fee'            => $membership_cat_fee,
			'start_date'     => $membership_start_day,
			'end_date'       => $membership_end_date,
			'billing_status' => 'waiting',
			'billing_type'   => $billing_type,
			'billing_mode'   => $billing_mode,
			//'billing_amount' => $membership_cat_fee,
		);

		if ( $billing_mode == 'other' ) {
			$membership_api_data['billing_address_street']     = ! empty( $_POST['billing_address_street'] ) ? $_POST['billing_address_street'] : '';
			$membership_api_data['billing_address_city']       = ! empty( $_POST['billing_address_city'] ) ? $_POST['billing_address_city'] : '';
			$membership_api_data['billing_address_state']      = ! empty( $_POST['billing_address_state'] ) ? $_POST['billing_address_state'] : '';
			$membership_api_data['billing_address_postalcode'] = ! empty( $_POST['billing_address_postalcode'] ) ? $_POST['billing_address_postalcode'] : '';
			$membership_api_data['billing_address_country']    = ! empty( $_POST['billing_address_country'] ) ? $_POST['billing_address_country'] : '';
			$membership_api_data['billing_address_georeg']     = easl_mz_get_geo_reg( $membership_api_data['billing_address_country'] );
		}

		if ( $jhep_hard_copy ) {
			$membership_api_data['jhep_hardcopy']          = 1;
			$membership_api_data['jhephardcopy_recipient'] = $jhephardcopy_recipient;
			if ( $jhephardcopy_recipient == 'other' ) {
				$membership_api_data['jhephardcopyotheraddress_street']     = ! empty( $_POST['jhephardcopyotheraddress_street'] ) ? $_POST['jhephardcopyotheraddress_street'] : '';
				$membership_api_data['jhephardcopyotheraddress_postalcode'] = ! empty( $_POST['jhephardcopyotheraddress_postalcode'] ) ? $_POST['jhephardcopyotheraddress_postalcode'] : '';
				$membership_api_data['jhephardcopyotheraddress_city']       = ! empty( $_POST['jhephardcopyotheraddress_city'] ) ? $_POST['jhephardcopyotheraddress_city'] : '';
				$membership_api_data['jhephardcopyotheraddress_state']      = ! empty( $_POST['jhephardcopyotheraddress_state'] ) ? $_POST['jhephardcopyotheraddress_state'] : '';
				$membership_api_data['jhephardcopyotheraddress_country']    = ! empty( $_POST['jhephardcopyotheraddress_country'] ) ? $_POST['jhephardcopyotheraddress_country'] : '';
				$membership_api_data['jhephardcopyotheraddress_georeg']     = easl_mz_get_geo_reg( $membership_api_data['jhephardcopyotheraddress_country'] );
			}
		}

		$this->api->get_user_auth_token();
		$membership_id = $this->api->create_membership( $membership_api_data );

		if ( ! $membership_id ) {
			$this->set_message( 'membership_error', 'Membership could not be created.' );

			return;
		}
		$membership_number = $this->api->add_membeship_to_member( $member_id, $membership_id );
		if ( ! $membership_number ) {
			$this->set_message( 'membership_error', 'Membership created but it could not be linked to contact.' );

			return;
		}
		$membership_cart_data = array(
			'membership_created_id' => $membership_id,
			'membership_number'     => $membership_number,
		);

		if ( $require_proof && ! empty( $_FILES['supporting_docs'] ) && ( $_FILES['supporting_docs']['error'] === UPLOAD_ERR_OK ) ) {

			if ( ! function_exists( 'wp_handle_upload' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
			}
			$supporting_file_data = wp_handle_upload( $_FILES['supporting_docs'], array( 'test_form' => false ) );
			if ( $supporting_file_data && empty( $supporting_file_data['error'] ) && $supporting_file_data['file'] ) {
				$attachments = array( $supporting_file_data['file'] );
			}

			$subject = 'Membership proof from EASL Memberzone';
			$to      = 'membership@easloffice.eu';
			$message = "Membership Number: {$membership_number}\n";
			$message .= "Member ID: {$member_id}\n";
			foreach ( $membership_api_data as $data_key => $data_value ) {
				$message .= "{$data_key}: {$data_value}\n";
			}

			add_filter( 'wp_mail_from_name', 'easl_mz_mail_form_name', 20 );
			wp_mail( $to, $subject, $message, '', $attachments );
			remove_filter( 'wp_mail_from_name', 'easl_mz_mail_form_name', 20 );
			if ( $attachments[0] ) {
				@unlink( $attachments[0] );
			}

		}

		$redirect_url = easl_membership_thanks_page_url();
		if ( $billing_type == 'offline_payment' ) {
			$redirect_url = add_query_arg( array(
				'membership_status' => 'created_offline',
				'mbs_id'            => $membership_id,
				'mbs_num'           => $membership_number,
				'fname'             => $first_name,
				'lname'             => $last_name
			), $redirect_url );
		} elseif ( $billing_type == 'online_cc_indiv' ) {
			$redirect_url = easl_membership_checkout_url();
		}
		if ( $redirect_url ) {
			wp_redirect( $redirect_url );
			exit();
		}

	}

	public function handle_payment_feedback() {
		$shaw_string = '';
		$passphrase  = 'am1709xtwrn18cmbm24tw3';
		$ingore_keys = array(
			'mz_action',
			'mz_status',
			'mz_fname',
			'mz_lname',
			'membership_id',
			'membership_number',
			'mz_sid',
			'SHASIGN',
		);

		$membership_id     = ! empty( $_GET['membership_id'] ) ? $_GET['membership_id'] : false;
		$membership_number = ! empty( $_GET['membership_number'] ) ? $_GET['membership_number'] : false;
		$mz_fname          = ! empty( $_GET['mz_fname'] ) ? $_GET['mz_fname'] : '';
		$mz_lname          = ! empty( $_GET['mz_lname'] ) ? $_GET['mz_lname'] : '';
		$session_db_id     = ! empty( $_GET['mz_sid'] ) ? $_GET['mz_sid'] : false;
		$response_digest   = ! empty( $_GET['SHASIGN'] ) ? strtoupper( $_GET['SHASIGN'] ) : false;
		$status            = ! empty( $_GET['mz_status'] ) ? $_GET['mz_status'] : false;
		$invoice_number    = ! empty( $_GET['PAYID'] ) ? $_GET['PAYID'] : '';
		$name              = ! empty( $_GET['CN'] ) ? $_GET['CN'] : '';
		$amount            = ! empty( $_GET['amount'] ) ? $_GET['amount'] : '';
		if ( ! $response_digest || ! $membership_id ) {
			die( "Are you sure you want to do this?" );
		}
		$feedback = array();
		foreach ( $_GET as $item_key => $item_value ) {
			if ( ( '' === $_GET[ $item_key ] ) || in_array( $item_key, $ingore_keys ) ) {
				continue;
			}
			$feedback[ strtoupper( $item_key ) ] = $item_value;
		}
		reset( $feedback );
		ksort( $feedback, SORT_NATURAL );
		foreach ( $feedback as $item_key => $item_value ) {
			$shaw_string .= $item_key . '=' . $item_value . $passphrase;
		}
		//$digest = strtoupper( sha1( $shaw_string ) );
		$digest = strtoupper( hash( "sha512", $shaw_string ) );
		if ( $response_digest != $digest ) {
			die( "Are you sure you want to do this?" );
		}
		$current_date  = date( 'Y-m-d' );
		$redirect_url  = easl_membership_thanks_page_url();
		$redirect_type = '';
		if ( $status == 'accepted' ) {
			$membership_api_data = array(
				'status'                              => 'active',
				'billing_status'                      => 'paid',
				'billing_invoice_id'                  => $invoice_number,
				'billing_invoice_date'                => $current_date,
				'billing_invoice_last_generated_date' => $current_date,
				'billing_initiated_on'                => $current_date,
				'billing_amount'                      => $amount,
			);
			$redirect_type       = 'paid_online';
		} elseif ( $status == 'declined' ) {
			$membership_api_data = array(
				'status'         => 'incomplete',
				'billing_status' => 'waiting',
			);
			$redirect_type       = 'declined_online';
		} elseif ( $status == 'cancelled' ) {
			$membership_api_data = array(
				'status'         => 'incomplete',
				'billing_status' => 'waiting',
			);
			$redirect_type       = 'cancelled_online';
		} else {
			$membership_api_data = array(
				'status'         => 'incomplete',
				'billing_status' => 'waiting',
			);
			$redirect_type       = 'failed_online';
		}

		// Update Membership in CRM
		$this->api->get_user_auth_token();
		$updated = $this->api->update_membership( $membership_id, $membership_api_data );
		if ( $updated ) {
			$redirect_url = add_query_arg( array(
				'membership_status' => $redirect_type,
				'mbs_num'           => $membership_number,
				'fname'             => $mz_fname,
				'name'              => $mz_lname,
			), $redirect_url );
		}

		$this->session->clear_session_cart( $session_db_id );
		wp_redirect( $redirect_url );
		exit();
	}

	public function get_member_image() {

		if ( empty( $_REQUEST['member_id'] ) ) {
			die();
		}

		$this->api->get_user_auth_token();
		$image_data = $this->api->get_member_profile_picture_raw( $_REQUEST['member_id'], false );
		if ( ! $image_data ) {
			die();
		}
		header( 'Content-Type: ' . $image_data['type'] );
		echo $image_data['data'];
		die();
	}

	public function get_membership_note() {
		if ( empty( $_REQUEST['note_id'] ) ) {
			die();
		}

		$this->api->get_user_auth_token();
		$note_data = $this->api->get_membership_note_raw( $_REQUEST['note_id'] );
		if ( ! $note_data ) {
			die();
		}
		header( 'Content-Disposition: ' . $note_data['content_disposition'] );
		header("Pragma: public");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header( 'Content-Type: ' . $note_data['content_type'] );
		header( 'Content-Length: ' . $note_data['content_length'] );

		echo $note_data['data'];
		die();
	}

	public function get_vc_shortcodes() {
		$shortcodes = array(
			'easl_mz_member_directory',
			'easl_mz_member_featured',
			'easl_mz_membership',
			'easl_mz_new_membership_form',
			'easl_mz_checkout_form',
			'easl_mz_member_statistics',
			'easl_mz_member_login',
			'easl_mz_new_member_form',
			'easl_mz_members_documents',
			'easl_mz_membership_confirm_message',
			'easl_mz_publications',
		);

		return $shortcodes;
	}

	/**
	 * Load shortcodes for visual composer
	 */
	public function vc_shortcodes() {
		require_once $this->path( 'CORE_DIR', '/class-easl-mz-shortcode.php' );
		foreach ( $this->get_vc_shortcodes() as $shortcode ) {
			$file_name  = str_replace( 'easl_mz_', '', $shortcode );
			$file_name  = str_replace( '_', '-', $file_name );
			$file_name  = strtolower( $file_name );
			$class_file = $this->path( 'SHORTCODES_DIR' ) . "/{$file_name}/{$file_name}.php";
			$map_file   = $this->path( 'SHORTCODES_DIR' ) . "/{$file_name}/map.php";
			if ( file_exists( $class_file ) ) {
				require_once $class_file;
			}
			if ( file_exists( $map_file ) ) {
				vc_lean_map( $shortcode, null, $map_file );
			}
		}
	}

	public function assets() {
		$version       = EASL_MZ_VERSION;
		$googleapi_key = 'AIzaSyCe-SgprAvAbprjsFR96WjAdKb2EVC-kR0';

		wp_enqueue_style( 'easl-mz-styles', $this->asset_url( 'css/easl-member-zone.css' ), array(), $version );
		wp_enqueue_style( 'easl-mz-styles-responsive', $this->asset_url( 'css/responsive.css' ), array(), $version );

		wp_enqueue_script( 'easl-mz-script', $this->asset_url( 'js/script.js' ), array( 'jquery' ), $version, true );
		$ssl_scheme      = is_ssl() ? 'https' : 'http';
		$script_settings = array(
			'homeURL'        => site_url(),
			'ajaxURL'        => admin_url( 'admin-ajax.php', $ssl_scheme ),
			'ajaxActionName' => $this->ajax->get_action_name(),
			'messages'       => $this->get_messages(),
			'membershipFees' => easl_mz_get_membership_category_fees_calculation(),
			'loaderHtml'     => '<div class="easl-mz-loader"><img src="' . get_stylesheet_directory_uri() . '/images/easl-loader.gif" alt="loading..."></div>',
		);

		$this->get_messages();

		wp_localize_script( 'easl-mz-script', 'EASLMZSETTINGS', $script_settings );
	}

	private function add_options_page() {
		if ( function_exists( 'acf_add_options_page' ) ) {
			$pva_settings_page_hook = acf_add_options_page( array(
				'page_title' => 'Member Zone Settins',
				'menu_slug'  => 'member-zone-settings',
				'capability' => 'manage_options',
				'redirect'   => false,
			) );
		}
	}

	public function set_message( $key, $message, $override = false ) {
		if ( $override || empty( $this->messages[ $key ] ) ) {
			$this->messages[ $key ] = array();
		}
		$this->messages[ $key ][] = $message;
	}

	public function get_messages() {
		return $this->messages;
	}

	public function get_message( $key ) {
		if ( isset( $this->messages[ $key ] ) ) {
			return $this->messages[ $key ];
		}

		return false;
	}

	/**
	 * Gets absolute path for file/directory in filesystem.
	 *
	 * @param $name - name of path dir
	 * @param string $file - file name or directory inside path
	 *
	 * @return string
	 * @since  1.0
	 * @access public
	 *
	 */
	public function path( $name, $file = '' ) {
		$path = $this->paths[ $name ] . ( strlen( $file ) > 0 ? '/' . preg_replace( '/^\//', '', $file ) : '' );

		return $path;
	}

	public function asset_url( $file ) {
		return preg_replace( '/\s/', '%20', plugins_url( $this->path( 'ASSETS_DIR_NAME', $file ), __FILE__ ) );
	}

	/**
	 * Get the instance of CR_VCE_Manager
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public static function activate() {
		add_role( 'member', 'Member', array( 'read' => true, 'level_0' => true ) );
	}

	public static function deactivate() {

	}

	/**
	 * @return EASL_MZ_Session_Handler
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * @return EASL_MZ_API
	 */
	public function getApi() {
		return $this->api;
	}
}

register_activation_hook( __FILE__, array( 'EASL_MZ_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'EASL_MZ_Manager', 'deactivate' ) );
// Finally initialize
EASL_MZ_Manager::get_instance();