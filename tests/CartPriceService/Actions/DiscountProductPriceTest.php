<?php

namespace CartPriceService\Actions;

use CartPriceService\Product;
use PHPUnit\Framework\TestCase;

class DiscountProductPriceTest extends TestCase {


    protected DiscountProductPrice $object;

    public function setUp(): void
    {
        $this->object = new DiscountProductPrice();
    }

    /**
     * @return void
     */
    public function testApplyingDiscount() {
        $product = new Product(id:"test", price: 10);
        $this->object->setDiscount(0.5);
        $this->object->applyToProduct($product);
        self::assertEquals(5, $product->finalPrice);
        self::assertEquals(10, $product->price);
    }
}
