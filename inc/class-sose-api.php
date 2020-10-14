<?php 

class SoseApi extends WpRecord {
	function __construct($url) {
		$this->url=$url;
	}

	function call($func, $params=array()) {
		$url=$this->url."/".$func."/?".http_build_query($params);

		$curl=curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
		$res=curl_exec($curl);
	}
}