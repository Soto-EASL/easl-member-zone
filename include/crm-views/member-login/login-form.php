<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Shortcode attributes
 * @var $redirect_url
 */

if ( ! $redirect_url ) {
	$redirect_url = get_field( 'member_dashboard_url', 'option' );
}

$login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );
$login_form_class     = 'easl-mz-login-form';
if ( $login_error_messages ) {
	$login_form_class .= ' easl-active';
}
?>
<div class="<?php echo $login_form_class; ?>">
    <form action="" method="post">
		<?php if ( $login_error_messages ): ?>
            <div class="mz-login-row mz-login-errors">
				<?php echo implode( '', $login_error_messages ); ?>
            </div>
		<?php endif; ?>
        <div class="mz-login-row">
            <input type="text" name="mz_member_login" value="" placeholder="Username">
        </div>
        <div class="mz-login-row">
            <input type="password" name="mz_member_password" value="" placeholder="Password">
        </div>
        <div class="mz-login-row">
            <input type="hidden" name="mz_rdirect_url" value="<?php echo esc_url( $redirect_url ); ?>">
            <button class="easl-generic-button easl-color-lightblue">Login</button>
        </div>
    </form>
</div>
