<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @param $response
 *
 * @return array
 */
function easl_mz_parse_crm_contact_data( $response ) {
	$data = array(
		'id'         => $response->id,
		'salutation' => $response->salutation,
		'first_name' => $response->first_name,
		'last_name'  => $response->last_name,
		'picture'    => $response->picture,

		'dotb_public_profile'           => $response->dotb_public_profile,
		'dotb_job_function'             => $response->dotb_job_function,
		'dotb_job_function_other'       => $response->dotb_job_function_other,
		'dotb_area_of_interest'         => $response->dotb_area_of_interest,
		'title'                         => $response->title,
		'dotb_easl_specialty'           => $response->dotb_easl_specialty,
		'dotb_easl_specialty_other'     => $response->dotb_easl_specialty_other,
		'department'                    => $response->department,
		'dotb_interaction_with_patient' => $response->dotb_interaction_with_patient,
		'dotb_gender'                   => $response->dotb_gender,
		'birthdate'                     => $response->birthdate,
		'email1'                        => $response->email1,

		'dotb_tmp_account' => $response->dotb_tmp_account,

		'primary_address_street'      => $response->primary_address_street,
		'primary_address_city'        => $response->primary_address_city,
		'primary_address_state'       => $response->primary_address_state,
		'primary_address_postalcode'  => $response->primary_address_postalcode,
		'primary_address_country'     => $response->primary_address_country,
		'dotb_primary_address_georeg' => $response->dotb_primary_address_georeg,

		'alt_address_street'      => $response->alt_address_street,
		'alt_address_city'        => $response->alt_address_city,
		'alt_address_state'       => $response->alt_address_state,
		'alt_address_postalcode'  => $response->alt_address_postalcode,
		'alt_address_country'     => $response->alt_address_country,
		'dotb_alt_address_georeg' => $response->dotb_alt_address_georeg,

		'phone_work'           => $response->phone_work,
		'phone_mobile'         => $response->phone_mobile,
		'phone_home'           => $response->phone_home,
		'phone_other'          => $response->phone_other,
		'phone_fax'            => $response->phone_fax,
		'do_not_call'          => $response->do_not_call,
		'assistant'            => $response->assistant,
		'dotb_assistant_email' => $response->dotb_assistant_email,
		'assistant_phone'      => $response->assistant_phone,

		'dotb_mb_id'                  => $response->dotb_mb_id,
		'dotb_mb_current_status'      => $response->dotb_mb_current_status,
		'dotb_mb_category'            => $response->dotb_mb_category,
		'dotb_mb_current_start_date'  => $response->dotb_mb_current_start_date,
		'dotb_mb_current_end_date'    => $response->dotb_mb_current_end_date,
		'dotb_mb_last_mz_login_date'  => $response->dotb_mb_last_mz_login_date,
		'dotb_mb_last_payment_date'   => $response->dotb_mb_last_payment_date,
		'dotb_mb_last_mz_update_date' => $response->dotb_mb_last_mz_update_date,

		'dotb_lead_source_other' => $response->dotb_lead_source_other,

		'facebook'    => $response->facebook,
		'twitter'     => $response->twitter,
		'googleplus'  => $response->googleplus,
		'description' => $response->description,
	);

	if ( $data['picture'] ) {
		$data['profile_picture'] = add_query_arg( 'mz_get_picture', $data['id'], get_site_url() );
	} else {
		$data['profile_picture'] = '';
	}

	return $data;
}

function easl_mz_parse_crm_membership_data( $response ) {
	$data = array(
		'id'             => $response->id,
		'name'           => $response->name,
		'description'    => $response->description,
		'billing_amount' => $response->billing_amount,
		'fee'            => $response->fee,
	);

	return $data;
}


