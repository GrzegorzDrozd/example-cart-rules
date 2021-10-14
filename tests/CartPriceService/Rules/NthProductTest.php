<?php

namespace CartPriceService\Rules;

use CartPriceService\Cart;
use CartPriceService\Product;
use PHPUnit\Framework\TestCase;

class NthProductTest extends TestCase {

    protected NthProduct $object;

    public function setUp(): void {
        $this->object = new NthProduct();
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate($expected, $min, $applyTo, $qty) : void {
        $cart = new Cart();
        $cart->addProduct(new Product(price: 10), $qty);

        $this->object->setMinNumber($min);
        $this->object->setApplyTo($applyTo);
        $actual = $this->object->validate($cart);
        $justPrices = array_column($actual, 'price');
        self::assertEquals($expected, $justPrices);
    }

    /**
     * @return array[]
     */
    public function validateDataProvider() {
        return [
            //       expected products        min    apply to               qty in cart
            [[10,10,10,10,10,10,10,10,10,10], 1, NthProduct::APPLY_TO_ALL,  10],
            [[                           10], 1, NthProduct::APPLY_TO_LAST, 10],
            [[               10,10,10,10,10], 5, NthProduct::APPLY_TO_NEXT, 10],
            [[   10,   10,   10,   10,   10], 2, NthProduct::APPLY_TO_NTH,  10],
            [[   10,   10,   10,   10,   10], 2, NthProduct::APPLY_TO_NTH,  11],
            [[   10,   10,   10,   10,     ], 2, NthProduct::APPLY_TO_NTH,  9 ],
            [[      10,      10,   10,   10], 3, NthProduct::APPLY_TO_NTH,  12],
            [[      10,      10,   10,   10], 3, NthProduct::APPLY_TO_NTH,  13],
            [[      10,      10,   10,     ], 3, NthProduct::APPLY_TO_NTH,  11],
        ];
    }
}
