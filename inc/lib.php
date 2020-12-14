<?php

function display_template( $fn, $vars = array() ) {
	foreach ( $vars as $key => $value ) {
		$$key = $value;
	}

	require $fn;
}

function render_template( $fn, $vars = array() ) {
	ob_start();
	display_template( $fn, $vars );
	return ob_get_clean();
}

function buttonOnOff($text, $url, $on) {
	printf("<a href=\"%s\" class=\"%s\">%s</a>",
		$url,
		$on?"button button-primary":"button",
		$text
	);
}

function display_select_options( $options, $current = null ) {
	if ( ! $options ) {
		return;
	}

	foreach ( $options as $key => $label ) {
		printf(
			'<option value="%s" %s>%s</option>',
			esc_attr( $key ),
			( ( strval( $current ) === strval( $key ) ) ? 'selected' : '' ),
			esc_html( $label )
		);
	}
}
