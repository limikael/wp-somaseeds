<?php
/**
 * WP Somaseeds
 *
 * Plugin Name:       WP Somaseeds
 * Plugin URI:        https://github.com/limikael/wp-somaseeds
 * GitHub Plugin URI: https://github.com/limikael/wp-somaseeds
 * Description:       Stores data from the MBR.
 * Version:           1.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mikael Lindqvist & Derek Smith
 * Text Domain:       wp-somaseeds
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . '/inc/class-sose-data.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/class-sose-api.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/lib.php';

/**
 * Handle plugin activation.
 *
 * @return void
 */
function sose_activate() {
	SoseData::install();
}
register_activation_hook( __FILE__, 'sose_activate' );

/**
 * Handle plugin uninstall.
 *
 * @return void
 */
function sose_uninstall() {
	SoseData::uninstall();
}
register_uninstall_hook( __FILE__, 'sose_uninstall' );

/**
 * Handle data.
 */
function sose_handle_data() {
	$data=new SoseData();
	$data->var=$_REQUEST["var"];
	$data->value=$_REQUEST["value"];
	$data->stamp=current_time("mysql");
	$data->save();

	wp_die();
}

add_action("wp_ajax_sosedata","sose_handle_data");
add_action("wp_ajax_nopriv_sosedata","sose_handle_data");

function sose_admin_page() {
	$vars=array();
	$api=new SoseApi("http://wordpress-59420-1495432.cloudwaysapps.com:8888/somaseeds1/");

	if (array_key_exists("relay", $_REQUEST)) {
		$api->call("relay",array(
			"relay"=>$_REQUEST["relay"],
			"val"=>$_REQUEST["val"]
		));
	}

	if (array_key_exists("steps", $_REQUEST)) {
		$api->call("step",array(
			"steps"=>$_REQUEST["steps"]
		));
	}

	if (array_key_exists("start", $_REQUEST)) {
		$api->call("start",array(
			"rpm"=>$_REQUEST["rpm"]
		));
	}

	if (array_key_exists("stop", $_REQUEST)) {
		$api->call("stop");
	}

	$vars["formurl"]=admin_url("admin.php?page=somaseeds");
	display_template(__DIR__."/tpl/sose-admin-page.tpl.php",$vars);
}

function sose_admin_menu() {
	add_menu_page(
		'Somaseeds',
		'Somaseeds',
		'manage_options',
		'somaseeds',
		'sose_admin_page'
	);
}
add_action('admin_menu','sose_admin_menu');
