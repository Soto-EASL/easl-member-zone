<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var array $member
 */

$member_dashboard_url     = get_field( 'member_dashboard_url', 'option' );
$member_zone_button_title = get_field( 'member_zone_button_title', 'option' );
$member_profile_url       = get_field( 'member_profile_url', 'option' );
/**
 * @todo Replace with actual picture
 */
$member_image = $member['profile_picture'];
if ( ! $member_image ) {
	$member_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );
}

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
<div class="mz-member-card-inner">
    <div class="mz-member-avatar">
        <a href="<?php echo esc_url( $member_profile_url ); ?>"><img src="<?php echo $member_image; ?>" alt=""></a>
    </div>
    <div class="mz-member-welcome-block">
        <p class="mz-member-welcome-row">Welcome back</p>
        <p class="mz-member-welcome-row">
            <span class="mz-member-name"><?php echo implode( ' ', $member_name_parts ); ?></span>
            <span class="mz-seperator">|</span>
            <a class="mz-logout-link" href="<?php echo easl_member_logout_url(); ?>">Logout</a>
        </p>
        <p class="mz-member-welcome-row mz-member-welcome-button">
            <a class="mz-member-panel-button" href="<?php echo esc_url( $member_dashboard_url ); ?>"><?php echo strip_tags( $member_zone_button_title ); ?></a>
        </p>
    </div>
</div>
