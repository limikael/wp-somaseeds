<?php

require_once plugin_dir_path( __FILE__ ) . '/../ext/wprecord/WpRecord.php';

class SoseData extends WpRecord {
	const spans=array(
		"live"=>5,
		"minutely"=>60,
		"hourly"=>60*60,
		"daily"=>60*60*24
	);

	public static function initialize() {
		self::field( 'id', 'integer not null auto_increment' );
		self::field( 'var', 'char(16) not null' );
		self::field( 'stamp', 'datetime not null' );
		self::field( 'value', 'float not null' );
		self::field( 'min', 'float not null' );
		self::field( 'max', 'float not null' );
		self::field( 'span', 'char(16) not null' );
		self::field( 'summarized', 'tinyint not null' );

		self::index( 'stamprange', '(var,span,stamp)');
		self::index( 'stamprange_summarized', '(var,span,summarized,stamp)');
	}

	public static function summarize($var, $fromSpan, $toSpan, $time) {
		if (!SoseData::spans[$fromSpan] || !SoseData::spans[$toSpan])
			throw new Error("Unknown span");

		$currentSpanStart=SoseData::spanifyTimestamp($toSpan,$time);
		$datas=SoseData::findAllByQuery(
			"SELECT * ".
			"FROM   :table ".
			"WHERE  var=%s ".
			"AND    summarized=0 ".
			"AND    stamp<%s ".
			"AND    span=%s",
			$var,
			gmdate("Y-m-d H:i:s",$currentSpanStart),
			$fromSpan);

		$summaryDataByStamp=array();
		foreach ($datas as $data) {
			if ($fromSpan=="live") {
				$data->min=$data->value;
				$data->max=$data->value;
			}

			$t=strtotime($data->stamp." UTC");
			$t=SoseData::spanifyTimestamp($toSpan,$t);
			$spanStamp=gmdate("Y-m-d H:i:s",$t);

			if (!array_key_exists($spanStamp,$summaryDataByStamp)) {
				$spanData=new SoseData();
				$spanData->var=$var;
				$spanData->value=0;
				$spanData->count=0;
				$spanData->span=$toSpan;
				$spanData->stamp=$spanStamp;
				$spanData->min=$data->min;
				$spanData->max=$data->max;

				$summaryDataByStamp[$spanStamp]=$spanData;
			}

			$summaryDataByStamp[$spanStamp]->value+=$data->value;
			$summaryDataByStamp[$spanStamp]->count++;

			if ($data->min<$summaryDataByStamp[$spanStamp]->min)
				$summaryDataByStamp[$spanStamp]->min=$data->min;

			if ($data->max>$summaryDataByStamp[$spanStamp]->max)
				$summaryDataByStamp[$spanStamp]->max=$data->max;
		}

		foreach ($summaryDataByStamp as $summaryData) {
			$summaryData->value/=$summaryData->count;
			$summaryData->save();
		}

		foreach ($datas as $data) {
			$data->summarized=1;
			$data->save();
		}
	}

	public static function spanifyTimestamp($span, $time) {
		return floor($time/SoseData::spans[$span])*SoseData::spans[$span];
	}

	public function getSpanifiedTimestamp() {
		return SoseData::spanifyTimestamp($this->span,strtotime($this->stamp." UTC"));
	}

	public function getTimestamp() {
		return strtotime($this->stamp." UTC");
	}

	public static function getSpanData($var, $span, $fromTimestamp, $toTimestamp) {
		$fromTimestamp=intval($fromTimestamp);
		$toTimestamp=intval($toTimestamp);

		$datas=SoseData::findAllByQuery(
			"SELECT * ".
			"FROM   :table ".
			"WHERE  var=%s ".
			"AND    span=%s ".
			"AND    stamp>=%s ".
			"AND    stamp<%s",
			$var,
			$span,
			gmdate("Y-m-d H:i:s",$fromTimestamp),
			gmdate("Y-m-d H:i:s",$toTimestamp)
		);

		$datasByStamp=array();
		foreach ($datas as $data)
			$datasByStamp[$data->getSpanifiedTimestamp()]=$data;

		for ($t=$fromTimestamp; $t<$toTimestamp; $t+=SoseData::spans[$span]) {
			if (!array_key_exists($t,$datasByStamp)) {
				$data=new SoseData();
				$data->stamp=gmdate("Y-m-d H:i:s",$t);
				$datasByStamp[$t]=$data;
			}
		}

		ksort($datasByStamp);

		return array_values($datasByStamp);
	}
}