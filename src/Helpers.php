<?php

if ( ! function_exists( 'calculator' ) ) {
	/**
	 * @return \Enimiste\Math\Contracts\Calculator
	 */
	function calculator() {
		return app()->make( 'calculator' );
	}
}

if ( ! function_exists( 'isInteger' ) ) {
	/**
	 * @param string|int|float $value
	 *
	 * @return bool
	 */
	function isInteger( $value ) {
		return \Enimiste\Math\VO\IntegerNumber::isInt( $value );
	}
}