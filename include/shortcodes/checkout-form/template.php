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
 * Shortcode class EASL_VC_MZ_Membership_Checkout_Form
 * @var $this EASL_VC_MZ_Membership_Checkout_Form
 */
$el_class      = '';
$el_id         = '';
$css_animation = '';
$title         = '';
$atts          = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$css_animation = $this->getCSSAnimation( $css_animation );

$class_to_filter = 'wpb_easl_mz_membership_checkout_form wpb_content_element ' . $this->getCSSAnimation( $css_animation );
$class_to_filter .= vc_shortcode_custom_css_class( $css, ' ' ) . $this->getExtraClass( $el_class );
$css_class       = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );

$wrapper_attributes = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}

$session_data = easl_mz_get_current_session_data();

$cart_data = isset( $session_data['cart_data'] ) ? $session_data['cart_data'] : array();

$pspid = 'EASLEvent2';


if ( easl_mz_is_member_logged_in() ):
	easl_mz_enqueue_select_assets();
	$api       = easl_mz_get_manager()->getApi();
	$session   = easl_mz_get_manager()->getSession();
	$member_id = $session->ge_current_member_id();
	$api->get_user_auth_token();
	$member     = $api->get_member_details( $member_id, false );
	$membership = $api->get_membership_details( $cart_data['memberhsip_created_id'], false );
	if ( $member && $membership ) {
		$billing_amount = intval( $membership['billing_amount'] * 100 );

		$member_name_parts = array();
		if ( $member['salutation'] ) {
			$member_name_parts[] = $member['salutation'];
		}
		if ( $member['first_name'] ) {
			$member_name_parts[] = $member['first_name'];
		}
		if ( $member['last_name'] ) {
			$member_name_parts[] = $member['last_name'];
		}
		?>

        <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
			<?php if ( $title ): ?>
                <h2 class="mz-page-heading"><?php echo $title; ?></h2>
			<?php endif; ?>
            <div class="easl-mz-membership-checkout-form">
                <div class="easl-mz-nottice">
					<?php if ( empty( $cart_data ) || empty( $cart_data['memberhsip_created_id'] ) ): ?>
                        <p>You don't have added any membership.</p>
					<?php endif; ?>
                </div>
                <form method="post" action="https://ogone.test.v-psp.com/ncol/test/orderstandard_utf8.asp" id="form1" name="form1">
                    <input type="hidden" name="member_id" value="<?php echo $member['id']; ?>">
                    <input type="hidden" name="membership_id" value="<?php echo $membership['id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $membership['billing_amount']; ?>">


                    <!-- general parameters: see Form parameters -->

                    <input type="hidden" name="PSPID" value="<?php echo $pspid; ?>">

                    <input type="hidden" name="ORDERID" value="<?php echo $membership['id']; ?>">

                    <input type="hidden" name="AMOUNT" value="<?php echo $billing_amount; ?>">

                    <input type="hidden" name="CURRENCY" value="EUR">


                    <!-- check before the payment: see Security: Check before the payment -->

                    <input type="hidden" name="SHASIGN" value="">


                    <!-- post payment redirection: see Transaction feedback to the customer -->

                    <input type="hidden" name="ACCEPTURL" value="">

                    <input type="hidden" name="DECLINEURL" value="">

                    <input type="hidden" name="EXCEPTIONURL" value="">

                    <input type="hidden" name="CANCELURL" value="">

                    <div class="mzcheckout-summery">
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Order ID:</span>
                            <span class="mzcheckout-summery-value"><?php echo $membership['id']; ?></span>
                        </div>
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Order Title:</span>
                            <span class="mzcheckout-summery-value"><?php echo $membership['name']; ?></span>
                        </div>
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Amount:</span>
                            <span class="mzcheckout-summery-value"><?php echo $membership['billing_amount']; ?>€</span>
                        </div>
                    </div>

                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_name">Billing Name</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="CN" id="billing_name" value="<?php echo esc_attr( implode( ' ', $member_name_parts ) ); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_email">Billing email</label>
                            <div class="mzms-field-wrap">
                                <input type="email" name="EMAIL" id="billing_email" value="<?php echo $member['email1']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_street">Billing street address</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="OWNERADDRESS" id="billing_street" value="<?php echo $member['primary_address_street']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_city">Billing city</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="OWNERCTY" id="billing_city" value="<?php echo $member['primary_address_city']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_state">Billing state</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="OWNERTOWN" id="billing_state" value="<?php echo $member['primary_address_state']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_zip">Billing zipcode</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="OWNERZIP" id="billing_zip" value="<?php echo $member['primary_address_postalcode']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="mzms-fields-row">
                        <div class="mzms-fields-con">
                            <label class="mzms-field-label" for="billing_telephone">Billing telephone</label>
                            <div class="mzms-field-wrap">
                                <input type="text" name="OWNERTELNO" id="billing_telephone" value="<?php echo $member['phone_work']; ?>">
                            </div>
                        </div>
                    </div>

                    <span class="mz-input-submit-wrap mzms-button"><input type="submit" value="Submit" id="submit2" name="submit2"></span>

                </form>
            </div>
        </div>
	<?php } ?>
<?php endif; ?>


