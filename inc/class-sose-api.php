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
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"X-Api-Key: ysOIV9vNp1hS2tHC"
		));

		$encoded=curl_exec($curl);
		$res=json_decode($encoded,TRUE);

		if (!$res || !array_key_exists("ok",$res) || !$res["ok"]) {
			throw new Exception("Unable to perform API call: ".$encoded);
		}

		return $res;
	}
}