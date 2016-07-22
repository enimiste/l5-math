# Financial calculation in a laravel 5 project

This package is suited for you if you want to build a Laravel 5 application that manage financial numbers (tva, prices, ...).
PHP float numbers are not exact due to the floating part.

## Float Number
For decimal numbers you should use the `FloatNumber` class.

## Helpers :

*calculator*
Get the calculator instance : 
```php
 $calculator = calculator();
 $calculator->add(.. , ...);
 ....
```

*check_is_integer*
Check if a given is integer or not. The `is_int` of php those not check the integer value given as strings

*as_float_number*
Convert a given value to an instance of `FloatNumber`. Default scale is 2

## Usage :
Add the service provider and the facade to the `config/app.php` file : 
```php
    Enimiste\L5Math\Providers\L5MathServiceProvider::class;
``` 

```php
    'Calculator' => 'Enimiste\L5Math\Facades\CalculatorFacade::class;
``` 

You can access the calculator with two ways :
- `Calculator` facade class
- `calculator()` helper function


Add model mutators :

  For fields that you want to convert them and forward from `FloatNumber` to `DB decimal` types you should add :
  ```php
  
     public function set[Yourattribute]Attribute($value){
          $this->attribute['price'] = as_float_number($value);
     }
     
     public function get[Yourattribute]Attribute($value){
          return as_float_number($value);
     }
  ```

## Calculator features :

```php

interface Calculator {

	/**
	 * Multiplication
	 *
	 * @param Number $l
	 * @param Number $r
	 *
	 * @return Number
	 */
	public function mult( Number $l, Number $r );

	/**
	 * Calculate the TTC price from HT and TVA
	 *
	 * @param FloatNumber $ht
	 * @param FloatNumber $tva between 0 and 1
	 *
	 * @return FloatNumber
	 */
	public function ttc( FloatNumber $ht, FloatNumber $tva );

	/**
	 * Add two Numbers
	 *
	 * @param Number $l
	 * @param Number $r
	 *
	 * @return Number
	 */
	public function add( Number $l, Number $r );

	/**
	 * @param IntegerNumber $quantite
	 * @param FloatNumber   $prixUnitaireHt
	 * @param FloatNumber   $tva
	 *
	 * @return PriceResultDto
	 */
	public function price( IntegerNumber $quantite, FloatNumber $prixUnitaireHt, FloatNumber $tva );

	/**
	 * Build TVA as value betwenn 0 and 1 from a value from 0 to 100
	 *
	 * @param FloatNumber $tva
	 *
	 * @return FloatNumber
	 */
	public function tva( FloatNumber $tva );

	/**
	 * Sub two Numbers
	 * $l - $r
	 *
	 * @param Number $l
	 * @param Number $r
	 *
	 * @return Number
	 */
	public function sub( Number $l, Number $r );
}

```

## Example :
Assume we have a `Product` and an `Ordre` entities that we have to manage in our application.

#### Migrations
```php

   //products table
   $table->increments('id');
   $table->string('title')->unique();
   $table->decimal('price', 8, 2);//000000.00
   $table->decimal('tva', 5, 2)->default(20.0);//between 0.00 and 100.00
   
   //orders table
   $table->increments('id');
   $table->unsignedInteger('amount');//count of product ordered
   $table->decimal('total_ht', 8, 2);// amount * price
   $table->decimal('total_ttc', 8, 2);
   $table->unsignedInteger('product_id');

```

#### Models

```php

class Product extends Model {

   protected $fillable = ['title', 'price', 'tva'];
   
   public function setPriceAttribute($value){
        $this->attribute['price'] = as_float_number($value);
   }
   
   public function getPriceAttribute($value){
        return as_float_number($value);
   }
   
   public function setTvaAttribute($value){
       $this->attribute['tva'] = as_float_number($value);
   }
   
   public function getTvaAttribute($value){
       return as_float_number($value);
   }

}

```

```php
class Order extends Model {

   protected $fillable = ['amount', 'total_ht', 'total_ttc', 'product_id'];
   
   public function setTotalHtAttribute($value){
        $this->attribute['total_ht'] = as_float_number($value);
   }
   
   public function getTotalHtAttribute($value){
        return as_float_number($value);
   }
   
   public function setTotalTtcAttribute($value){
        $this->attribute['total_ttc'] = as_float_number($value);
   }
      
   public function getTotalTtcAttribute($value){
       return as_float_number($value);
   }

}
```

### Seed

```php

 $p = Product::create(
        [
            'price' =>  100.76,
            'tva'   =>  20.0
        ]
 );
 
 $calc = calculator();
 
 $o1 = Order::create(
        [
            'product_id'    =>  $p->id,
            'amount'        =>  33,
            'total_ht'      =>  $ht = $calc->mult($p->price, new Enimiste\Math\VO\IntegerNumber(33)),
            'total_ttc'     =>  $calc->ttc($ht, $calc->tva($p->tva))
        ]
 );

//NB : $calc->tva convert a tva as % to float value between 0 and 1
//another way

 $price = $calc->price(new Enimiste\Math\VO\IntegerNumber(78), $p->price, $calc->tva($p->tva));
 $o2 = Order::create(
        [
            'product_id'    =>  $p->id,
            'amount'        =>  $price->quantite,
            'total_ht'      =>  $price->ht,
            'total_ttc'     =>  $price->ttc
        ]
 );
```

#### Echo 1:
```php

 $products = Product::all();
 
 foreach($products as $p){
    echo 'Price : $' . $p->price->__toString() . PHP_EOL;
    echo 'Tva : ' . $p->tva->__toString() . '%' . PHP_EOL;
 }

```

Will output :

```
 Price : $100.76
 Tva : 20.00%

```

#### Echo 2:
```php

 $orders = Order::all();
 
 foreach($orders as $o){
    echo 'Amount : $' . $o->amount . PHP_EOL;
    echo 'Total Ht : $' . $o->total_ht->__toString() . PHP_EOL;
    echo 'Total Ttc : ' . $o->total_ttc->__toString() . '%' . PHP_EOL;
    echo PHP_EOL;
 }

```

Will output :

```
 Amount : 33
 Total Ht : $3 325.08
 Total Ttc : $3 990.10//exact is 3 990.096 but the scale is 2 sà 0.096 become 0.10
 
 Amount : 78
 Total Ht : $7 859.28
 Total Ttc : $9 431.14//exact is 9 431.136 but the scale is 2 sà 0.136 become 0.14

```