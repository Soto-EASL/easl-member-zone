<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $members array
 * @var $current_page int
 * @var $member_per_page int
 * @var $total_found_member int
 */

$paginations = paginate_links( array(
	'total'     => ceil( $total_found_member / $member_per_page ),
	'current'   => $current_page,
	'type'      => 'list',
	'base'      => '#%#%',
	'format'    => '%#%',
	'end_size'  => 3,
	'mid_size'  => 1,
	'prev_text' => '<span class="ticon ticon-angle-left"></span>',
	'next_text' => '<span class="ticon ticon-angle-right"></span>'
) );

$paginations = str_replace( "<ul class='page-numbers'>", "<ul class='easl-mz-pagination-list'>", $paginations );

?>
<?php if ( $paginations ): ?>
    <div class="easl-mz-pagination easl-mz-pagination-top"><?php echo $paginations; ?></div>
<?php endif; ?>
<div class="easl-mz-directory-members-wrap">
    <div class="easl-mz-directory-members easl-row easl-row-col-2">
		<?php foreach ( $members as $member ): ?>
			<?php
			$member_image      = easl_mz_get_member_image_src( $member['id'], $member['picture'] );
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
			$public_fields = explode( ',', $member['dotb_public_profile_fields'] );

			$job_excerpt = array();
			if ( $member['title'] ) {
				$job_excerpt[] = $member['title'];
			}
			$job_function_title = '';
			if ( ( $member['dotb_job_function'] == 'other' ) && $member['dotb_job_function_other'] ) {
				$job_function_title = $member['dotb_job_function_other'];
			} elseif ( $member['dotb_job_function'] ) {
				$job_function_title = easl_mz_get_list_item_name( 'job_functions', $member['dotb_job_function'] );
			}
			if ( ( $job_function_title != $member['title'] ) ) {
				$job_excerpt[] = $job_function_title;
			}
			if ( $member['department'] ) {
				$job_excerpt[] = $member['department'];
			}
			$job_excerpt = implode( ', ', $job_excerpt );
			if ( strlen( $job_excerpt ) > 86 ) {
				$job_excerpt = substr( $job_excerpt, 0, 80 ) . '...';
			}
			?>
            <div class="easl-col">
                <div class="easl-col-inner easl-mz-md-item">
                    <div class="md-item-image">
                        <img src="<?php echo $member_image; ?>" alt="">
                    </div>
                    <div class="md-item-details">
                        <h4 class="md-item-name">
                            <a class="mz-member-details-trigger" href="#<?php echo $member['id']; ?>" data-memberid="<?php echo $member['id']; ?>"><span><?php echo implode( ' ', $member_name_parts ); ?></span></a>
                        </h4>
						<?php if ( $job_excerpt ): ?>
                            <p class="md-item-text"><?php echo $job_excerpt; ?></p>
						<?php endif; ?>
                        <span class="md-item-country"><?php echo easl_mz_get_country_name( $member['country'] ); ?></span>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>
    </div>
</div>
<?php if ( $paginations ): ?>
    <div class="easl-mz-pagination easl-mz-pagination-bottom"><?php echo $paginations; ?></div>
<?php endif; ?>


