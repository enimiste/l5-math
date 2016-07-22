<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 22/07/2016
 * Time: 11:09
 */

namespace Test\Enimiste\L5Math;


use Enimiste\Math\VO\FloatNumber;
use Enimiste\Math\VO\IntegerNumber;

class HelpersTest extends TestCase {

	public function ints_conversion() {
		return [
			[ 1, 1 ],
			[ 0, 0 ],
			[ 0.0, 0 ],
			[ '0.0', 0 ],
			[ '0', 0 ],
			[ 129, 129 ],
			[ 129.78, 129 ],
			[ 129.48, 129 ],
			[ new FloatNumber( 129.48 ), 129 ],
			[ new FloatNumber( 129.78 ), 129 ],
		];
	}

	/**
	 * @test
	 * @dataProvider ints_conversion
	 */
	public function check_as_integer_number_helper( $from, $expected ) {
		$int = as_integer_number( $from );

		$this->assertEquals( $expected, $int->getValue() );
		$this->assertEquals( $expected, $int->__toString() );
	}

	public function floats_conversion() {
		return [
			[ 1.0, 1.0 ],
			[ 0.0, 0.0 ],
			[ '0.0', 0.0 ],
			[ '0', 0.0 ],
			[ 129, 129.0 ],
			[ new IntegerNumber( 129 ), 129.0 ],
			[ 129.78, 129.78 ],
			[ 129.48, 129.48 ],
			[ new FloatNumber( 129.48 ), 129.48 ],
			[ new FloatNumber( 129.78 ), 129.78 ],
		];
	}

	/**
	 * @test
	 * @dataProvider floats_conversion
	 */
	public function check_as_float_number_helper( $from, $expected ) {
		$f = as_float_number( $from );

		$this->assertEquals( $expected, $f->getValue() );
		$this->assertTrue( ( new FloatNumber( $expected ) )->equals( $f->__toString() ) );
	}
}