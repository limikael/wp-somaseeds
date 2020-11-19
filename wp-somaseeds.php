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

	$vars["apiResult"]=NULL;
	$vars["apiError"]=NULL;
	$vars["statusError"]=NULL;

	try {
		if (array_key_exists("relay", $_REQUEST)) {
			$apiResult=$api->call("relay",array(
				"relay"=>$_REQUEST["relay"],
				"val"=>$_REQUEST["val"]
			));
		}

		else if (array_key_exists("start", $_REQUEST)) {
			$apiResult=$api->call("start",array(
				"rpm"=>$_REQUEST["rpm"]
			));
		}

		else if (array_key_exists("reverse", $_REQUEST)) {
			$apiResult=$api->call("start",array(
				"reverse"=>TRUE
			));
		}

		else if (array_key_exists("stop", $_REQUEST)) {
			$apiResult=$api->call("stop");
		}

		else if (array_key_exists("light",$_REQUEST)) {
			$apiResult=$api->call("lightSchedule",array(
				"schedule"=>$_REQUEST["lightSchedule"],
				"duration"=>$_REQUEST["lightDuration"]
			));
			$vars["apiResult"]="Light settings updated.";
		}

		else if (array_key_exists("motor",$_REQUEST)) {
			$apiResult=$api->call("motorSchedule",array(
				"forwardSchedule"=>$_REQUEST["forwardSchedule"],
				"forwardDuration"=>$_REQUEST["forwardDuration"],
				"backwardSchedule"=>$_REQUEST["backwardSchedule"],
				"backwardDuration"=>$_REQUEST["backwardDuration"]
			));
			$vars["apiResult"]="Pump motor settings updated.";
		}
	}

	catch (Exception $e) {
		$vars["apiError"]=$e->getMessage();
	}

	try {
		$status=$api->call("status");
		$vars["lightSchedule"]=$status["settings"]["lightSchedule"];
		$vars["lightDuration"]=$status["settings"]["lightDuration"];
		$vars["forwardSchedule"]=$status["settings"]["forwardSchedule"];
		$vars["forwardDuration"]=$status["settings"]["forwardDuration"];
		$vars["backwardSchedule"]=$status["settings"]["backwardSchedule"];
		$vars["backwardDuration"]=$status["settings"]["backwardDuration"];
	}

	catch (Exception $e) {
		$vars["statusError"]=$e->getMessage();
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

/*function sose_test() {
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
add_shortcode("sose-test","sose_test");*/

function alignTimestampToMonth($timestamp) {
	return strtotime(gmdate("Y-m-01 00:00:00",$timestamp)." UTC");
}

function sose_chart_data() {
	$timestamp=$_REQUEST["timestamp"];
	$var=$_REQUEST["var"];

	if ($timestamp>time())
		$timestamp=time();

	switch ($_REQUEST["scope"]) {
		case "hour":
			$fromTimestamp=intval(60*60*floor($timestamp/(60*60)));
			$toTimestamp=$fromTimestamp+60*60;
			$prevTimestamp=$fromTimestamp-60*60;
			$span="live";
			$rangeLabel=
				gmdate("j M, Y, H:i",$fromTimestamp)." -> ".
				gmdate("H:i",$toTimestamp);
			$labelFormat="H:i";
			break;

		case "day":
			$fromTimestamp=intval(60*60*24*floor($timestamp/(60*60*24)));
			$toTimestamp=$fromTimestamp+60*60*24;
			$prevTimestamp=$fromTimestamp-60*60*24;
			$span="minutely";
			$rangeLabel=
				gmdate("j M, Y",$fromTimestamp);
			$labelFormat="H:i";
			break;

		case "month":
			$fromTimestamp=alignTimestampToMonth($timestamp);
			$toTimestamp=alignTimestampToMonth(alignTimestampToMonth($timestamp)+32*60*60*24);
			$prevTimestamp=alignTimestampToMonth(alignTimestampToMonth($timestamp)-60*60*24);
			$span="hourly";
			$rangeLabel=
				gmdate("M, Y",$fromTimestamp);
			$labelFormat="j";
			break;

		default:
			wp_die();
			break;
	}

	$output=array();
	$output["labels"]=array();
	$output["tempdata"]=array();
	$output["phdata"]=array();
	$output["nextTimestamp"]=$toTimestamp;
	$output["prevTimestamp"]=$prevTimestamp;
	$output["rangeLabel"]=$rangeLabel;

	$datas=SoseData::getSpanData($var,$span,$fromTimestamp,$toTimestamp);
	foreach ($datas as $data) {
		$output["labels"][]=gmdate($labelFormat,$data->getTimestamp());
		$output["tempdata"][]=$data->value;
	}

	echo json_encode($output);
	wp_die();
}
add_action("wp_ajax_sose_chart_data", "sose_chart_data");
add_action("wp_ajax_nopriv_sose_chart_data", "sose_chart_data");

function sose_chart($params) {
	$vars=array();

	//$vars["timestamp"]=strtotime("2020-01-01 09:00:00 UTC");
	$vars["timestamp"]=time();
	$vars["var"]=$params["var"];

	return render_template(__DIR__."/tpl/sose-chart.tpl.php",$vars);
}
add_shortcode("sose-chart","sose_chart");

function sose_enqueue_scripts() {
	wp_enqueue_script(
		'charts-bundle',
		'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.js',
		array( 'jquery' ), 1.0, true
	);

	wp_enqueue_script(
		'somacharts-scripts', 
		plugin_dir_url( __FILE__ ) . '/js/somaseeds.js',
		array( 'jquery', 'charts-bundle' ), 1.0, true
	);

	wp_enqueue_style(
		'somacharts-style',
		plugin_dir_url( __FILE__ ) . '/css/somaseeds.css'
	);
}
add_action( 'wp_enqueue_scripts', 'sose_enqueue_scripts' );
