<?php

// If plugin is not being uninstalled, exit (do nothing)
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('pipdig_bloglovin_btn_url');
/*
for ($day = 1; $day <= 31; $day++) {
	delete_option('pipdig_bloglovin_follower_count'.$day);
}
*/