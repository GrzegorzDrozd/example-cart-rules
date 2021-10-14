<?php

namespace CartPriceService\Rules;

use CartPriceService\Cart;
use CartPriceService\Product;
use PHPUnit\Framework\TestCase;

class ProductInCategoriesTest extends TestCase {

    protected ProductInCategories $object;

    public function setUp(): void {
        $this->object = new ProductInCategories();
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate($expected, $cartCategories, $categories)
    {
        $cart = new Cart();

        foreach($cartCategories as $i => $category) {
            $cart->addProduct(new Product(id:"test-".$i, price: 10, categories: $category), 1);
        }

        $this->object->setCategories($categories);

        $actual = $this->object->validate($cart);
        $justPrices = array_column($actual, 'price');
        self::assertEquals($expected, array_sum($justPrices));
    }

    public function validateDataProvider() {
        return [
            [10, [[1,2,3]], [2]],
            [10, [[1,2,3]], [1]],
            [10, [[1,2,3]], [3]],
            [10, [[1,2,3], [1,3]], [2]],
            [20, [[1,2,3], [1,3]], [1]],
            [20, [[1,2,3], [1,3]], [3]],
            [0,  [[1,2,3], [1,3]], [4]],
            [0,  [[4]],     [1,2,3]],
        ];
    }
}
