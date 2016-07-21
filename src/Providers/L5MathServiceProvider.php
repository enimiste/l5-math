<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 21/07/2016
 * Time: 13:22
 */

namespace Enimiste\L5Math\Providers;


use Enimiste\Math\BcMath\BcMathCalculator;
use Enimiste\Math\Contracts\Calculator;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class L5MathServiceProvider extends ServiceProvider {

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register() {
		$this->app->singleton( 'calculator', BcMathCalculator::class );
		$this->app->bind( Calculator::class,
			function ( Container $app ) {
				return $app->make( 'calculator' );
			} );
	}
}