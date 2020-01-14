<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


/**
 * Shortcode attributes
 * @var $atts
 * @var $title
 * @var $el_class
 * @var $el_id
 * @var $css
 * Shortcode class EASL_VC_MZ_Member_Statistics
 * @var $this EASL_VC_MZ_Member_Statistics
 */

$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$subtitle      = '';

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

$class_to_filter = 'wpb_easl_mz_statistics wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );
$css_animation   = $this->getCSSAnimation( $css_animation );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

easl_mz_enqueue_select_assets();
$this->enqueue_map_scripts();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="mz-statistics-inner mz-ms-loading">
	    <?php if ( $title ): ?>
            <h2 class="mz-page-heading"><?php echo $title; ?></h2>
	    <?php endif; ?>
	    <?php if ( $subtitle ): ?>
            <h4 class="mz-subheading"><?php echo $subtitle; ?></h4>
	    <?php endif; ?>
        <div class="mz-stats-container">

        </div>
        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>

</div>