function easl_mz_validate_member_data( $data = array() ) {
	$errors = array();
	if ( empty( $data['salutation'] ) ) {
		$errors['salutation'] = 'Mandatory field';
	}
	if ( empty( $data['first_name'] ) ) {
		$errors['first_name'] = 'Mandatory field';
	}
	if ( empty( $data['last_name'] ) ) {
		$errors['last_name'] = 'Mandatory field';
	}
	if ( empty( $data['dotb_job_function'] ) ) {
		$errors['dotb_job_function'] = 'Mandatory field';
	}
	if ( ! empty( $data['dotb_job_function'] ) && ( $data['dotb_job_function'] == 'other' ) && empty( $data['dotb_job_function_other'] ) ) {
		$errors['dotb_job_function_other'] = 'Mandatory field';
	}
	if ( empty( $data['dotb_area_of_interest'] ) ) {
		$errors['dotb_area_of_interest'] = 'Mandatory field';
	}
	if ( isset( $data['title'] ) && empty( $data['title'] ) ) {
		$errors['title'] = 'Mandatory field';
	}
	if ( empty( $data['dotb_easl_specialty'] ) ) {
		$errors['dotb_easl_specialty'] = 'Mandatory field';
	}
	if ( ! empty( $data['dotb_easl_specialty'] ) && in_array( 'other', $data['dotb_easl_specialty'] ) && empty( $data['dotb_easl_specialty_other'] ) ) {
		$errors['dotb_easl_specialty_other'] = 'Mandatory field';
	}
	if ( empty( $data['dotb_gender'] ) ) {
		$errors['dotb_gender'] = 'Mandatory field';
	}
	if ( ! empty( $data['birthdate'] ) && ! preg_match( "/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data['birthdate'] ) ) {
		$errors['birthdate'] = 'Enter date in yyyy-mm-dd format.';
	}
	if ( empty( $data['email1'] ) ) {
		$errors['email1'] = 'Mandatory field';
	}
	if ( empty( $data['primary_address_street'] ) ) {
		$errors['primary_address_street'] = 'Mandatory field';
	}
	if ( empty( $data['primary_address_postalcode'] ) ) {
		$errors['primary_address_postalcode'] = 'Mandatory field';
	}
	if ( empty( $data['primary_address_city'] ) ) {
		$errors['primary_address_city'] = 'Mandatory field';
	}
	if ( empty( $data['primary_address_state'] ) ) {
		$errors['primary_address_state'] = 'Mandatory field';
	}
	if ( empty( $data['primary_address_country'] ) ) {
		$errors['primary_address_country'] = 'Mandatory field';
	}

	return $errors;
}

function easl_mz_get_crm_dropdown_items( $dropdown_name, $current = '' ) {
	$dropdown_func_name = 'easl_mz_get_list_' . $dropdown_name;
	if ( ! is_callable( $dropdown_func_name ) ) {
		return '';
	}
	$list = call_user_func( $dropdown_func_name );
	if ( ! $list && ! is_array( $list ) ) {
		return '';
	}

	if ( ! $current ) {
		$current = array();
	}

	if ( ! is_array( $current ) ) {
		$current = array( $current );
	}

	$html = '';
	foreach ( $list as $key => $value ) {
		$html .= '<option value="' . $key . '" ' . selected( true, in_array( $key, $current ), false ) . '>' . $value . '</option>';
	}

	return $html;
}

function easl_mz_get_membership_category_name( $category_key ) {
	$categories = easl_mz_get_list_membership_categories();

	return isset( $categories[ $category_key ] ) ? $categories[ $category_key ] : '';
}

function easl_mz_get_membership_category_fees_calculation() {
	// Synchronise these keys with the @function easl_mz_get_list_membership_categories()
	return array(
		"regular"            => 200,
		"regular_jhep"       => 250,
		"corresponding"      => 200,
		"corresponding_jhep" => 250,
		"trainee"            => 100,
		"trainee_jhep"       => 150,
		"nurse"              => 100,
		"nurse_jhep"         => 150,
		"patient"            => 100,
		"patient_jhep"       => 150,
		"emeritus"           => 25,
		"emeritus_jhep"      => 75,
		"allied_pro"         => 100,
		"allied_pro_jhep"    => 150,
	);
}

function easl_mz_get_membership_fee( $membership_category, $add_currency_symbol = false ) {
	$fees = easl_mz_get_membership_category_fees_calculation();

	$fee = isset( $fees[ $membership_category ] ) ? $fees[ $membership_category ] : '';
	if ( $fee && $add_currency_symbol ) {
		$fee += '€';
	}

	return $fee;
}

function easl_mz_get_members_allowed_categories( $member ) {
	$categories = easl_mz_get_list_membership_categories();
	$member_age = (int) easl_mz_calculate_age( $member['birthdate'] );
	$geo_reg    = easl_mz_get_geo_reg( $member['primary_address_country'] );

	if ( $member_age < 35 ) {
		unset( $categories['regular'] );
		unset( $categories['regular_jhep'] );
		unset( $categories['corresponding'] );
		unset( $categories['corresponding_jhep'] );
	}
	if ( $member_age >= 35 ) {
		unset( $categories['trainee'] );
		unset( $categories['trainee_jhep'] );
	}

	if ( $member_age < 65 ) {
		unset( $categories['emeritus'] );
		unset( $categories['emeritus_jhep'] );
	}

	if ( ( $geo_reg != 'europe' ) && ( $member[ 'primary_address_country' != 'ISR' ] ) ) {
		unset( $categories['regular'] );
		unset( $categories['regular_jhep'] );
	}
	if ( $geo_reg == 'europe' ) {
		unset( $categories['corresponding'] );
		unset( $categories['corresponding_jhep'] );
	}

	return $categories;
}

function easl_mz_calculate_age( $dob ) {
	if ( empty( $dob ) ) {
		return false;
	}
	$date     = DateTime::createFromFormat( 'Y-m-d', $dob );
	$now      = new DateTime();
	$interval = $now->diff( $date );

	return $interval->y;
}

function easl_mz_get_geo_reg( $country_code ) {
	$map = array(
		"ALB" => "europe",
		"AND" => "europe",
		"ARM" => "europe",
		"AUT" => "europe",
		"AZE" => "europe",
		"BLR" => "europe",
		"BEL" => "europe",
		"BIH" => "europe",
		"BGR" => "europe",
		"HRV" => "europe",
		"CYP" => "europe",
		"CZE" => "europe",
		"DNK" => "europe",
		"EST" => "europe",
		"FIN" => "europe",
		"FRA" => "europe",
		"GEO" => "europe",
		"DEU" => "europe",
		"GRC" => "europe",
		"HUN" => "europe",
		"ISL" => "europe",
		"IRL" => "europe",
		"ISR" => "europe",
		"ITA" => "europe",
		"KAZ" => "europe",
		"KGZ" => "europe",
		"LVA" => "europe",
		"LTU" => "europe",
		"LUX" => "europe",
		"MKD" => "europe",
		"MLT" => "europe",
		"MDA" => "europe",
		"MCO" => "europe",
		"MNE" => "europe",
		"NLD" => "europe",
		"NOR" => "europe",
		"POL" => "europe",
		"PRT" => "europe",
		"ROU" => "europe",
		"RUS" => "europe",
		"SMR" => "europe",
		"SRB" => "europe",
		"SVK" => "europe",
		"SVN" => "europe",
		"ESP" => "europe",
		"SWE" => "europe",
		"CHE" => "europe",
		"TUR" => "europe",
		"TKM" => "europe",
		"UKR" => "europe",
		"GBR" => "europe",
		"UZB" => "europe",
	);
	if ( isset( $map[ $country_code ] ) ) {
		return $map[ $country_code ];
	}

	return 'other';
}

function easl_mz_get_membership_status_name( $current_status ) {

	$membership_statuses = easl_mz_get_list_membership_statuses();

	return isset( $membership_statuses[ $current_status ] ) ? $membership_statuses[ $current_status ] : '';
}

function easl_mz_is_birthday( $birth_date ) {
	if ( ! $birth_date ) {
		return false;
	}
	$today     = date( 'm-d' );
	$birth_day = substr( $birth_date, 5 );
	if ( $today != $birth_day ) {
		return false;
	}

	return true;
}
