<?php

$users = get_users( array() );
foreach ( $users as $user ) {
	delete_user_meta($user->ID, 'registered_by');
	delete_user_meta($user->ID, 'multireg_user_id');
}

delete_option('oauth_multireg_options');
delete_option('oauth_login_icon_options');
