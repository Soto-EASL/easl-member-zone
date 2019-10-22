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
 * Shortcode class EASL_VC_MZ_Member_Profile
 * @var $this EASL_VC_MZ_Member_Profile
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$back_link     = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );

extract( $atts );

$class_to_filter = 'wpb_easl_mz_members_docs wpb_content_element ' . $this->getCSSAnimation( $css_animation );
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
$back_link = easl_membership_page_url();
?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-mz-mydocs-inner mz-docs-loading">
        <div class="easl-mz-membership-docs-con">
            <div class="easl-mz-back-link-wrap">
                <a class="easl-mz-back-link" href="<?php echo $back_link; ?>">Back</a>
            </div>
            <h2 class="mz-page-heading">My documents</h2>
            <div class="mzmd-docs-table">
                <div class="mzmd-docs-table-row mzmd-docs-table-head">
                    <div class="mzmd-docs-table-col mzmd-docs-table-col-duration">Membership duration</div>
                    <div class="mzmd-docs-table-col mzmd-docs-table-col-type">Membership category</div>
                    <div class="mzmd-docs-table-col mzmd-docs-table-col-download">&nbsp;</div>
                </div>
            </div>
        </div>
        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>
</div>
