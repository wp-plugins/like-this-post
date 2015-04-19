<?php
/*
Plugin Name: Like This Post
Description: This Plugin is used to like the interested post on the website. It also includes features which facebook have for like on its website. Counts, user names can be viewed. Popup features is also present to view the users.
Version: 1.0
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
	
	add_option('ltp_login_message','Please login to vote.');
	add_option('ltp_login_required', '1');
}
register_activation_hook(__FILE__, 'ltpSetOptions' );

function ltpMenu() {
  add_options_page('Customize the Settings Options for Like this Post', 'Like This Post', 'administrator', 'like-this-post', 'ltpSettings');
}
add_filter('admin_menu', 'ltpMenu');

function ltpSettings() {
	echo '<br/>';
	echo '<br/>';
	echo '<br/>';
	echo 'Hi, with in this April Month our Major Update will include front end customization and other widgets will be produced.';
	echo '<br/>';
	echo 'To kick start now we released this first version.';
	echo '<br/>';
	echo 'But the UI will be 100% from this 1st version itself.';
	echo '<br/>';
	echo 'To customize any UI. please use your own style sheets';
	echo '<br/>';
	echo '<br/>';
	echo 'Thanks in advance for your cooperation';
}

function ltpAdminRegisterSettings() {
	register_setting('ltp_options','ltp_login_message');
	register_setting('ltp_options','ltp_login_required');
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

$plugin_dir   = plugin_dir_path( __FILE__ );
require_once( $plugin_dir . 'counter.php' );
require_once( $plugin_dir . 'ltp-ajax.php' );
require_once( $plugin_dir . 'ltp-view-like.php' );
?>