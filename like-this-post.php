<?php
/*
Plugin Name: Like This Post
Description: This Plugin is used to like the interested post on the website. It also includes features which facebook have for like on its website. Counts, user names can be viewed. Popup features is also present to view the users.
Version: 1.1
Author: Thiyagesh M

Copyright 2015  Thiyagesh M (email : thyash11@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function ltpSetOptions() {
	global $wpdb;
	$mydbname = $wpdb->prefix . 'ltp_datas';

    if ($wpdb->get_var("show tables like '$mydbname'") != $mydbname) {
		$sql = "CREATE TABLE " . $mydbname . " (
			`id` bigint(11) NOT NULL AUTO_INCREMENT,
			`post_id` int(11) NOT NULL,
			`value` int(2) NOT NULL,
			`user_id` int(11) NOT NULL,
			`date_time` datetime NOT NULL,
			PRIMARY KEY (`id`)
		);";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);	
	}
	add_option('ltp_show_ajax_notify','1');
	add_option('ltp_login_message','Please login to vote.');
	add_option('ltp_thanks_message','Thanks for voting.');
	add_option('ltp_already_liked_message','You Already Liked.');
	add_option('ltp_login_required', '1');
	add_option('ltp_show_only_count', '0');
}
register_activation_hook(__FILE__, 'ltpSetOptions' );

function ltpMenu() {
  add_options_page('Like this Post | Admin Settings', 'Like This Post', 'administrator', 'like-this-post', 'ltpSettings');
}
add_filter('admin_menu', 'ltpMenu');

function ltpSettings() {
	echo '<br/>';
	echo '<h2>Like This Post Options</h2>';
	$show_ajax_notify = get_option('ltp_show_ajax_notify');
	$loginplease = get_option('ltp_login_message');
	$thanksforlike = get_option('ltp_thanks_message');
	$alreadylikes = get_option('ltp_already_liked_message');
	$show_only_count = get_option('ltp_show_only_count');
	?>
	<div>
		<div class="left-side-box">
			<form method="post" action="options.php">
				<?php settings_fields('ltp_options'); ?>
				<div class="ltp-each-section">
					<label class="main-descpn">Show Ajax Notifications on Like</label>
					<input type="radio" name="ltp_show_ajax_notify" id="ltp_show_ajax_notify_y" value="1" <?php if($show_ajax_notify == 1) { echo 'checked';} ?> />
					<label for="ltp_show_ajax_notify_y">Yes</label>
					<input type="radio" name="ltp_show_ajax_notify" id="ltp_show_ajax_notify_n" value="0" <?php if($show_ajax_notify == 0) { echo 'checked';} ?> />
					<label for="ltp_show_ajax_notify_n">No</label>
				</div>
				<div class="ltp-each-section">
					<label class="main-descpn" for="ltp_login_message">Login Required Notification</label>
					<input type="text" size="25" name="ltp_login_message" id="ltp_login_message" value="<?php echo $loginplease; ?>" />
					<span class="description">Message Shown to login for like when clicking on like button</span><br/>
					</div>
				<div class="ltp-each-section">
					<label class="main-descpn" for="ltp_thanks_message">Thanks Notification</label>
					<input type="text" size="25" name="ltp_thanks_message" id="ltp_thanks_message" value="<?php echo $thanksforlike; ?>" />
					<span class="description">Message Shown to thanks for their likes</span>
				</div>
				<div class="ltp-each-section">
					<label class="main-descpn" for="ltp_already_liked_message">Already Liked Notification</label>
					<input type="text" size="25" name="ltp_already_liked_message" id="ltp_already_liked_message" value="<?php echo $alreadylikes; ?>" />
					<span class="description">Message Shown that already the current user liked the post</span>
				</div>
				<div class="ltp-each-section">
					<label class="main-descpn">Show Only Counts</label>
					<input type="radio" name="ltp_show_only_count" id="ltp_show_only_count_y" value="1" <?php if($show_only_count == 1) { echo 'checked';} ?> />
					<label for="ltp_show_only_count_y">Yes</label>
					<input type="radio" name="ltp_show_only_count" id="ltp_show_only_count_n" value="0" <?php if($show_only_count == 0) { echo 'checked';} ?> />
					<label for="ltp_show_only_count_n">No</label>
					<span class="description" style="margin-left: 129px">Hide the Names of People who like the Posts</span>
				</div>
				<div class="ltp-each-section">
					<input class="button-primary" type="submit" name="Save" value="<?php _e('Save Options', 'ltp-like-post'); ?>" /><br/>
				</div>
			</form>
		</div>
		<div class="right-side-box">
			<!-- Paypal Donation Box -->
		</div>
	</div>
<?php
}

function ltpAdminRegisterSettings() {
	register_setting('ltp_options','ltp_show_ajax_notify');
	register_setting('ltp_options','ltp_login_message');
	register_setting('ltp_options','ltp_thanks_message');
	register_setting('ltp_options','ltp_already_liked_message');
	register_setting('ltp_options','ltp_login_required');
	register_setting('ltp_options','ltp_show_only_count');
}
add_action('admin_init', 'ltpAdminRegisterSettings');

function ltpAddingScripts() {
	wp_register_script('ltp_ajax_script', plugins_url('js/ltp_post_ajax.js', __FILE__), array('jquery'), true);
	wp_localize_script( 'ltp_ajax_script', 'ltpajax', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
    wp_enqueue_script( 'jquery' );
	wp_enqueue_script('ltp_ajax_script');
}
add_action( 'wp_enqueue_scripts', 'ltpAddingScripts' );

function ltpAddingStyles() {
	echo '<link rel="stylesheet" type="text/css" href="' . plugins_url( 'css/ltp-style.css', __FILE__) . '" media="screen" />';
}
add_action( 'wp_head', 'ltpAddingStyles' );

function admin_register_head() {
    $siteurl = get_option('siteurl');
    $url = $siteurl . '/wp-content/plugins/' . basename(dirname(__FILE__)) . '/css/admin-custom.css';
    echo "<link rel='stylesheet' type='text/css' href='$url' />\n";
}
add_action('admin_head', 'admin_register_head');

$plugin_dir   = plugin_dir_path( __FILE__ );

require_once( $plugin_dir . 'counter.php' );
require_once( $plugin_dir . 'ltp-ajax.php' );
require_once( $plugin_dir . 'ltp-view-like.php' );
?>