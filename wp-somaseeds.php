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
	$t=time();

	$var=$_REQUEST["var"];
	if (!$var) {
		echo "no variable to save...";
		wp_die();
	}

	$data=new SoseData();
	$data->var=$var;
	$data->value=$_REQUEST["value"];
	$data->stamp=gmdate("Y-m-d H:i:s",$t);
	$data->span="live";
	$data->save();

	SoseData::summarize($var,"live","minutely",$t);
	SoseData::summarize($var,"minutely","hourly",$t);
	SoseData::summarize($var,"hourly","daily",$t);

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

function sose_test() {
	SoseData::query("DELETE FROM :table");

	$t=strtotime("2020-01-01 09:00:00 UTC");

	for ($i=0; $i<1000; $i++) {
		$v=rand(0,999);

		$d=new SoseData();
		$d->var="temp";
		$d->span="live";
		$d->value=$v;
		$d->min=$v;
		$d->max=$v;
		$d->stamp=gmdate("Y-m-d H:i:s",$t+$i*5);
		$d->save();
	}

	$t=strtotime("2020-01-02 09:02:15 UTC");
	SoseData::summarize("temp","live","minutely",$t);
	SoseData::summarize("temp","minutely","hourly",$t);

	return "testing... hello...";
}
add_shortcode("sose-test","sose_test");
