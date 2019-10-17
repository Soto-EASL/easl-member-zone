<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */

$public_fields = explode( ',', $member['dotb_public_profile_fields'] );

$job_excerpt = array();
if ( ( $member['dotb_job_function'] == 'other' ) && $member['dotb_job_function_other'] ) {
	$job_excerpt[] = $member['dotb_job_function_other'];
} elseif ( $member['dotb_job_function'] ) {
	$job_excerpt[] = easl_mz_get_list_item_name( 'job_functions', $member['dotb_job_function'] );
}
if ( $member['department'] ) {
	$job_excerpt[] = $member['department'];
}

$address = array();
if ( in_array( 'primary_address_street', $public_fields ) && $member['primary_address_street'] ) {
	$address[] = str_replace( "\n", wp_unslash( $member['primary_address_street'] ) );
}
if ( in_array( 'primary_address_city', $public_fields ) && $member['primary_address_city'] ) {
	$address[] = $member['primary_address_city'];
}
if ( in_array( 'primary_address_state', $public_fields ) && $member['primary_address_state'] ) {
	$address[] = $member['primary_address_state'];
}
if ( in_array( 'primary_address_postalcode', $public_fields ) && $member['primary_address_postalcode'] ) {
	$address[] = $member['primary_address_postalcode'];
}
if ( in_array( 'primary_address_country', $public_fields ) && $member['primary_address_country'] ) {
	$address[] = easl_mz_get_country_name( $member['primary_address_country'] );
}
$address = implode( "<br/>\n", $address );

$member_name_parts = array();
if ( $member['salutation'] ) {
	$member_name_parts[] = $member['salutation'];
}
if ( $member['first_name'] ) {
	$member_name_parts[] = $member['first_name'];
}
if ( $member['last_name'] ) {
	$member_name_parts[] = $member['last_name'];
}
?>
<div class="easl-easl-mz-mp-details">
    <div class="easl-easl-mz-mp-header">
        <div class="easl-easl-mz-mp-image">
            <img src="<?php echo easl_mz_get_member_image_src( $member['id'], $member['picture'] ); ?>" alt="">
        </div>
        <div class="easl-easl-mz-mp-title">
            <h3><?php echo implode( ' ', $member_name_parts ); ?></h3>
			<?php if ( $member['title'] ): ?>
                <h4><?php echo $member['title']; ?></h4>
			<?php endif; ?>
			<?php if ( $job_excerpt ): ?>
                <div class="easl-easl-mz-mp-excerpt"><?php echo implode( ', ', $job_excerpt ); ?></div>
			<?php endif; ?>
        </div>
    </div>
	<?php
	if ( $member['dotb_easl_specialty'] ):
		$specialities = array();
		foreach ( $member['dotb_easl_specialty'] as $speciality ) {
			if ( $speciality == 'other' ) {
				$specialities[] = $member['dotb_easl_specialty_other'];
			} else {
				$specialities[] = easl_mz_get_list_item_name( 'specialities', $speciality );
			}
		}
		?>
        <div class="easl-easl-mz-mp-speciality">
            <strong>Speciality:</strong> <span><?php echo implode( ', ', $specialities ); ?></span>
        </div>
	<?php endif; ?>
    <div class="easl-easl-mz-mp-intro">
		<?php
		if ( $member['description'] ) {
			echo wpautop( $member['description'] );
		}
		?>
    </div>
</div>
<div class="easl-easl-mz-mp-contacts">
	<?php if ( ( in_array( 'email1', $public_fields ) && $member['email1'] ) || ( in_array( 'phone_mobile', $public_fields ) && $member['phone_mobile'] ) ): ?>
        <div class="easl-easl-mz-mp-email">
            <p class="easl-easl-mz-mp-contact-item">
                <strong>Email:</strong><a href="mailto:<?php echo $member['email1']; ?>"><?php echo $member['email1']; ?></a>
            </p>
        </div>
	<?php endif; ?>
	<?php if ( in_array( 'phone_work', $public_fields ) && $member['phone_work'] ): ?>
        <div class="easl-easl-mz-mp-telmob">
			<?php if ( in_array( 'phone_work', $public_fields ) && $member['phone_work'] ): ?>
                <p class="easl-easl-mz-mp-contact-item">
                    <strong>TEL:</strong><span"><?php echo $member['phone_work']; ?></span>
                </p>
			<?php endif; ?>
			<?php if ( in_array( 'phone_mobile', $public_fields ) && $member['phone_mobile'] ): ?>
                <p class="easl-easl-mz-mp-contact-item">
                    <strong>MOB:</strong><span"><?php echo $member['phone_mobile']; ?></span>
                </p>
			<?php endif; ?>
        </div>
	<?php endif; ?>
	<?php if ( $address ): ?>
        <div lang="easl-easl-mz-mp-address">
            <p class="easl-easl-mz-mp-contact-item">
                <strong>ADDRESS:</strong>
				<?php echo $address; ?>
            </p>
        </div>
	<?php endif; ?>
</div>
