<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $member array
 */
$title         = 'My membership';
$template_base = easl_mz_get_manager()->path( 'CRM_VIEWS', '/memeber-details' );
?>

    <div class="easl-mz-membership-fields">
        <form id="easl-mz-membership-form" action="" method="post">
            <input type="hidden" name="id" id="mzf_id" value="<?php echo $member['id']; ?>">
            <div class="mzms-fields-row easl-mz-membership-header">
				<?php if ( $title ): ?>
                    <h2 class="mz-page-heading"><?php echo $title; ?></h2>
				<?php endif; ?>
                <div class="mzms-field-wrap mzms-field-wrap-public">
                    <label for="mzms_dotb_public_profile" class="easl-custom-checkbox">
                        <input type="checkbox" name="dotb_public_profile" id="mzms_dotb_public_profile" value="Yes" <?php checked( 'Yes', $member['dotb_public_profile'], true ); ?>>
                        <span>Make my profile public</span>
                    </label>
                </div>
            </div>
            <div class="mzms-fields-separator"></div>
			<?php include $template_base . '/partials/fields-basic.php'; ?>
			<?php include $template_base . '/partials/fields-global.php'; ?>

            <div class="mzms-fields-separator"></div>
			<?php include $template_base . '/partials/fields-communications.php'; ?>

            <div class="mzms-fields-separator"></div>
			<?php include $template_base . '/partials/fields-address.php'; ?>
            <div class="mzms-fields-separator"></div>
            <div class="mzms-fields-row">
                <div class="mzms-fields-con">
                    <label class="mzms-field-label" for="mzms_personal_profile">Personal Profile</label>
                    <div class="mzms-field-wrap">
                        <textarea name="description" id="mzms_personal_profile" placeholder=""><?php echo esc_textarea( $member['description'] ); ?></textarea>
                    </div>
                </div>
            </div>
            <div class="mzms-fields-separator"></div>
            <div class="mzms-fields-row mzms-submit-row">
                <button class="mzms-submit">Save Updates</button>
            </div>
        </form>

		<?php include $template_base . '/partials/change-picture-form.php'; ?>
		<?php include $template_base . '/partials/change-password.php'; ?>
		<?php include $template_base . '/partials/membership-category-form.php'; ?>
    </div>

<?php include $template_base . '/partials/sidebar.php'; ?>