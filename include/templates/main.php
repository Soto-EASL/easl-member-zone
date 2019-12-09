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
                    add_filter('nav_menu_link_attributes', 'easl_mz_jhep_menu_link');
					wp_nav_menu( array(
						'container'      => 'nav',
						'menu_class'     => '',
						'wp_nav_menu'    => '',
						'echo'           => true,
						'fallback_cb'    => false,
						'theme_location' => 'member-zone-pages-menu',
					) );
					remove_filter('nav_menu_link_attributes', 'easl_mz_jhep_menu_link');
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
		$redirect_url = home_url( $_SERVER['REQUEST_URI'] );
	}

	?>
    <div class="membership-pages-login-wrap easl-mz-login-form-wrapper">
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
        <div class="mz-forgot-pass-fields clr">
            <input class="mz-reset-pass-email" type="text" value="" placeholder="Your email address">
            <button class="easl-generic-button easl-color-lightblue mz-reset-pass-button">Reset Password</button>
        </div>
        <div class="mz-forgot-pass-row clr">
            <a class="mz-become-member-link" href="https://easl.eu/become-a-member/">Become a member</a>
            <a class="mz-forgot-password" href="#">Forgot your password?</a>
        </div>

        <div class="easl-mz-loader">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/easl-loader.gif" alt="loading...">
        </div>
    </div>
<?php endif; ?>