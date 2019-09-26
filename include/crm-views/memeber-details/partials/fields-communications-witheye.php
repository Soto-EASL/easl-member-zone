<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
?>
<div class="mzms-fields-row">
    <div class="mzms-fields-con">
        <label class="mzms-field-label" for="mzms_email">Email</label>
        <div class="mzms-field-wrap mzms-field-has-privacy">
            <input type="email" placeholder="" name="email1" id="mzms_email" value="<?php echo esc_attr( $member['email1'] ); ?>">
            <input type="hidden" name="mzms_email_privacy" value="1">
            <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzms_telephone">Phone (work)</label>
            <div class="mzms-field-wrap mzms-field-has-privacy mzms-privacy-enabled">
                <input type="text" placeholder="" name="phone_work" id="mzms_telephone" value="<?php echo esc_attr( $member['phone_work'] ); ?>">
                <input type="hidden" name="mzms_telephone_privacy" value="1">
                <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzms_mobile">Mobile</label>
            <div class="mzms-field-wrap mzms-field-has-privacy mzms-privacy-enabled">
                <input type="text" placeholder="" name="phone_mobile" id="mzms_mobile" value="<?php echo esc_attr( $member['phone_mobile'] ); ?>">
                <input type="hidden" name="mzms_mobile_privacy" value="1">
                <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="phone_home">Phone (home)</label>
            <div class="mzms-field-wrap mzms-field-has-privacy mzms-privacy-enabled">
                <input type="text" placeholder="" name="phone_work" id="phone_home" value="<?php echo esc_attr( $member['phone_home'] ); ?>">
                <input type="hidden" name="mzms_telephone_privacy" value="1">
                <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="mzms_mobile">Other Phone</label>
            <div class="mzms-field-wrap mzms-field-has-privacy mzms-privacy-enabled">
                <input type="text" placeholder="" name="phone_mobile" id="mzms_mobile" value="<?php echo esc_attr( $member['phone_other'] ); ?>">
                <input type="hidden" name="mzms_mobile_privacy" value="1">
                <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="phone_fax">Fax number</label>
            <div class="mzms-field-wrap mzms-field-has-privacy mzms-privacy-enabled">
                <input type="text" placeholder="" name="phone_fax" id="phone_fax" value="<?php echo esc_attr( $member['phone_fax'] ); ?>">
                <input type="hidden" name="mzms_telephone_privacy" value="1">
                <span class="mzms-fields-privacy-icon ticon ticon-eye"></span>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <div class="mzms-field-wrap">
                <label class="mzms-field-label" for="do_not_call" class="easl-custom-checkbox">
                    <input type="checkbox" name="do_not_call" id="do_not_call" <?php checked( 1, $member['do_not_call'], true ); ?>>
                    <span>Do not call</span>
                </label>
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="assistant">Assistant</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="assistant" id="assistant" value="<?php echo esc_attr( $member['assistant'] ); ?>">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="dotb_assistant_email">Assistant email</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="dotb_assistant_email" id="dotb_assistant_email" value="<?php echo esc_attr( $member['dotb_assistant_email'] ); ?>">
            </div>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="assistant_phone">Assistant telephone</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="assistant_phone" id="assistant_phone" value="<?php echo esc_attr( $member['assistant_phone'] ); ?>">
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner mzms-fields-con">
            <label class="mzms-field-label" for="twitter">Twitter account</label>
            <div class="mzms-field-wrap">
                <input type="text" placeholder="" name="twitter" id="twitter" value="<?php echo esc_attr( $member['twitter'] ); ?>">
            </div>
        </div>
    </div>
</div>