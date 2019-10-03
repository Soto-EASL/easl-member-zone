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
 * Shortcode class EASL_VC_MZ_New_Member_Form
 * @var $this EASL_VC_MZ_New_Member_Form
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );

$template_base = easl_mz_get_manager()->path( 'SHORTCODES_DIR', '/new-member-form' );

extract( $atts );

$class_to_filter = 'wpb_easl_mz_new_member_form wpb_content_element ' . $this->getCSSAnimation( $css_animation );
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

if ( ! easl_mz_is_member_logged_in() ):

	easl_mz_enqueue_datepicker_assets();
	easl_mz_enqueue_select_assets();

	?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="easl-mz-new-member-form-inner">
			<?php if ( $title ): ?>
                <h2 class="mz-page-heading"><?php echo $title; ?></h2>
			<?php endif; ?>
            <form id="easl-mz-new-member-form" action="" method="post" autocomplete="off">
				<?php include $template_base . '/partials/fields-basic.php'; ?>
                <div class="mzms-fields-separator"></div>
				<?php include $template_base . '/partials/fields-global.php'; ?>
                <div class="mzms-fields-separator"></div>
				<?php include $template_base . '/partials/fields-communication.php'; ?>
                <div class="mzms-fields-separator"></div>
				<?php include $template_base . '/partials/fields-address.php'; ?>
                <div class="mzms-fields-separator"></div>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label" for="mzms_personal_profile">Personal Profile</label>
                        <div class="mzms-field-wrap">
                            <textarea name="description" id="mzms_personal_profile" placeholder=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <div class="mzms-field-wrap mzms-inline-checkbox">
                            <label for="mzf_terms_condition" class="easl-custom-checkbox">
                                <input type="checkbox" name="terms_condition" id="mzf_terms_condition" value="1">
                                <span>I agree to <a href="https://easl.eu/terms-conditions/" target="+_blank">terms and conditions</a></span>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row">
                    <p>*mandatory fields</p>
                </div>
                <div class="mzms-fields-separator"></div>
                <div class="mzms-fields-row mzms-submit-row">
                    <button class="mzms-submit">Create Account</button>
                </div>
            </form>
        </div>
        <div class="easl-mz-membership-loader">
            <div class="easl-mz-loader">
                <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading..."></div>
        </div>
    </div>
<?php else: ?>
    <div class="mz-new-member-page-alreadyloggedin">
        <p>You are already logged in!</p>
        <p>Check your <a href="<?php echo easl_membership_page_url(); ?>">membership profile</a>.</p>
    </div>
<?php endif; ?>
