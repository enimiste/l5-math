<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 21/07/2016
 * Time: 14:30
 */

namespace Enimiste\L5Math\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * Class CalculatorFacade
 * @package Enimiste\L5Math\Facades
 */
class CalculatorFacade extends Facade {
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor() {
		return 'calculator';
	}


}