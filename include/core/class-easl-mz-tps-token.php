<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_JHEP_Tokenizer {
	private $host_name;
	private $society_id;
	private $member_level;
	private $encrypt_key;
	private $target_journal_url;
	private $test_url;
	private $timestamp_ms;

	private static $_instance;

	protected function __construct() {
		$this->host_name          = 'https://easl.eu';
		$this->society_id         = '116';
		$this->member_level       = 'memberaccess';
		$this->encrypt_key        = 'b67e864abdddf83fb67e864abdddf83f';
		$this->target_journal_url = 'https://www.journal-of-hepatology.eu/';
		$this->test_url           = 'https://tps.elsevier-jbs.com/test';
		$this->timestamp_ms       = time() . "000";
	}

	public function get_plain_text() {
		return "{$this->host_name}|{$this->timestamp_ms}|{$this->member_level}";
	}

	function get_pkcs5_padded_text() {
		$blocksize = 8;
		$text      = $this->get_plain_text();
		$pad       = $blocksize - ( strlen( $text ) % $blocksize );

		return $text . str_repeat( chr( $pad ), $pad );
	}

	public function get_decoded_key() {
		return base64_decode( $this->encrypt_key );
	}

	public function get_encrypted_token() {
		$result = openssl_encrypt( $this->get_pkcs5_padded_text(), 'DES-EDE3', $this->get_decoded_key(), OPENSSL_NO_PADDING );

		return base64_encode( $result );
	}

	public function get_tps_string() {
		return "{$this->society_id}.{$this->get_encrypted_token()}";
	}

	public function get_tps_link() {
		return add_query_arg( array(
			'tpstoken' => urlencode( $this->get_tps_string() )
		), $this->target_journal_url );
	}

	public function get_tps_test_link() {
		return add_query_arg( array(
			'tpstoken' => urlencode( $this->get_tps_string() )
		), $this->test_url );
	}

	/**
	 * @return EASL_MZ_JHEP_Tokenizer
	 */
	public static function get_instance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
}

function easl_mz_get_jhep_link( $test = false ) {
	if ( $test ) {
		return EASL_MZ_JHEP_Tokenizer::get_instance()->get_tps_test_link();
	}

	return EASL_MZ_JHEP_Tokenizer::get_instance()->get_tps_link();
}