<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
?>
<?php if ( easl_mz_is_member_logged_in() ): ?>
    <div class="vc_row wpb_row vc_row-fluid">
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-3 vc_col-md-2">
            <div class="vc_column-inner">
                <div class="easl-mz-page-menu">
                    <h1 class="easl-mz-page-menu-title">Member Zone</h1>
					<?php
					wp_nav_menu( array(
						'container'      => 'nav',
						'menu_class'     => '',
						'wp_nav_menu'    => '',
						'echo'           => true,
						'fallback_cb'    => false,
						'theme_location' => 'member-zone-pages-menu',
					) );
					?>
                </div>
            </div>
        </div>
        <div class="wpb_column vc_column_container vc_col-sm-12 vc_col-lg-9 vc_col-md-10">
            <div class="vc_column-inner">
                <div class="wpb_wrapper easl-mz-container-inner">
					<?php the_content(); ?>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>

	<?php

	$login_error_messages = easl_mz_get_manager()->get_message( 'login_error' );
	$member_dashboard_url = get_field( 'member_dashboard_url', 'option' );

	if ( ! empty( $_GET['redirect_url'] ) ) {
		$redirect_url = $_GET['redirect_url'];
	} else {
		$redirect_url = home_url($_SERVER['REQUEST_URI']);
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
        <div class="mz-forgot-pass-row"><a href="https://easl.eu/become-a-member/">Become a member</a></div>
    </div>
<?php endif; ?>