<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Session_Handler {
	protected $session_db_id;
	protected $_cookie;
	protected $_table;
	protected $_member_login;
	protected $_data = array();
	protected $_dirty = false;
	protected static $_instance;

	protected function __construct() {
		$this->_cookie = 'easl_mz_session_' . COOKIEHASH;
		$this->_table  = $GLOBALS['wpdb']->prefix . 'mz_sessions';
	}

	final public static function get_instance() {
		if ( empty( self::$_instance ) && ! is_a( self::$_instance, 'EASL_MZ_Session_Handler' ) ) {
			self::$_instance = new EASL_MZ_Session_Handler;
		}

		return self::$_instance;
	}


	public function init() {
		$this->init_session_cookie();
		//add_action( 'shutdown', array( $this, 'save_data' ), 20 );
		add_action( 'easl_mz_member_authenticated', array( $this, 'set_auth_cookie' ), 20, 2 );
		add_action( 'easl_mz_member_token_expired', array( $this, 'unset_auth_cookie' ), 20, 1 );
		add_action( 'easl_mz_member_token_refreshed', array( $this, 'refresh_session_data_form_api' ), 20, 2 );
	}

	public function init_session_cookie() {
		$cookie_elements = $this->parse_auth_cookie();
		if ( ! $cookie_elements ) {
			return false;
		}
		$member_login = $cookie_elements['member_login'];
		$hmac         = $cookie_elements['hmac'];
		$token        = $cookie_elements['token'];
		$expired      = $expiration = $cookie_elements['expiration'];

		if ( $expired < time() ) {
			return false;
		}

		$key = wp_hash( $member_login . '|' . $expiration . '|' . $token, 'secure_auth' );

		// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
		$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
		$hash = hash_hmac( $algo, $member_login . '|' . $expiration . '|' . $token, $key );

		if ( ! hash_equals( $hash, $hmac ) ) {
			return false;
		}

		$session = $this->get_session_by_token( $token );

		if ( ! $session ) {
			return false;
		}
		// Member login cookie verified
		$this->session_db_id = $session->session_id;
		$this->_member_login = $session->member_login;
		$this->_data         = maybe_unserialize( $session->session_value );
		$api_data            = $this->prepare_api_credential_data();

		if ( $this->api_expired( $api_data ) ) {
			$this->unset_auth_cookie();

			return false;
		}
		easl_mz_get_manager()->getApi()->set_credentials( $api_data );
	}

	public function api_expired( $api_data ) {
		if ( empty( $api_data['access_token'] ) || empty( $api_data['refresh_token'] ) || empty( $api_data['expires_in'] ) || empty( $api_data['refresh_expires_in'] ) || empty( $api_data['token_set_time'] ) ) {
			return true;
		}
		if ( ( $api_data['token_set_time'] + $api_data['refresh_expires_in'] - 10 ) < time() ) {
			return true;
		}

		return false;
	}

	public function prepare_api_credential_data() {
		$data = array(
			'access_token'       => '',
			'refresh_token'      => '',
			'download_token'     => '',
			'expires_in'         => 0,
			'refresh_expires_in' => 0,
			'token_set_time'     => 0,
		);
		if ( isset( $this->_data['access_token'] ) ) {
			$data['access_token'] = $this->_data['access_token'];
		}
		if ( isset( $this->_data['refresh_token'] ) ) {
			$data['refresh_token'] = $this->_data['refresh_token'];
		}
		if ( isset( $this->_data['download_token'] ) ) {
			$data['download_token'] = $this->_data['download_token'];
		}
		if ( isset( $this->_data['expires_in'] ) ) {
			$data['expires_in'] = $this->_data['expires_in'];
		}
		if ( isset( $this->_data['refresh_expires_in'] ) ) {
			$data['refresh_expires_in'] = intval( $this->_data['refresh_expires_in'] );
		}
		if ( isset( $this->_data['token_set_time'] ) ) {
			$data['token_set_time'] = intval( $this->_data['token_set_time'] );
		} elseif ( isset( $this->_data['login'] ) ) {
			$data['token_set_time'] = intval( $this->_data['login'] );
		}

		return $data;
	}

	public function has_member_active_session() {
		return (bool) $this->session_db_id;
	}

	public function get_current_session_data() {
		return $this->_data;
	}

	public function unset_auth_cookie( $is_member = true ) {
		if ( ! $is_member ) {
			return false;
		}
		$this->delete_session( $this->session_db_id );
		$this->session_db_id = false;
		$this->_member_login = false;
		$this->_data         = array();
		setcookie( $this->_cookie, '', time() - 14 * DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, true, true );
	}

	public function set_auth_cookie( $member_login, $data = array() ) {
		$this->_member_login = $member_login;

		$session_data = $this->sanitize_session_data( $data );

		if ( ! empty( $data['refresh_expires_in'] ) ) {
			$expiration = time() + intval( $data['refresh_expires_in'] );
		} else {
			$expiration = time() + 14 * DAY_IN_SECONDS;
		}

		$token    = $this->generate_token();
		$verifier = $this->hash_token( $token );

		$seesion_db_id = $this->create_session( $this->_member_login, $verifier, $session_data, $expiration );

		if ( ! $seesion_db_id ) {
			easl_mz_get_manager()->set_message( 'login_error', 'Failed to create member session.' );

			return;
		}
		$this->session_db_id = $seesion_db_id;
		$this->_data         = $session_data;

		$auth_cookie = $this->generate_auth_cookie( $expiration, $token );

		setcookie( $this->_cookie, $auth_cookie, $expiration, COOKIEPATH, COOKIE_DOMAIN, true, true );
	}

	public function generate_auth_cookie( $expiration, $token ) {
		$key = wp_hash( $this->_member_login . '|' . $expiration . '|' . $token, 'secure_auth' );

		// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
		$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
		$hash = hash_hmac( $algo, $this->_member_login . '|' . $expiration . '|' . $token, $key );

		$cookie = $this->_member_login . '|' . $expiration . '|' . $token . '|' . $hash;

		return $cookie;
	}

	public function refresh_session_data_form_api( $api_data = array(), $is_member = true ) {
		if ( ! $is_member ) {
			return false;
		}
		foreach ( $api_data as $key => $value ) {
			$this->add_data( $key, $value );
		}
		$this->save_session_data();
	}

	public function parse_auth_cookie() {
		if ( empty( $_COOKIE[ $this->_cookie ] ) ) {
			return false;
		}

		$cookie = $_COOKIE[ $this->_cookie ];

		$cookie_elements = explode( '|', $cookie );
		if ( count( $cookie_elements ) !== 4 ) {
			return false;
		}

		list( $member_login, $expiration, $token, $hmac ) = $cookie_elements;

		return compact( 'member_login', 'expiration', 'token', 'hmac' );
	}

	public function sanitize_session_data( $data ) {
		$defaults     = array(
			'access_token'       => '',
			'expires_in'         => '',
			'scope'              => '',
			'refresh_token'      => '',
			'refresh_expires_in' => '',
			'download_token'     => '',
			'login'              => time(),
		);
		$session_data = wp_parse_args( $data, $defaults );
		// IP address.
		if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$session_data['ip'] = $_SERVER['REMOTE_ADDR'];
		}

		// User-agent.
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$session_data['ua'] = wp_unslash( $_SERVER['HTTP_USER_AGENT'] );
		}

		return $session_data;
	}

	protected function generate_token() {
		$token = wp_generate_password( 43, false, false );

		return $token;
	}

	public function ge_current_member_id() {
		if ( isset( $this->_data['member_id'] ) ) {
			return $this->_data['member_id'];
		}

		return false;
	}

	public function get_current_session_db_id() {
		return $this->session_db_id;
	}

	protected function verify_token( $token ) {
		return (bool) $this->get_session_by_token( $token );
	}

	public function destroy_current_session() {

	}

	public function get_session_by_token( $token ) {
		global $wpdb;

		$verifier = $this->hash_token( $token );
		$sql      = $wpdb->prepare( "SELECT * FROM {$this->_table} WHERE session_token=%s", $verifier );

		return $wpdb->get_row( $sql );
	}

	public function get_session_by_db_id( $id ) {
		global $wpdb;

		$sql = $wpdb->prepare( "SELECT * FROM {$this->_table} WHERE session_id=%d", $id );

		return $wpdb->get_row( $sql );
	}

	private function hash_token( $token ) {
		// If ext/hash is not present, use sha1() instead.
		if ( function_exists( 'hash' ) ) {
			return hash( 'sha256', $token );
		} else {
			return sha1( $token );
		}
	}

	public function add_data( $key, $value ) {
		if ( isset( $this->_data[ $key ] ) && ( $this->_data[ $key ] == $value ) ) {
			return false;
		}
		$this->_data[ $key ] = $value;
		$this->_dirty        = true;
	}

	public function save_session_data() {
		global $wpdb;
		if ( ! $this->_dirty || empty( $this->session_db_id ) ) {
			return false;
		}

		$wpdb->update(
			$this->_table,
			array(
				'session_value' => maybe_serialize( $this->_data )
			),
			array(
				'session_id' => $this->session_db_id
			),
			array( '%s' ),
			array( '%d' )
		);
		$this->_dirty = false;
	}

	public function clear_session_cart( $id ) {
		global $wpdb;
		if ( ! $id ) {
			return false;
		}
		$session = $this->get_session_by_db_id( $id );

		if ( ! $session ) {
			return true;
		}
		// Member login cookie verified
		$session_data = maybe_unserialize( $session->session_value );
		if ( empty( $session_data['cart_data'] ) ) {
			return true;
		}
		unset( $session_data['cart_data'] );

		$wpdb->update(
			$this->_table,
			array(
				'session_value' => maybe_serialize( $session_data )
			),
			array(
				'session_id' => $id
			),
			array( '%s' ),
			array( '%d' )
		);

		return true;
	}

	public function create_session( $member_login, $session_token, $session_value, $session_expiry ) {
		global $wpdb;
		$data   = array(
			'member_login'   => $member_login,
			'session_token'  => $session_token,
			'session_value'  => maybe_serialize( $session_value ),
			'session_expiry' => $session_expiry,
		);
		$format = array( '%s', '%s', '%s', '%d' );
		if ( $wpdb->insert( $this->_table, $data, $format ) ) {
			return $wpdb->insert_id;
		}

		return false;
	}

	public function delete_session( $id ) {
		global $wpdb;
		if ( $id ) {
			$wpdb->delete( $this->_table, array( 'session_id' => $id ), array( '%d' ) );
		}
	}
}