<?php

if ( ! function_exists( 'calculator' ) ) {
	/**
	 * @return \Enimiste\Math\Contracts\Calculator
	 */
	function calculator() {
		return app()->make( 'calculator' );
	}
}

if ( ! function_exists( 'check_is_integer' ) ) {
	/**
	 * @param string|int|float $value
	 *
	 * @return bool
	 */
	function check_is_integer( $value ) {
		return \Enimiste\Math\VO\IntegerNumber::isInt( $value );
	}
}

if ( ! function_exists( 'as_float_number' ) ) {
	/**
	 * @param string|int|float|\Enimiste\Math\VO\FloatNumber $value
	 *
	 * @param int                                            $scale
	 *
	 * @return bool
	 */
	function as_float_number( $value, $scale = 2 ) {
		if ( $value instanceof \Enimiste\Math\VO\FloatNumber ) {
			return $value->copy( $scale );
		} else {
			if ( $scale < 0 ) {
				throw new \RuntimeException( 'Scale should be >= 0' );
			}

			return new \Enimiste\Math\VO\FloatNumber( $value, $scale );
		}
	}
}