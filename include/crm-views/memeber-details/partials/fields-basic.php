<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */

$member_image = $member['profile_picture'];
$has_picture = true;
$change_picture_title = 'Change Picture';
if(!$member_image) {
	$has_picture = false;
	$change_picture_title = 'Add Picture';
	$member_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );
}
?>
<div class="mzms-fields-row easl-mz-membership-top">
    <div class="mzms-image-wrap">
        <img src="<?php echo $member_image; ?>" alt="">
    </div>
    <div class="mzms-image-button-wrap">
        <a class="mzms-button mzms-change-image" href="#"><?php echo $change_picture_title; ?></a>
    </div>
    <div class="mzms-passwor-button-wrap">
        <a class="mzms-button mzms-change-password" href="#">Change Password</a>
    </div>
    <div class="mzms-form-button-wrap">
        <button class="mzms-button mzms-save-membership-form">Save Changes</button>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_salutation">Title <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <span class="ec-cs-label"></span>
                <select class="easl-mz-select2" name="salutation" id="mzf_salutation" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'salutations', $member['salutation'] ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_first_name">First Name <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="first_name" id="mzf_first_name" value="<?php echo esc_attr( $member['first_name'] ); ?>" autocomplete="new-password">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_last_name">Last Name <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="last_name" id="mzf_last_name" value="<?php echo esc_attr( $member['last_name'] ); ?>" autocomplete="new-password">
            </div>
        </div>
    </div>
</div>
