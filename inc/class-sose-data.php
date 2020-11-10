<?php

require_once plugin_dir_path( __FILE__ ) . '/../ext/wprecord/WpRecord.php';

class SoseData extends WpRecord {
	public static function initialize() {
		self::field( 'id', 'integer not null auto_increment' );
		self::field( 'var', 'char(16) not null' );
		self::field( 'stamp', 'datetime not null' );
		self::field( 'value', 'float not null' );
		self::field( 'min', 'float not null' );
		self::field( 'max', 'float not null' );
		self::field( 'span', 'char(16) not null' );
		self::field( 'summarized', 'tinyint not null' );
	}

	public static function summarize($var, $fromSpan, $toSpan, $time) {
		$spans=array(
			"live"=>1,
			"minutely"=>60,
			"hourly"=>60*60,
			"daily"=>60*60*24
		);

		if (!$spans[$fromSpan] || !$spans[$toSpan])
			throw new Error("Unknown span");

		$currentSpanStart=floor($time/$spans[$toSpan])*$spans[$toSpan];

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
			$t=floor($t/$spans[$toSpan])*$spans[$toSpan];
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
}