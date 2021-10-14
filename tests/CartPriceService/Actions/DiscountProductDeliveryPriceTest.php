<?php

namespace CartPriceService\Actions;

use CartPriceService\Product;
use PHPUnit\Framework\TestCase;

class DiscountProductDeliveryPriceTest extends TestCase {


    protected DiscountProductDeliveryPrice $object;

    public function setUp(): void
    {
        $this->object = new DiscountProductDeliveryPrice();
    }

    /**
     * @return void
     */
    public function testApplyingDiscount() {
        $product = new Product(id:"test", deliveryCost: 10);
        $this->object->setDiscount(0.5);
        $this->object->applyToProduct($product);
        self::assertEquals(5, $product->deliveryCost);
    }


    /**
     * @dataProvider settingWrongDiscountDataProvider
     */
    public function testSettingWrongDiscount($value)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->object->setDiscount($value);
    }

    public function settingWrongDiscountDataProvider() {
        return [
            [0],
            [2],
            [3],
            [-1]
        ];
    }

}
