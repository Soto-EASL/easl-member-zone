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

$wrapper_attributes     = array();
if ( ! empty( $el_id ) ) {
	$wrapper_attributes[] = 'id="' . esc_attr( $el_id ) . '"';
}
if ( ! empty( $css_class ) ) {
	$wrapper_attributes[] = 'class="' . esc_attr( $css_class ) . '"';
}


if ( easl_mz_is_member_logged_in() ):
	$api = easl_mz_get_manager()->getApi();
	$session            = easl_mz_get_manager()->getSession();
	$user_session_db_id = $session->get_current_session_db_id();
	$member_id          = $session->ge_current_member_id();
	$member             = false;
	$membership         = false;

	$membership_checkout_id = '';

	$billing_type = '';

	if ( $member_id ) {
		$members_latest_membership = $api->get_members_latest_membership( $member_id );
		if ( isset( $members_latest_membership['id'] ) ) {
			$membership_checkout_id = $members_latest_membership['id'];
			$billing_type           = $members_latest_membership['billing_type'];
		}
	}

	if ( ( 'online_cc_indiv' == $billing_type ) && $membership_checkout_id ) {
		$api->get_user_auth_token();
		$member     = $api->get_member_details( $member_id, false );
		$membership = $api->get_membership_details( $membership_checkout_id, false );
	}
	if ( $member && $membership ):
		$billing_amount = intval( $membership['fee'] * 100 );

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
		$member_name_parts = implode( ' ', $member_name_parts );
		$billing_address   = array();

		if ( $membership['billing_mode'] == 'c1' ) {
			$billing_address_title         = 'Your contacts institution address';
			$billing_address['street']     = preg_replace( '/[\t\r\n]+/', ' ', trim( $member['primary_address_street'] ) );
			$billing_address['postalcode'] = $member['primary_address_postalcode'];
			$billing_address['city']       = $member['primary_address_city'];
			$billing_address['state']      = $member['primary_address_state'];
			$billing_address['country']    = $member['primary_address_country'];
		} elseif ( $membership['billing_mode'] == 'c2' ) {
			$billing_address_title         = 'Your contacts home address';
			$billing_address['street']     = preg_replace( '/[\t\r\n]+/', ' ', trim( $member['alt_address_street'] ) );
			$billing_address['postalcode'] = $member['alt_address_postalcode'];
			$billing_address['city']       = $member['alt_address_city'];
			$billing_address['state']      = $member['alt_address_state'];
			$billing_address['country']    = $member['alt_address_country'];
		} else {
			$billing_address['street']     = preg_replace( '/[\t\r\n]+/', ' ', trim( $membership['billing_address_street'] ) );
			$billing_address['postalcode'] = $membership['billing_address_postalcode'];
			$billing_address['city']       = $membership['billing_address_city'];
			$billing_address['state']      = $membership['billing_address_state'];
			$billing_address['country']    = $membership['billing_address_country'];

			$billing_address_title = easl_mz_get_formatted_address( $billing_address );
		}

		$pspid              = 'EASLEvent2';
		$saw_in_pass_phrase = 'Omj010159gj061148dsm190384';
		$sha_string         = '';

		?>

        <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
			<?php if ( $title ): ?>
                <h2 class="mz-page-heading"><?php echo $title; ?></h2>
			<?php endif; ?>
            <div class="easl-mz-membership-checkout-form">
                <form method="post" action="https://secure.ogone.com/ncol/prod/orderstandard_utf8.asp" id="form1" name="form1">
                    <!-- general parameters: see Form parameters -->
					<?php
					$billing_country = easl_mz_get_country_name( $billing_address['country'] );


					$order_id = $member['dotb_mb_id'] . '_' . time();

					$accept_url    = add_query_arg( array(
						'mz_action' => 'payment_feedback',
						'mzsts'     => 'accepted',
						'mzsid'     => $user_session_db_id,
						'msid'      => $membership['id'],
						'msnum'     => $member['dotb_mb_id'],
						'mzfn'      => $member['first_name'],
						'mzln'      => $member['last_name'],
						'mzcat'     => $member['dotb_mb_category'],
					), get_site_url() );
					$decline_url   = add_query_arg( array(
						'mz_action' => 'payment_feedback',
						'mzsts'     => 'declined',
						'mzsid'     => $user_session_db_id,
						'msid'      => $membership['id'],
						'msnum'     => $member['dotb_mb_id'],
						'mzfn'      => $member['first_name'],
						'mzln'      => $member['last_name'],
						'mzcat'     => $member['dotb_mb_category'],
					), get_site_url() );
					$exception_url = add_query_arg( array(
						'mz_action' => 'payment_feedback',
						'mzsts'     => 'failed',
						'mzsid'     => $user_session_db_id,
						'msid'      => $membership['id'],
						'msnum'     => $member['dotb_mb_id'],
						'mzfn'      => $member['first_name'],
						'mzln'      => $member['last_name'],
						'mzcat'     => $member['dotb_mb_category'],
					), get_site_url() );;
					$cancel_url = add_query_arg( array(
						'mz_action' => 'payment_feedback',
						'mzsts'     => 'cancelled',
						'mzsid'     => $user_session_db_id,
						'msid'      => $membership['id'],
						'msnum'     => $member['dotb_mb_id'],
						'mzfn'      => $member['first_name'],
						'mzln'      => $member['last_name'],
						'mzcat'     => $member['dotb_mb_category'],
					), get_site_url() );

					$sha_string .= "ACCEPTURL={$accept_url}{$saw_in_pass_phrase}";
					$sha_string .= "AMOUNT={$billing_amount}{$saw_in_pass_phrase}";
					$sha_string .= "CANCELURL={$cancel_url}{$saw_in_pass_phrase}";
					if ( $member_name_parts ) {
						$sha_string .= "CN={$member_name_parts}{$saw_in_pass_phrase}";
					}
					$sha_string .= "CURRENCY=EUR{$saw_in_pass_phrase}";
					$sha_string .= "DECLINEURL={$decline_url}{$saw_in_pass_phrase}";
					if ( $member['email1'] ) {
						$sha_string .= "EMAIL={$member['email1']}{$saw_in_pass_phrase}";
					}
					$sha_string .= "EXCEPTIONURL={$exception_url}{$saw_in_pass_phrase}";
					$sha_string .= "ORDERID={$order_id}{$saw_in_pass_phrase}";
					if ( $billing_address['street'] ) {
						$sha_string .= "OWNERADDRESS={$billing_address['street']}{$saw_in_pass_phrase}";
					}
					if ( $billing_country ) {
						$sha_string .= "OWNERCTY={$billing_country}{$saw_in_pass_phrase}";
					}
					if ( $member['phone_work'] ) {
						$sha_string .= "OWNERTELNO={$member['phone_work']}{$saw_in_pass_phrase}";
					}
					if ( $billing_address['state'] ) {
						$sha_string .= "OWNERTOWN={$billing_address['state']}{$saw_in_pass_phrase}";
					}
					if ( $billing_address['postalcode'] ) {
						$sha_string .= "OWNERZIP={$billing_address['postalcode']}{$saw_in_pass_phrase}";
					}

					$sha_string .= "PSPID={$pspid}{$saw_in_pass_phrase}";

					$digest = hash( "sha512", $sha_string );
					?>
                    <input type="hidden" name="PSPID" value="<?php echo $pspid; ?>">
                    <input type="hidden" name="ORDERID" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="AMOUNT" value="<?php echo $billing_amount; ?>">
                    <input type="hidden" name="CURRENCY" value="EUR">
                    <input type="hidden" name="CN" id="billing_name" value="<?php echo esc_attr( $member_name_parts ); ?>">
                    <input type="hidden" name="EMAIL" id="billing_email" value="<?php echo $member['email1']; ?>">
                    <input type="hidden" name="OWNERADDRESS" id="billing_street" value="<?php echo $billing_address['street']; ?>">
                    <input type="hidden" name="OWNERTOWN" id="billing_state" value="<?php echo $billing_address['state']; ?>">
                    <input type="hidden" name="OWNERZIP" id="billing_zip" value="<?php echo $billing_address['postalcode']; ?>">
                    <input type="hidden" name="OWNERCTY" id="billing_zip" value="<?php echo $billing_country; ?>">
                    <input type="hidden" name="OWNERTELNO" id="billing_telephone" value="<?php echo $member['phone_work']; ?>">
                    <!-- check before the payment: see Security: Check before the payment -->

                    <input type="hidden" name="SHASIGN" value="<?php echo $digest; ?>">


                    <!-- post payment redirection: see Transaction feedback to the customer -->

                    <input type="hidden" name="ACCEPTURL" value="<?php echo $accept_url; ?>">

                    <input type="hidden" name="DECLINEURL" value="<?php echo $decline_url; ?>">

                    <input type="hidden" name="EXCEPTIONURL" value="<?php echo $exception_url; ?>">

                    <input type="hidden" name="CANCELURL" value="<?php echo $cancel_url; ?>">

                    <div class="mzcheckout-summery">
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Membership Number:</span>
                            <span class="mzcheckout-summery-value"><?php echo $member['dotb_mb_id'] ?></span>
                        </div>
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Order Title:</span>
                            <span class="mzcheckout-summery-value"><?php echo $membership['name']; ?></span>
                        </div>
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Amount:</span>
                            <span class="mzcheckout-summery-value"><?php echo $membership['fee']; ?>€</span>
                        </div>
                        <div class="mzcheckout-summery-row">
                            <span class="mzcheckout-summery-label">Billing address:</span>
                            <span class="mzcheckout-summery-value"><?php echo $billing_address_title; ?></span>
                        </div>
                    </div>
                    <div class="mz-checkout-submit-row">
                        <span class="mz-input-submit-wrap mzms-button"><input type="submit" value="Submit" id="submit2" name="submit2"></span>
                    </div>

                </form>
            </div>
        </div>
	<?php else: ?>
        <div class="easl-mz-nottice">
            <p>you haven’t selected a membership type.</p>
        </div>
	<?php endif; ?>
<?php endif; ?>


