<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_salutation">Title</label>
            <div class="mzms-field-wrap">
                <span class="ec-cs-label"></span>
                <select class="easl-mz-select2" name="salutation" id="mzf_salutation" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'salutations', '' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_first_name">First Name</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="first_name" id="mzf_first_name" value="">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_last_name">Last Name</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="last_name" id="mzf_last_name" value="">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_email1">Email</label>
            <div class="mzms-field-wrap">
                <input type="email" placeholder="" name="email1" id="mzf_email1" value="">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_password">Password</label>
            <div class="mzms-field-wrap">
                <input type="password" placeholder="" name="password" id="mzf_password" value="">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label for="mzf_password2">Confirm password</label>
            <div class="mzms-field-wrap">
                <input type="password" placeholder="" name="password2" id="mzf_password2" value="">
            </div>
        </div>
    </div>
</div>

