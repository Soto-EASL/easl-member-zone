<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
/**
 * @var $redirect_url
 */
$login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );

if ( ! $redirect_url ) {
	$redirect_url = home_url( $_SERVER['REQUEST_URI'] );
}
?>
<div class="membership-pages-login-wrap">
    <form action="" method="post" class="clr">
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
            <input type="hidden" name="mz_redirect_url" value="<?php echo esc_url( $redirect_url ); ?>">
            <button class="easl-generic-button easl-color-lightblue">Login</button>
        </div>
    </form>
</div>
