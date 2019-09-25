<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class EASL_MZ_Shortcode extends WPBakeryShortCode {
	/**
	 * @return mixed
	 */
	protected function geTemplatePathForThisSC() {
		$file_name = str_replace( 'easl_mz_', '', $this->shortcode );
		$file_name = strtolower( $file_name );
		$file_name =  str_replace( '_', '-', $file_name );
		return easl_mz_get_manager()->path('SHORTCODES_DIR') . "/{$file_name}/template.php";
	}

	/**
	 * Find html template for shortcode output.
	 */
	protected function findShortcodeTemplate() {
		// Check template path in shortcode's mapping settings
		if ( ! empty( $this->settings['html_template'] ) && is_file( $this->settings( 'html_template' ) ) ) {
			return $this->setTemplate( $this->settings['html_template'] );
		}

		// Check template in theme directory
		$user_template = vc_shortcodes_theme_templates_dir( $this->getFileName() . '.php' );
		if ( is_file( $user_template ) ) {
			return $this->setTemplate( $user_template );
		}
		// Check plugin dir
		$sc_template = $this->geTemplatePathForThisSC();
		if ( is_file( $sc_template ) ) {
			return $this->setTemplate( $sc_template );
		}
		// Check default place
		$default_dir = vc_manager()->getDefaultShortcodesTemplatesDir() . '/';
		if ( is_file( $default_dir . $this->getFileName() . '.php' ) ) {
			return $this->setTemplate( $default_dir . $this->getFileName() . '.php' );
		}

		return '';
	}
}