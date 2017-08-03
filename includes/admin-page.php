<?php
include("settings.php");

function easymaintenance_bootup() {
	global $bootup;
	
	if (!get_option($bootup)) {
		update_option($bootup, 'true');
		header("Location: options-general.php?page=maintenance&intro=true");
	}
}
add_action( 'admin_init', 'easymaintenance_bootup' );

function easymaintenance_settings_page() {
	global $version;
	global $changes;
	global $easymaintenance_options;

	$selected_page = ! empty( $easymaintenance_options['page'] ) ? $easymaintenance_options['page'] : 0;
	
	if(isset($_GET['intro']) && $_GET['intro'] == true) {
	?>
	<div class="wrap easymaintenance intro">
		<h1><?php echo _e('Thank you for using'); ?> Easy Maintenance<span><?php echo $version; ?></span></h1>
		<p><?php echo _e('Awesome! You have successfully installed/upgraded'); ?> Easy Maintenance.</p>
		<p><?php echo _e('The plugin is now running on your WordPress system.'); ?></p>
		<div id="changenotes" style="display:none">
		<?php
		if( count( $changes) > 0) {
			echo '<ul>';
			echo '<li>' . implode( '</li><li>', $changes) . '</li>';
			echo '</ul>';
		} else {
			echo '<p>No changes were made. :(</p>';
		}
		?>
		<p><?php echo _e('If you have any questions about these changes, please <a href="https://wordpress.org/support/plugin/easy-maintenance" target="_blank">visit the support forum</a>.'); ?></p>
		</div>
		<p class="intro-button"><a href="options-general.php?page=maintenance" class="button button-primary"><?php echo _e('Continue to plugin'); ?></a><a href="javascript:;" id="changenotes_toggle" class="button"><?php echo _e('View changes'); ?></a></p>
	</div>
	<?php
	} else {
	?>
	<div class="wrap easymaintenance">
		<h1>Easy Maintenance<span class="easymaintenance-small"><?php echo $version; ?></span></h1>
		<form method="post" action="options.php">
		<?php settings_fields( 'easymaintenance_settings_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><?php echo _e( 'Status' ); ?>:</th>
					<td><input id="easymaintenance_settings[enable]" name="easymaintenance_settings[enable]" class="checkbox_enabler" type="checkbox" value="1" <?php checked( true, isset( $easymaintenance_options['enable'] ) ); ?>/><label for="easymaintenance_settings[enable]" id="em_enable"></label></td>
				</tr>
				<tr>
					<th scope="row"><?php echo _e( 'Pages' ); ?>:</th>
					<td>
					<?php $pages = get_pages(); ?>
					<select id="easymaintenance_settings[page]" name="easymaintenance_settings[page]">
						<?php foreach($pages as $page) { ?>
							<option value="<?php echo esc_attr( $page->ID ); ?>"<?php if( $selected_page == $page->ID ) { echo ' selected="selected"'; } ?>><?php echo $page->post_title; ?></option>
						<?php } ?>
					</select>
					<p class="description"><?php echo _e( 'Select the page you want to be your maintenance page.' ); ?></p>
					</td>
				</tr>
				<tr class="optionalsettings_p1" style="display:none">
					<th scope="row"><?php echo _e( 'Auto Disable' ); ?>:<p class="description">Optional</p></th>
					<td>
						<input type="search" class="regular-text datepicker" id="easymaintenance_settings[disable]" name="easymaintenance_settings[disable]" value="<?php if(isset($easymaintenance_options['disable'])) { echo $easymaintenance_options['disable']; } ?>">
						<p class="description"><?php echo _e( 'At which date should maintenance mode automatically disable itself?' ); ?></p>
					</td>
				</tr>
				<tr class="optionalsettings_p2" style="display:none">
					<th scope="row"><?php echo _e( 'Shortcode' ); ?>:<p class="description">Optional</p></th>
					<td>
						<select id="easymaintenance_settings[format]" name="easymaintenance_settings[format]">
							<option value="1" <?php if(isset($easymaintenance_options['format']) && $easymaintenance_options['format'] == "1") { echo 'selected'; } ?>>MM-DD-YYYY (default)</option>
							<option value="2" <?php if(isset($easymaintenance_options['format']) && $easymaintenance_options['format'] == "2") { echo 'selected'; } ?>>DD-MM-YYYY</option>
							<option value="3" <?php if(isset($easymaintenance_options['format']) && $easymaintenance_options['format'] == "3") { echo 'selected'; } ?>>YYYY-MM-DD</option>
						</select>
						<p class="description"><?php echo _e( 'You can use the shortcode by pasting <code>[easy-maintenance]</code> into your page.' ); ?></p>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" class="button-primary" value="<?php _e( 'Save' ); ?>" name="submit" /></td>
				</tr>
			</table>
		</form>
		<a href="javascript:;" id="show_os" class="expand">Show optional settings</a>
	</div>
<?php
	}
}

function easymaintenance_register_settings() {
	register_setting( 'easymaintenance_settings_group', 'easymaintenance_settings' );
}
add_action( 'admin_init', 'easymaintenance_register_settings' );

function easymaintenance_date() {
	global $easymaintenance_options;
	
	if(!empty($easymaintenance_options['disable'])) {
		if($format == "1") { $format = "m-d-Y"; } elseif($format == "2") { $format = "d-m-Y"; } elseif($format == "2") { $format = "Y-m-d"; } else {  $format = "m-d-Y"; }
		$date = date_create($easymaintenance_options['disable']);
		return date_format($date,$format);
	} else {
		$error = 'error';
		return '<i>' . $error . '</i>';
	}
}
add_shortcode('easy-maintenance', 'easymaintenance_date');

function easymaintenance_fjquery() {
	global $version;
	?>
	<!-- Easy Maintenance <?php echo $version; ?> // Start -->
	<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(function() {
			$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
		});
		$('.easymaintenance form').submit(function() {
			$('input:submit').attr("disabled", true);	
		});
		$("#show_os").click(function(){
			$(".optionalsettings_p1, .optionalsettings_p2").fadeToggle();
			$(this).text(function(i, v){
				return v === 'Show optional settings' ? 'Hide optional settings' : 'Show optional settings'
			})
		});
		$("#changenotes_toggle").click(function(){
			$("#changenotes").slideToggle();
			$(this).text(function(i, v){
				return v === 'View changes' ? 'Hide changes' : 'View changes'
			})
		});
		$('.checkbox_enabler').change(function () {
			$('#em_enable').text(this.checked ? '<?php echo _e( 'Activated' ); ?>' : '<?php echo _e( 'Deactivated' ); ?>');
		}).change();
	});
	</script>
	<!-- Easy Maintenance <?php echo $version; ?> // End -->
	<?php
	}
