<?php

require_once __DIR__."/../vendor/autoload.php";

class MqttRequest {
	function __construct($settings) {
		$this->settings=$settings;
	}

	function request($params) {
		$reqid=uniqid();

		$mqtt = new \PhpMqtt\Client\MQTTClient(
			$this->settings["server"],
			$this->settings["port"],
			$this->settings["id"]
		);

		$mqtt->connect($this->settings["user"],$this->settings["pass"]);

		$response=array();
		$mqtt->subscribe($this->settings["topic"], function ($topic, $message) use ($mqtt, $reqid, &$response) {
			parse_str($message,$res);

			if (array_key_exists("__res",$res) && $res["__res"]==$reqid) {
				unset($res["__res"]);
				$response=$res;
				$mqtt->interrupt();
			}
		}, 0);

		$params["__req"]=$reqid;

		$mqtt->publish($this->settings["topic"],http_build_query($params));
		$mqtt->loop(true);

		return $response;
	}
}