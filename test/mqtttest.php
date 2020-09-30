<?php

require_once __DIR__."/../vendor/autoload.php";

$mqtt = new \PhpMqtt\Client\MQTTClient("postman.cloudmqtt.com", 13342, "wp");
$mqtt->connect("hbpiywwf","VO5sPd3HeesO");
$mqtt->subscribe("mbr", function ($topic, $message) use ($mqtt) {
    echo sprintf("Received message on topic [%s]: %s\n", $topic, $message);
//	$mqtt->interrupt();
}, 0);

/*function mqttEvent() {
	echo "event..";
}

$mqtt->registerEventHandler("mqttEvent");*/

$mqtt->publish("mbr","action=relay&relay=0&val=0&__req=1234");
$mqtt->loop(true);
