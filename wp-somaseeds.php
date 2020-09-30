<?php
/**
 * WP Somaseeds
 *
 * Plugin Name:       WP Somaseeds
 * Plugin URI:        https://github.com/limikael/wp-somaseeds
 * GitHub Plugin URI: https://github.com/limikael/wp-somaseeds
 * Description:       Stores data from the MBR.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mikael Lindqvist & Derek Smith
 * Text Domain:       wp-somaseeds
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

defined( 'ABSPATH' ) || exit;

require_once plugin_dir_path( __FILE__ ) . '/inc/class-sose-data.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/lib.php';
require_once plugin_dir_path( __FILE__ ) . '/inc/class-mqtt-request.php';

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

	if (array_key_exists("relay", $_REQUEST)) {
		$r=new MqttRequest(array(
			"server"=>"postman.cloudmqtt.com",
			"id"=>"wp",
			"port"=>13342,
			"user"=>"hbpiywwf",
			"pass"=>"VO5sPd3HeesO",
			"topic"=>"mbr"
		));

		$res=$r->request(array(
			"action"=>"relay",
			"relay"=>$_REQUEST["relay"],
			"val"=>$_REQUEST["val"]
		));
	}

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
