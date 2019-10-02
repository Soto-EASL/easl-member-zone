<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>

<div class="mzms-fields-row">
    <div class="mzms-fields-con">
        <label class="mzms-field-label" for="dotb_tmp_account">Organisation / Institution / Company</label>
        <div class="mzms-field-wrap">
            <input type="text" placeholder="" name="dotb_tmp_account" id="mzf_dotb_tmp_account" value="" autocomplete="off">
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
					<div class="mzms-field-wrap">
						<textarea name="primary_address_street" id="mzf_primary_address_street" placeholder="" autocomplete="off"></textarea>
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_primary_address_city">City <span class="mzms-asteric">*</span></label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="primary_address_city" id="mzf_primary_address_city" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_primary_address_state">State <span class="mzms-asteric">*</span></label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="primary_address_state" id="mzf_primary_address_state" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_primary_address_postalcode">Postal code <span class="mzms-asteric">*</span></label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="primary_address_postalcode" id="mzf_primary_address_postalcode" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_primary_address_country">Country <span class="mzms-asteric">*</span></label>
					<div class="mzms-field-wrap">
						<select class="easl-mz-select2" name="primary_address_country" id="mzf_primary_address_country" data-placeholder="Select an option" style="width: 100%;">
                            <option value=""></option>
							<?php echo easl_mz_get_crm_dropdown_items( 'countries', '' ); ?>
						</select>
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
					<div class="mzms-field-wrap">
						<textarea name="alt_address_street" id="mzf_alt_address_street" placeholder="" autocomplete="off"></textarea>
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_alt_address_city">City</label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="alt_address_city" id="mzf_alt_address_city" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_alt_address_state">State</label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="alt_address_state" id="mzf_alt_address_state" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_alt_address_postalcode">Postal code</label>
					<div class="mzms-field-wrap">
						<input type="text" placeholder="" name="alt_address_postalcode" id="mzf_alt_address_postalcode" value="" autocomplete="off">
					</div>
				</div>
			</div>
			<div class="mzms-fields-row">
				<div class="mzms-fields-con">
					<label class="mzms-field-label" for="mzf_alt_address_country">Country</label>
					<div class="mzms-field-wrap">
						<select class="easl-mz-select2" name="alt_address_country" id="mzf_alt_address_country" data-placeholder="Select an option" style="width: 100%;">
                            <option value=""></option>
							<?php echo easl_mz_get_crm_dropdown_items( 'countries' ); ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
