<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
?>
<div class="mzms-fields-row easl-mz-membership-top">
    <div class="mzms-image-wrap">
        <img src="https://easl.websitestage.co.uk/wp-content/uploads/2018/09/easl-pierre-emmanuel-rautou-1-254x254.jpg" alt="">
    </div>
    <div class="mzms-image-button-wrap">
        <a class="mzms-button mzms-change-image" href="#">Change Picture</a>
    </div>
    <div class="mzms-passwor-button-wrap">
        <a class="mzms-button mzms-change-password" href="#">Change Password</a>
    </div>
    <div class="mzms-form-button-wrap">
        <button class="mzms-button mzms-change-image">Save Changes</button>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_salutation">Title</label>
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
            <label for="mzf_first_name">First Name</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="first_name" id="mzf_first_name" value="<?php echo esc_attr( $member['first_name'] ); ?>">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_last_name">Last Name</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="last_name" id="mzf_last_name" value="<?php echo esc_attr( $member['last_name'] ); ?>">
            </div>
        </div>
    </div>
</div>
