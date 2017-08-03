<?php
include("includes/settings.php");

if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

delete_option($bootup);
delete_option('easymaintenance_settings');