<?php

namespace CartPriceService\Rules;

use CartPriceService\Cart;
use CartPriceService\Product;
use PHPUnit\Framework\TestCase;

class CartTotalMinMaxTest extends TestCase {

    protected CartTotalMinMax $object;

    public function setUp(): void {
        $this->object = new CartTotalMinMax();
    }

    /**
     * @param $expected
     * @param $min
     * @param $max
     * @return void
     * @dataProvider validateDataProvider
     */
    public function testValidate($expected, $min, $minInclusive, $max, $maxInclusive, $prices)
    {
        $cart = new Cart();

        foreach($prices as $i => $price) {
            $cart->addProduct(new Product(id:"test-".$i, price: $price), 1);
        }

        $this->object->setMin($min, $minInclusive);
        $this->object->setMax($max, $maxInclusive);

        $actual = $this->object->validate($cart);
        $justPrices = array_column($actual, 'price');
        self::assertEquals($expected, array_sum($justPrices));
    }

    public function validateDataProvider() {
        return [
            [6, 5, true, 10, true, [1,2,3]],
            [6, 6, true, 10, true, [1,2,3]],
            [0, 6, false, 10, true, [1,2,3]],
            [6, 5, false, 6, true, [1,2,3]],
        ];
    }
}
