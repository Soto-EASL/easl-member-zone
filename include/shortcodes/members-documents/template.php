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

?>
<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="easl-mz-mydocs-inner">
        <div class="mzmd-expiry-notice">
            <div class="mzmd-expiry-time">
                <p class="mzmd-expiry-time-label">Time remaining on your membership</p>
                <p class="mzmd-expiry-time-value"><strong>01</strong><span>month</span>
                    <strong>02</strong><span>days</span></p>
            </div>
            <div class="mzmd-expiry-message">
                <span>Your membership is due to expire</span>
            </div>
            <div class="mzmd-expiry-renew">
                <a href="#">Renew Today</a>
            </div>
        </div>
		<?php if ( $back_link ): ?>
            <div class="easl-mz-back-link-wrap">
                <a class="easl-mz-back-link" href="<?php echo $back_link; ?>">Back</a>
            </div>
		<?php endif; ?>
		<?php if ( $title ): ?>
            <h2 class="mz-page-heading"><?php echo $title; ?></h2>
		<?php endif; ?>
        <div class="mzmd-docs-table">
            <div class="mzmd-docs-table-row mzmd-docs-table-head">
                <div class="mzmd-docs-table-col mzmd-docs-table-col-year">Year</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-type">Membership Type</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-download">&nbsp;</div>
            </div>
            <div class="mzmd-docs-table-row">
                <div class="mzmd-docs-table-col mzmd-docs-table-col-year">2017-2018</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-type">Full Membership</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-download">
                    <a class="mzmd-download-link" href="#">Download Invoice</a></div>
            </div>
            <div class="mzmd-docs-table-row">
                <div class="mzmd-docs-table-col mzmd-docs-table-col-year">2016-2017</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-type">Full Membership</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-download">
                    <a class="mzmd-download-link" href="#">Download Invoice</a></div>
            </div>
            <div class="mzmd-docs-table-row">
                <div class="mzmd-docs-table-col mzmd-docs-table-col-year">2015-2016</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-type">Full Membership</div>
                <div class="mzmd-docs-table-col mzmd-docs-table-col-download">
                    <a class="mzmd-download-link" href="#">Download Invoice</a></div>
            </div>
        </div>
    </div>
</div>
