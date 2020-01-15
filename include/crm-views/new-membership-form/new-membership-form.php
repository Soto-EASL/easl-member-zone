<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member
 * @var $renew
 * @var $messages
 */

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
$member_has_waiting_payemnt = false;
$membership_button_url      = '';
if ( $member['dotb_mb_id'] && isset( $member['latest_membership']['billing_status'] ) && 'waiting' == $member['latest_membership']['billing_status'] ) {
	$member_has_waiting_payemnt = true;
	if ( 'offline_payment' == $member['latest_membership']['billing_type'] ) {
		$membership_button_url = add_query_arg( array(
			'membership_status' => 'created_offline',
			'mbs_id'            => $member['latest_membership']['id'],
			'mbs_num'           => $member['dotb_mb_id'],
			'fname'             => $member['first_name'],
			'lname'             => $member['last_name']
		), $confirmation_page );
	} else {
		$membership_button_url = easl_membership_checkout_url();
	}
}

$member_has_home_address = true;

if ( ! $member['alt_address_street'] || ! $member['alt_address_postalcode'] || ! $member['alt_address_city'] || ! $member['alt_address_state'] || ! $member['alt_address_country'] ) {
	$member_has_home_address = false;
}


if ( ! $member_has_waiting_payemnt ):
	?>
    <div class="easl-mz-new-membership-form-inner">
        <form action="" method="post" enctype="multipart/form-data">

			<?php if ( isset( $messages['membership_error'] ) && count( $messages['membership_error'] ) > 0 ): ?>
                <div class="easl-mz-error"><?php echo implode( '<br/>', $messages['membership_error'] ); ?></div>
			<?php endif; ?>
            <input type="hidden" name="mz_action" value="create_membership">
            <input type="hidden" name="mz_member_id" value="<?php echo $member['id']; ?>">
            <input type="hidden" name="mz_member_email" value="<?php echo $member['email1']; ?>">
            <input type="hidden" name="mz_renew" value="<?php echo $renew; ?>">
            <input type="hidden" name="mz_current_cat" value="<?php echo $member['dotb_mb_category']; ?>">
            <input type="hidden" name="mz_current_end_date" value="<?php echo $member['dotb_mb_current_end_date']; ?>">
            <input type="hidden" name="mz_member_name" value="<?php echo implode( ' ', $member_name_parts ); ?>">
            <input type="hidden" name="mz_member_fname" value="<?php echo $member['first_name']; ?>">
            <input type="hidden" name="mz_member_lname" value="<?php echo $member['last_name']; ?>">

            <div class="mzms-fields-row easl-row easl-row-col-2">
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_membership_category">Membership Category</label>
                        <div class="mzms-field-wrap">
							<?php

							$allowed_cats = easl_mz_get_members_allowed_categories( $member );
							?>
                            <select class="easl-mz-select2" name="membership_category" id="mzf_membership_category" data-placeholder="Select an category" style="width: 100%;">
                                <option value=""></option>
								<?php
								foreach ( $allowed_cats as $cat_key => $cat_name ):
									?>
                                    <option value="<?php echo $cat_key ?>"<?php selected( $cat_key, $member['dotb_mb_category'], true ); ?>><?php echo $cat_name; ?>
                                        ( <?php echo easl_mz_get_membership_fee( $cat_key, true ); ?> )
                                    </option>
								<?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_membership_payment_type">Payment Type</label>
                        <div class="mzms-field-wrap">
                            <select class="easl-mz-select2" name="membership_payment_type" id="mzf_membership_payment_type" style="width: 100%;">
                                <option value="ingenico_epayments" selected="selected">Online</option>
                                <option value="offline_payment">Offline</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row easl-row easl-row-col-2">
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_membership_payment_type">Billing Address</label>
                        <div class="mzms-field-wrap">
                            <select class="easl-mz-select2" name="billing_mode" id="mzf_billing_mode" style="width: 100%;">
                                <option value="c1" selected="selected">Institution</option>
								<?php if ( $member_has_home_address ): ?>
                                    <option value="c2">Home</option><?php endif; ?>
                                <option value="other">or other?</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con">
                        <div id="mzf_jhephardcopy_recipient_wrapper">
                            <label class="mzms-field-label" for="mzf_jhephardcopy_recipient">JHEP - Where?</label>
                            <div class="mzms-field-wrap">
                                <select class="easl-mz-select2" name="jhephardcopy_recipient" id="mzf_jhephardcopy_recipient" style="width: 100%;">
                                    <option value="c1" selected="selected">Institution</option>
									<?php if ( $member_has_home_address ): ?>
                                        <option value="c2">Home</option><?php endif; ?>
                                    <option value="other">or other?</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="mz-membership-other-address-wrap">
                <div class="mzms-fields-separator"></div>
                <h3>Billing - Other address</h3>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_billing_address_street">Street</label>
                        <div class="mzms-field-wrap">
                            <textarea name="billing_address_street" id="mzf_billing_address_street" placeholder="" autocomplete="off"><?php echo esc_textarea( $member['billing_address_street'] ); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row easl-row easl-row-col-2">
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_billing_address_city">City</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="billing_address_city" id="mzf_billing_address_city" value="<?php echo esc_attr( $member['billing_address_city'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_billing_address_state">State</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="billing_address_state" id="mzf_billing_address_state" value="<?php echo esc_attr( $member['billing_address_state'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row easl-row easl-row-col-2">
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_billing_address_postalcode">Postal code</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="billing_address_postalcode" id="mzf_billing_address_postalcode" value="<?php echo esc_attr( $member['billing_address_postalcode'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_billing_address_country">Country</label>
                            <div class="mzms-field-wrap">
                                <select class="easl-mz-select2" name="billing_address_country" id="mzf_billing_address_country" style="width: 100%;" data-placeholder="Select country">
                                    <option value=""></option>
									<?php echo easl_mz_get_crm_dropdown_items( 'countries', $member['billing_address_country'] ); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mz-membership-jhe-pother-address-wrap">
                <div class="mzms-fields-separator"></div>
                <h3>JHEP - Other address</h3>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <label class="mzms-field-label" for="mzf_jhephardcopyotheraddress_street">Street</label>
                        <div class="mzms-field-wrap">
                            <textarea name="jhephardcopyotheraddress_street" id="mzf_jhephardcopyotheraddress_street" placeholder="" autocomplete="off"><?php echo esc_textarea( $member['jhephardcopyotheraddress_street'] ); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row easl-row easl-row-col-2">
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_jhephardcopyotheraddress_city">City</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="jhephardcopyotheraddress_city" id="mzf_jhephardcopyotheraddress_city" value="<?php echo esc_attr( $member['jhephardcopyotheraddress_city'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_jhephardcopyotheraddress_state">State</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="jhephardcopyotheraddress_state" id="mzf_jhephardcopyotheraddress_state" value="<?php echo esc_attr( $member['jhephardcopyotheraddress_state'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mzms-fields-row easl-row easl-row-col-2">
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_jhephardcopyotheraddress_postalcode">Postal
                                code</label>
                            <div class="mzms-field-wrap">
                                <input type="text" placeholder="" name="jhephardcopyotheraddress_postalcode" id="mzf_jhephardcopyotheraddress_postalcode" value="<?php echo esc_attr( $member['jhephardcopyotheraddress_postalcode'] ); ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="easl-col">
                        <div class="easl-col-inner mzms-fields-con">
                            <label class="mzms-field-label" for="mzf_jhephardcopyotheraddress_country">Country</label>
                            <div class="mzms-field-wrap">
                                <select class="easl-mz-select2" name="jhephardcopyotheraddress_country" id="mzf_jhephardcopyotheraddress_country" style="width: 100%;" data-placeholder="Select country">
                                    <option value=""></option>
									<?php echo easl_mz_get_crm_dropdown_items( 'countries', $member['jhephardcopyotheraddress_country'] ); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="mzms-support-docs-wrap">
                <div class="mzms-fields-separator"></div>
                <div class="mzms-fields-row">
                    <div class="mzms-fields-con">
                        <div class="mzms-field-label">Supporting documents</div>
                        <div class="mzms-field-wrap">
                            <label class="mzms-field-file-wrap">
                                <span class="mzms-field-file-label"></span>
                                <input type="file" name="supporting_docs" id="mzf_supporting_docs" accept="image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                <span class="mzms-field-file-button"><span class="ticon ticon-folder-open"></span> Browse</span>
                            </label>
                        </div>
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
                <div class="mzms-fields-con">
                    <p>Please note that your membership will become active for 12 months on receipt of payment.</p>
                </div>
                <div class="mzms-fields-separator"></div>
                <div style="text-align: right;">
                    <button class="mzms-button mzms-add-membership-submit mzms-modal-submit" href="#">Go ahead</button>
                </div>
            </div>
        </form>
        <div class="easl-mznm-loader">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>
<?php else: ?>
    <div class="mz-membership-form-has-waiting-payment">
        You already have an unpaid membership request. Please
        <a href="<?php echo $membership_button_url; ?>">complete you payment</a>.
    </div>
<?php endif; ?>