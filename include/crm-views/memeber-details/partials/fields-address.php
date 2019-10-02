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
        <label class="mzms-field-label" for="dotb_tmp_account">Organisation / Institution / Company</label>
        <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('dotb_tmp_account', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
            <input type="text" placeholder="" name="dotb_tmp_account" id="mzf_dotb_tmp_account" value="<?php echo esc_attr( $member['dotb_tmp_account'] ); ?>" autocomplete="off">
	        <?php echo easl_mz_field_public_field('dotb_tmp_account', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
        </div>
    </div>
</div>
<div class="mzms-fields-row easl-row easl-row-col-2">
    <div class="easl-col">
        <div class="easl-col-inner">
            <h3>Institution address</h3>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_primary_address_street">Street <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_street', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <textarea name="primary_address_street" id="mzf_primary_address_street" placeholder="" autocomplete="off"><?php echo esc_textarea( $member['primary_address_street'] ); ?></textarea>
	                    <?php echo easl_mz_field_public_field('primary_address_street', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_primary_address_city">City <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_city', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="primary_address_city" id="mzf_primary_address_city" value="<?php echo esc_attr( $member['primary_address_city'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('primary_address_city', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_primary_address_state">State <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_state', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="primary_address_state" id="mzf_primary_address_state" value="<?php echo esc_attr( $member['primary_address_state'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('primary_address_state', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_primary_address_postalcode">Postal code <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_postalcode', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="primary_address_postalcode" id="mzf_primary_address_postalcode" value="<?php echo esc_attr( $member['primary_address_postalcode'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('primary_address_postalcode', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_primary_address_country">Country <span class="mzms-asteric">*</span></label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('primary_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <select class="easl-mz-select2" name="primary_address_country" id="mzf_primary_address_country" data-placeholder="Select an option" style="width: 100%;">
                            <option value=""></option>
							<?php echo easl_mz_get_crm_dropdown_items( 'countries', $member['primary_address_country'] ); ?>
                        </select>
	                    <?php echo easl_mz_field_public_field('primary_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="easl-col">
        <div class="easl-col-inner">
            <h3>Home address</h3>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_alt_address_street">Street</label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('alt_address_street', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <textarea name="alt_address_street" id="mzf_alt_address_street" placeholder="" autocomplete="off"><?php echo esc_textarea( $member['alt_address_street'] ); ?></textarea>
	                    <?php echo easl_mz_field_public_field('alt_address_street', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_alt_address_city">City</label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('alt_address_city', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="alt_address_city" id="mzf_alt_address_city" value="<?php echo esc_attr( $member['alt_address_city'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('alt_address_city', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_alt_address_state">State</label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('alt_address_state', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="alt_address_state" id="mzf_alt_address_state" value="<?php echo esc_attr( $member['alt_address_state'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('alt_address_state', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_alt_address_postalcode">Postal code</label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('alt_address_postalcode', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <input type="text" placeholder="" name="alt_address_postalcode" id="mzf_alt_address_postalcode" value="<?php echo esc_attr( $member['alt_address_postalcode'] ); ?>" autocomplete="off">
	                    <?php echo easl_mz_field_public_field('alt_address_postalcode', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzf_alt_address_country">Country</label>
                    <div class="mzms-field-wrap mzms-field-has-privacy<?php if(!easl_mz_field_is_public('alt_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields'])){echo ' mzms-privacy-enabled';} ?>">
                        <select class="easl-mz-select2" name="alt_address_country" id="mzf_alt_address_country" style="width: 100%;" data-placeholder="Select a country">
                            <option value=""></option>
							<?php echo easl_mz_get_crm_dropdown_items( 'countries', $member['alt_address_country'] ); ?>
                        </select>
	                    <?php echo easl_mz_field_public_field('alt_address_country', $member['dotb_public_profile'], $member['dotb_public_profile_fields']); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
