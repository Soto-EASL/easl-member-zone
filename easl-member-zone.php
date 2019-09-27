<?php
/*
Plugin Name: EASL Member Zone
Description: The plugin contains the functionality for EASL Member zone
Version: 1.0
Author: Soto
Author URI: http://www.gosoto.co/
Text Domain: easlmz
License: GPLv2 or later
*/
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

define( 'EASL_MZ_VERSION', '1.0' );

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
		}
	}

	public function handle_member_login() {
		if ( empty( $_POST['mz_member_login'] ) || empty( $_POST['mz_member_password'] ) ) {
			return false;
		}
		$member_login    = $_POST['mz_member_login'];
		$member_password = $_POST['mz_member_password'];
		$redirect        = $_POST['mz_rdirect_url'];

		$auth_response_status = $this->api->get_auth_token( $member_login, $member_password, true );
		if ( ! $auth_response_status ) {
			$this->set_message( 'login_error', 'Invalid username/password.' );

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
		$member_id        = $_POST['mz_member_id'];
		$member_cat       = $_POST['membership_category'];
		$member_name      = $_POST['mz_member_name'];
		$renew            = $_POST['mz_renew'];
		$current_end_date = $_POST['mz_current_end_date'];
		$current_cat      = $_POST['mz_current_cat'];
		//$years            = max( 1, absint( $_POST['membership_years'] ) );
		$years = 1;
		/**
		 * @todo ask Zoey/Emily to confirm if active member needs to upate their current membership
		 */
//		if($current_cat != $member_cat) {
//			$renew = 'no';
//		}

		$membership_cate_name = easl_mz_get_membership_category_name( $member_cat );
		if ( ! $membership_cate_name ) {
			$this->set_message( 'membership_error', 'Membership catgory not found.' );

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

		$membership_name    = $member_name . ' - ' . $membership_cate_name;
		$date_year_modifier = '';
		if ( $years > 1 ) {
			$membership_name    .= ' - ' . $years . ' years';
			$date_year_modifier = '+' . $years . ' years';
		} else {
			$date_year_modifier = '+1 year';
		}
		$membership_cat_fee = $years * easl_mz_get_membership_fee( $member_cat );

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
		$initial_date->modify( $date_year_modifier );
		$membership_end_date = $initial_date->format( 'Y-m-d' );

		$billing_type = '';
		if ( ! empty( $_POST['membership_payment_type'] ) ) {
			$billing_type = $_POST['membership_payment_type'];
		}
		if ( ! in_array( $billing_type, array( 'offline_payment', 'ingenico_epayments' ) ) ) {
			$billing_type = 'ingenico_epayments';
		}

		$membership_api_data = array(
			'name'           => $membership_name,
			'category'       => $member_cat,
			'status'         => 'in_progress',
			'fee'            => $membership_cat_fee,
			'start_date'     => $membership_start_day,
			'end_date'       => $membership_end_date,
			'billing_status' => 'waiting',
			'billing_type'   => $billing_type,
			'billing_amount' => $membership_cat_fee,
		);
		$this->api->get_user_auth_token();
		$membership_id       = $this->api->create_membership( $membership_api_data );
		if ( ! $membership_id ) {
			$this->set_message( 'membership_error', 'Membership could not be created.' );

			return;
		}
		$result = $this->api->add_membeship_to_member( $member_id, $membership_id );
		if ( ! $result ) {
			$this->set_message( 'membership_error', 'Membership created but it could not be linked to contact.' );

			return;
		}
		$membership_cart_data = array(
			'memberhsip_created_id' => $membership_id
		);
		$redirect_url         = easl_membership_thanks_page_url();
		if ( $billing_type == 'offline_payment' ) {
			$redirect_url = add_query_arg( 'membership_created', true, $redirect_url );
		} elseif ( $billing_type == 'ingenico_epayments' ) {
			$redirect_url = easl_membership_checkout_url();
			$this->session->add_data( 'cart_data', $membership_cart_data );
			$this->session->save_session_data();
		}
		if ( $redirect_url ) {
			wp_redirect( $redirect_url );
			exit();
		}

	}

	public function get_vc_shortcodes() {
		$shortcodes = array(
			'easl_mz_member_directory',
			'easl_mz_member_featured',
			'easl_mz_membership',
			'easl_mz_new_membership_form',
			'easl_mz_member_statistics',
			'easl_mz_member_login',
			'easl_mz_new_member_form',
			'easl_mz_members_documents',
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
		$version       = time();
		$googleapi_key = 'AIzaSyCe-SgprAvAbprjsFR96WjAdKb2EVC-kR0';

		wp_enqueue_style( 'easl-mz-styles', $this->asset_url( 'css/easl-member-zone.css' ), array(), $version );

		wp_enqueue_script( 'easl-mz-script', $this->asset_url( 'js/script.js' ), array( 'jquery' ), $version, true );
		$ssl_scheme      = is_ssl() ? 'https' : 'http';
		$script_settings = array(
			'homeURL'        => site_url(),
			'ajaxURL'        => admin_url( 'admin-ajax.php', $ssl_scheme ),
			'ajaxActionName' => $this->ajax->get_action_name(),
			'membershipFees' => easl_mz_get_membership_category_fees_calculation(),
			'loaderHtml'     => '<div class="easl-mz-loader"><img src="' . get_stylesheet_directory_uri() . '/images/easl-loader.gif" alt="loading..."></div>',
		);
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
	 * @return mixed
	 */
	public function getSession() {
		return $this->session;
	}

	/**
	 * @return mixed
	 */
	public function getApi() {
		return $this->api;
	}
}

register_activation_hook( __FILE__, array( 'EASL_MZ_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'EASL_MZ_Manager', 'deactivate' ) );
// Finally initialize
EASL_MZ_Manager::get_instance();