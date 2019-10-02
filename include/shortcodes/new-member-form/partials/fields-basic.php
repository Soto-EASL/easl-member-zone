<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_salutation">Title <span class="mzms-asteric">*</span></label>
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
            <label class="mzms-field-label" for="mzf_first_name">First Name <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="first_name" id="mzf_first_name" value="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_last_name">Last Name <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="last_name" id="mzf_last_name" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-3">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_email1">Email <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="email" placeholder="" name="email1" id="mzf_email1" value="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_password">Password <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="password" placeholder="" name="password" id="mzf_password" value="" autocomplete="off">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_password2">Confirm password <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="password" placeholder="" name="password2" id="mzf_password2" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>

