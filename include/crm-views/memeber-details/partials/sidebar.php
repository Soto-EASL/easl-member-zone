<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
?>
<div class="easl-mz-membership-sidebar">
    <div class="easl-mz-membership-sidebar-inner">
        <div class="mzms-sbitem mzms-sbitem-category">
            <strong>Membership Category:</strong>
			<?php
			if ( $member['dotb_mb_category'] ) {
				echo easl_mz_get_membership_category_name( $member['dotb_mb_category'] );
			} else {
				echo 'N/A';
			}
			?>
        </div>
        <div class="mzms-sbitem mzms-sbitem-number">
            <strong>Membership Number:</strong>
			<?php
			if ( $member['dotb_mb_id'] ) {
				echo $member['dotb_mb_id'];
			} else {
				echo 'N/A';
			}
			?>
        </div>
        <div class="mzms-sbitem mzms-sbitem-number">
            <strong>Membership Duration:</strong>
			<?php
			$current_start_date = get_formatted_birthday_crm_to_europe( $member['dotb_mb_current_start_date'] );
			$current_end_date   = get_formatted_birthday_crm_to_europe( $member['dotb_mb_current_end_date'] );
			if ( $current_start_date && $current_end_date ) {
				echo $current_start_date . ' - ' . $current_end_date;
			} else {
				echo 'N/A';
			}
			?>
        </div>
		<?php if ( easl_mz_is_birthday( $member['birthdate'] ) ): ?>
            <div class="mzms-sbitem">
                <div class="mzms-birthday-box">
                    <strong>Happy Birthday</strong>
                    <span>Best wishes from the EASL team.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php
		$membersip_sidear_items = false;
		if ( $membersip_sidear_items ) :
			?>
            <div class="mzms-sbitem">
                <div class="mzms-icon-cta">
                    <div class="mzms-icon-cta-inner">
                        <a class="easl-icon-cta-link" href="#">
                            <div class="easl-icon-cta-icon">
                                <img class="easl-icon-cta-icon-normal" src="https://easl.websitestage.co.uk/wp-content/uploads/2019/08/icon-question-blue.png" alt="">
                                <img class="easl-icon-cta-icon-hover" src="https://easl.websitestage.co.uk/wp-content/uploads/2019/08/icon-question-white.png" alt="">
                            </div>
                            <div class="easl-icon-cta-text">
                                <span class="easl-icon-cta-title">FAQ</span>
                                <span class="easl-icon-cta-subtitle">Get some answers</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="mzms-icon-cta">
                    <div class="easl-icon-cta-inner">
                        <a class="easl-icon-cta-link" href="#">
                            <div class="easl-icon-cta-icon">
                                <img class="easl-icon-cta-icon-normal" src="https://easl.websitestage.co.uk/wp-content/uploads/2019/08/icon-doc2-blue.png" alt="">
                                <img class="easl-icon-cta-icon-hover" src="https://easl.websitestage.co.uk/wp-content/uploads/2019/08/icon-doc2-white.png" alt="">
                            </div>
                            <div class="easl-icon-cta-text">
                                <span class="easl-icon-cta-title">My Documents</span>
                                <span class="easl-icon-cta-subtitle">Download past invoices</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
		<?php endif; ?>


		<?php
		$membership_button_title = '';
		$membership_button_link  = '';
		$confirmation_page       = easl_membership_thanks_page_url();

		if ( $member['dotb_mb_id'] && isset( $member['latest_membership']['billing_status'] ) && 'waiting' == $member['latest_membership']['billing_status'] ) {
			$membership_button_title = 'Complete payment';
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
		} elseif ( in_array( $member['dotb_mb_current_status'], array( 'expired', 'active' ) ) ) {
			$membership_button_title = 'Renew Membership';
			$membership_button_url   = easl_member_new_membership_form_url( true );
		} else {
			$membership_button_title = 'Add Membership';
			$membership_button_url   = easl_member_new_membership_form_url( false );
		}


		if ( $membership_button_title && $membership_button_url ):
			?>
            <div class="mzms-sbitem">
                <a class="mzms-button" href="<?php echo $membership_button_url ?>"><?php echo $membership_button_title; ?></a>
            </div>
		<?php endif; ?>
        <div class="mzms-sbitem mzms-delete-account-request">
            <p>If you would like to delete your account, please contact
                <a href="mailto:membership@easloffice.eu">membership@easloffice.eu</a></p>
        </div>
    </div>
</div>