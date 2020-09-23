<?php

require_once plugin_dir_path( __FILE__ ) . '/../ext/wprecord/WpRecord.php';

class SoseData extends WpRecord {
	public static function initialize() {
		self::field( 'id', 'integer not null auto_increment' );
		self::field( 'var', 'char(16) not null' );
		self::field( 'stamp', 'datetime not null' );
		self::field( 'value', 'float not null' );
	}
}