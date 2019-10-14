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
 * Shortcode class EASL_VC_MZ_New_Membership_Form
 * @var $this EASL_VC_MZ_New_Membership_Form
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title_add     = '';
$title_renew   = '';


$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

$class_to_filter = 'wpb_easl_mz_new_membership_form wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

if(!$title_renew) {
	$title_renew = 'Renew Membership';
}
if(!$title_add) {
	$title_renew = 'Add Membership';
}

if ( easl_mz_is_member_logged_in() ):
	easl_mz_enqueue_select_assets();
	$payment_type = ! empty( $_GET['mz_renew'] ) ? 'yes' : 'no';

	if ( ! empty( $_GET['mz_renew'] ) ) {
		$title = $title_renew;
	} else {
		$title = $title_add;
	}

	?>

    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
		<?php if ( $title ): ?>
            <h2 class="mz-page-heading"><?php echo $title; ?></h2>
		<?php endif; ?>
        <div class="easl-mz-crm-view easl-mz-new-membership-form easl-mz-loading" data-paymenttype="<?php echo $payment_type; ?>">

        </div>
    </div>
<?php endif; ?>