add_action( 'in_admin_footer', 'easymaintenance_fjquery' );

function easymaintenance_menu() {
	global $wp_admin_bar;
	global $easymaintenance_options;

	if(isset($easymaintenance_options['enable']) && $easymaintenance_options['enable'] == "1") {
		$wp_admin_bar->add_menu(array(
			'id' => 'maintenance',
			'title' => __('Maintenance'),
			'href' => 'options-general.php?page=maintenance'
		));
	} else {
		$wp_admin_bar->add_menu(array(
			'id' => 'general',
			'title' => __('Maintenance'),
			'href' => 'options-general.php?page=maintenance'
		));
	}
}
add_action('admin_bar_menu', 'easymaintenance_menu', 2000);

function easymaintenance_settings_menu() {
	add_submenu_page('options-general.php', __('Easy Maintenance'), __('Maintenance'),'manage_options', 'maintenance', 'easymaintenance_settings_page');
}
add_action( 'admin_menu', 'easymaintenance_settings_menu' );

function easymaintenance_scripts() {
	wp_register_style('style', plugins_url('scripts/jquery-ui.css',__FILE__ ));
	wp_enqueue_style('style');

	wp_register_script( 'jquery-ui', plugins_url('scripts/jquery-ui.js',__FILE__ ));
	wp_enqueue_script('jquery-ui');
}
add_action( 'admin_init','easymaintenance_scripts');