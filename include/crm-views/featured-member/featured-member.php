<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var array $members
 */

if ( count( $members ) > 0 ):
	$carousel_data_options = '';
	$carousel_options = array(
		'arrows'                 => true,
		'dots'                   => true,
		'auto_play'              => true,
		'infinite_loop'          => true,
		'center'                 => 'false',
		'animation_speed'        => 150,
		'items'                  => 1,
		'items_scroll'           => 1,
		'timeout_duration'       => 5000,
		'items_margin'           => 0,
		'tablet_items'           => 1,
		'mobile_landscape_items' => 1,
		'mobile_portrait_items'  => 1
	);
	$carousel_data_options = 'data-wpex-carousel="' . vcex_get_carousel_settings( $carousel_options, 'easl_mz_member_featured' ) . '"';
	$default_image = easl_mz_get_asset_url( 'images/default-avatar.jpg' );
	?>
    <div class="wpex-carousel mz-featured-member-carousel owl-carousel clr"<?php echo $carousel_data_options; ?>>
		<?php foreach ( $members as $member ): ?>
			<?php
			$member_image = $member['profile_picture'];
			if ( ! $member_image ) {
				$member_image = $default_image;
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
            <div class="wpex-carousel-slide mz-featured-member-carousel-item clr">
                <div class="mz-featured-member-image">
                    <img src="<?php echo $member_image; ?>" alt="">
                </div>
                <div class="mz-featured-member-details">
                    <h5><?php echo implode( ' ', $member_name_parts ); ?></h5>
                    <p><?php echo $member['description']; ?></p>
                    <a class="mz-member-details-trigger" href="#<?php echo $member['id']; ?>" data-id="<?php echo $member['id']; ?>">View profile</a>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
<?php endif; ?>