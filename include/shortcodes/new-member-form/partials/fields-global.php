<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_job_function">Job function <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_job_function" id="mzf_dotb_job_function" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'job_functions' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-dotb_job_function_other">
            <label class="mzms-field-label" for="mzf_dotb_job_function_other">Job function - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_job_function_other" id="mzf_mzms_first_name" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_area_of_interest">Area of interest <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_area_of_interest[]" id="mzf_dotb_area_of_interest" multiple="multiple" data-placeholder="Select one/more options"  style="width: 100%;">
					<?php echo easl_mz_get_crm_dropdown_items( 'area_of_interests' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_title">Profession / Job Title <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="title" id="mzf_title" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_easl_specialty">Specialty <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_easl_specialty[]" id="mzf_dotb_easl_specialty" multiple="multiple" data-placeholder="Select one/more options" style="width: 100%;">
					<?php echo easl_mz_get_crm_dropdown_items( 'specialities' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con" id="mzms-fields-con-dotb_easl_specialty_other">
            <label class="mzms-field-label" for="mzf_dotb_easl_specialty_other">Specialty - Other <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_easl_specialty_other" id="mzf_dotb_easl_specialty_other" value="" autocomplete="off">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_department">Department</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="department" id="mzf_department" value="">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_interaction_with_patient">Interaction with patients</label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_interaction_with_patient" id="mzf_dotb_interaction_with_patient" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
                    <?php echo easl_mz_get_crm_dropdown_items( 'interactions_patient' ); ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_dotb_gender">Gender <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <select class="easl-mz-select2" name="dotb_gender" id="mzf_dotb_gender" data-placeholder="Select an option" style="width: 100%;">
                    <option value=""></option>
					<?php echo easl_mz_get_crm_dropdown_items( 'genders' ); ?>
                </select>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzf_birthdate">Date of birth <span class="mzms-asteric">*</span></label>
            <div class="mzms-field-wrap">
                <input type="hidden" placeholder="" name="birthdate" id="mzf_birthdate" value="" class="easl-mz-date" autocomplete="off">
                <input type="text" placeholder="" name="" id="mzf_birthdate_fz" value="<?php echo esc_attr( $date_of_birth_formatted ); ?>" class="easl-mz-date" autocomplete="off">
            </div>
        </div>
    </div>
</div>
