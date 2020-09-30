<?php

require_once __DIR__."/../inc/class-mqtt-request.php";

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
	"relay"=>0,
	"val"=>1
));

print_r($res);