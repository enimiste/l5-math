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
	 * @return \Enimiste\Math\VO\FloatNumber
	 */
	function as_float_number( $value, $scale = 2 ) {
		if ( $value instanceof \Enimiste\Math\VO\FloatNumber ) {
			return $value->copy( $scale );
		} else {
			if ( $scale < 0 ) {
				throw new \RuntimeException( 'Scale should be >= 0' );
			}

			if ( $value instanceof \Enimiste\Math\VO\IntegerNumber ) {
				$value = $value->__toString();
			}

			return new \Enimiste\Math\VO\FloatNumber( $value, $scale );
		}
	}
}

if ( ! function_exists( 'as_integer_number' ) ) {
	/**
	 * @param string|int|float|\Enimiste\Math\VO\IntegerNumber $value
	 *
	 * @return \Enimiste\Math\VO\FloatNumber
	 */
	function as_integer_number( $value ) {
		if ( $value instanceof \Enimiste\Math\VO\IntegerNumber ) {
			return $value;
		} elseif ( $value instanceof \Enimiste\Math\VO\FloatNumber ) {
			return new \Enimiste\Math\VO\IntegerNumber( $value->__toString() );
		} else {
			return new \Enimiste\Math\VO\IntegerNumber( $value );
		}
	}
}