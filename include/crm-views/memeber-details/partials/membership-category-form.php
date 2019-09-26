<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
?>
<div class="easl-mz-membership-modal-wrap easl-mz-membership-category-form-wrap">
    <div class="easl-mz-membership-modal-inner easl-mz-membership-category-form-inner">
        <div class="mzms-fields-row">
            <div class="mzms-fields-con">
                <label class="mzms-field-label" for="mzf_membership_category">Membership Category</label>
                <div class="mzms-field-wrap">
                    <select class="easl-mz-select2" name="membership_category" id="mzf_membership_category" data-placeholder="Select an category" style="width: 100%;">
                        <option value=""></option>
						<?php echo easl_mz_get_crm_dropdown_items( 'membership_categories', $member['dotb_mb_category'] ); ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="mzms-fields-row easl-row easl-row-col-2">
            <div class="easl-col">
                <div class="easl-col-inner mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_membership_category">Membership Years</label>
                    <div class="mzms-field-wrap">
                        <select class="easl-mz-select2" name="membership_years" id="mzf_membership_years" data-placeholder="Select number of years" style="width: 100%;">
                            <option value=""></option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
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
                        <select class="easl-mz-select2" name="membership_payment_type" id="mzf_membership_payment_type" data-placeholder="Select payment type" style="width: 75%;">
                            <option value=""></option>
                            <option value="online">Online</option>
                            <option value="offline">Offline</option>
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
        <div class="mzms-fields-row easl-row easl-row-col-2" style="margin-bottom: 0;">
            <div class="easl-col">
                <div class="easl-col-inner mzms-fields-con mzms-modal-submit-wrap">
                    <a class="mzms-button mzms-add-membership-submit mzms-modal-submit" href="#">Go ahead</a>
                </div>
            </div>
            <div class="easl-col">
                <div class="easl-col-inner mzms-fields-con mzms-modal-cancel-wrap">
                    <a class="mzms-button mzms-add-membership-cancel mzms-modal-cancel" href="#">Cancel</a>
                </div>
            </div>
        </div>
        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>
</div>
