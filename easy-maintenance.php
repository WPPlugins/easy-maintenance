<?php
/*
Plugin Name: Easy Maintenance
Description: A small and lightweight plugin build for simple maintenances.
Version: 1.1.5
Author: Mitch
Author URI: https://wordpress.org/support/profile/lowest
*/

$easymaintenance_options = get_option('easymaintenance_settings');

include('includes/admin-page.php');
include('includes/restrict-access.php');