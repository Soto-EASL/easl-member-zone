<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member
 * @var $renew
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

?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="mz_action" value="create_membership">
    <input type="hidden" name="mz_member_id" value="<?php echo $member['id']; ?>">
    <input type="hidden" name="mz_renew" value="<?php echo $renew; ?>">
    <input type="hidden" name="mz_current_cat" value="<?php echo $member['dotb_mb_category']; ?>">
    <input type="hidden" name="mz_current_end_date" value="<?php echo $member['dotb_mb_current_end_date']; ?>">
    <input type="hidden" name="mz_member_name" value="<?php echo implode( ' ', $member_name_parts ); ?>">

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
                            <option value="<?php echo $cat_key ?>"<?php selected( $cat_key, $member['dotb_mb_category'], true ); ?>><?php echo $cat_name; ?></option>
						<?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="easl-col">
            <div class="easl-col-inner mzms-fields-con">
                <label class="mzms-field-label" for="mzf_membership_payment_type">Fee</label>
                <div class="mzms-field-wrap">
                    <span id="easl-mz-membership-fee"><?php echo easl_mz_get_membership_fee( $member['dotb_mb_category'], true ); ?></span>
                </div>
            </div>
        </div>
    </div>
    <div class="mzms-fields-row easl-row easl-row-col-2">
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
        <div class="easl-col">
            <div class="easl-col-inner mzms-fields-con">

            </div>
        </div>
    </div>
    <div class="mzms-fields-row mzms-support-docs-row">
        <div class="mzms-fields-con">
            <div class="mzms-fiel-label">Supporting documents</div>
            <div class="mzms-field-wrap">
                <label>
                    <span class="mzms-field-file-label"></span>
                    <input type="file" name="supporting_docs[]" id="mzf_supporting_docs">
                    <span class="mzms-field-file-button">Browse</span>
                </label>
                <a href="#" class="mzms-field-file-add"><span class="ticon ticon-plus-square"></span></a>
            </div>
        </div>
        <div class="mzms-fields-con">
            <div class="mzms-field-wrap">
                <label for="mzf_agree_docs_terms" class="easl-custom-checkbox">
                    <input type="checkbox" name="agree_docs_terms" id="mzf_agree_docs_terms" value="1">
                    <span>I agree to terms and conditionsl</span>
                </label>
            </div>
        </div>
    </div>
    <div class="mzms-fields-separator"></div>
    <div style="text-align: right;">
        <button class="mzms-button mzms-add-membership-submit mzms-modal-submit" href="#">Go ahead</button>
    </div>
</form>