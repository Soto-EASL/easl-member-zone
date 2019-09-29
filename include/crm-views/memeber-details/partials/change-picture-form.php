<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member
 */
?>
<div class="easl-mz-membership-modal-wrap easl-mz-picture-change-wrap">
    <div class="easl-mz-membership-modal-inner easl-mz-picture-change-inner">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="mz_action" value="change_member_picture">
            <input type="hidden" name="mz_member_id" value="<?php echo $member['id']; ?>">
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
	                <div class="mzms-field-label">Profile Image</div>
	                <div class="mzms-field-wrap">
		                <label class="mzms-field-file-wrap">
			                <span class="mzms-field-file-label"></span>
			                <input type="file" name="mz_picture_file" accept="image/png, image/jpeg">
			                <span class="mzms-field-file-button"><span class="ticon ticon-folder-open"></span> Browse</span>
		                </label>
	                </div>
                </div>
            </div>
            <div class="mzms-fields-separator"></div>
            <div class="mzms-fields-row easl-row easl-row-col-2" style="margin-bottom: 0;">
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con mzms-modal-cancel-wrap">
                        <a class="mzms-button mzms-change-picture-cancel mzms-modal-cancel" href="#">Cancel</a>
                    </div>
                </div>
                <div class="easl-col">
                    <div class="easl-col-inner mzms-fields-con mzms-modal-submit-wrap">
                        <button class="mzms-button mzms-change-picture-submit mzms-modal-submit" href="#">Go ahead</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
