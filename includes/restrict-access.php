<?php
function easymaintenance_restrict_access() {
	global $easymaintenance_options;

	if( isset( $easymaintenance_options['enable'] ) ) {
		$cd = time();
		$ud = strtotime($easymaintenance_options['disable']);

		if( ! current_user_can( 'manage_options' ) ) {
			if(!empty($easymaintenance_options['disable'])) {
				if($cd < $ud) {
					if( ! is_page( intval( $easymaintenance_options['page'] ) ) ) {
						wp_redirect( get_permalink( $easymaintenance_options['page'] ) ); exit;
					}
				}
			} else {
				if( ! is_page( intval( $easymaintenance_options['page'] ) ) ) {
					wp_redirect( get_permalink( $easymaintenance_options['page'] ) ); exit;
				}
			}
		}
	}
}
add_action( 'template_redirect', 'easymaintenance_restrict_access', 0 );