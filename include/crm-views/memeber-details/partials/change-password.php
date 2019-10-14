<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="easl-mz-membership-modal-wrap easl-mz-password-change-wrap">
    <div class="easl-mz-membership-modal-inner easl-mz-password-change-inner">
        <div class="mzms-fields-row">
            <div class="mzms-fields-con">
                <label class="mzms-field-label" for="mzf_old_password">Old password</label>
                <div class="mzms-field-wrap">
                    <input type="password" placeholder="" name="old_password" id="mzf_old_password" value="" autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="mzms-fields-row">
            <div class="mzms-fields-con">
                <label class="mzms-field-label" for="mzf_new_password">New password</label>
                <div class="mzms-field-wrap">
                    <input type="password" placeholder="" name="new_password" id="mzf_new_password" value="" autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="mzms-fields-row">
            <div class="mzms-fields-con">
                <label class="mzms-field-label" for="mzf_new_password2">Confirm new password</label>
                <div class="mzms-field-wrap">
                    <input type="password" placeholder="" name="new_password2" id="mzf_new_password2" value="" autocomplete="new-password">
                </div>
            </div>
        </div>
        <div class="mzms-fields-separator"></div>
        <div class="mzms-fields-row easl-row easl-row-col-2" style="margin-bottom: 0;">
            <div class="easl-col">
                <div class="easl-col-inner mzms-fields-con mzms-modal-cancel-wrap">
                    <a class="mzms-button mzms-change-password-cancel mzms-modal-cancel" href="#">Cancel</a>
                </div>
            </div>
            <div class="easl-col">
                <div class="easl-col-inner mzms-fields-con mzms-modal-submit-wrap">
                    <a class="mzms-button mzms-change-password-submit mzms-modal-submit" href="#">Go ahead</a>
                </div>
            </div>
        </div>
        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading..."></div>
    </div>
</div>
