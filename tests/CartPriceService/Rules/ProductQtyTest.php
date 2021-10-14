<?php
namespace Tests\CartPriceService\Rules;

use CartPriceService\Rules\ProductQtyMinMax;
use PHPUnit\Framework\TestCase;

/**
 * Test product quantity in the cart rule.
 */
class ProductQtyTest extends TestCase {

    protected \CartPriceService\Service $service;
    
    protected \CartPriceService\Cart$cart;

    protected function setUp() : void
    {
        $this->service = new \CartPriceService\Service();
        $this->cart    = new \CartPriceService\Cart();
    }

    public function validateDataProvider() : array
    {
        return [
/*0*/       [1, 5, 1, true, 10, true], // qty = 5, min=1, max=10 both inclusive
            [1, 1, 1, true, 10, true],
            [1, 10, 1, true, 10, true],
            [1, 10, 1, true, 10, true],

            [0, 1, 1, false, 10, true],
/*5*/       [0, 11, 1, false, 10, true],
            [0, 10, 1, false, 10, false],
            [0, 15, 1, true, 10, true],
            [0, 5, 5, false, 10, true],
        ];
    }

    /**
     * @dataProvider validateDataProvider
     */
    public function testValidate(int $expected, float $qty, float $min, bool $minInclusive, float $max, bool $maxInclusive) : void
    {
        $product = new \CartPriceService\Product(id: "test-1", name: "test product");
        $this->cart->addProduct($product, $qty);

        $actionMock = $this->getMockBuilder(\CartPriceService\Actions\DiscountProductPrice::class)
                          ->onlyMethods(['applyToProduct'])
                          ->disableOriginalConstructor()
                          ->getMock();
        // rule is applied per product in cart so if there are 5 products in cart it is applied 5 times.
        // if we expect it to be applied 0 times we set $expected to 0 and after * by $qty we still have 0 :)
        $actionMock->expects(self::exactly($expected*$qty))->method('applyToProduct');

        $rule = new ProductQtyMinMax();
        $rule->setMin($min, $minInclusive);
        $rule->setMax($max, $maxInclusive);
        $rule->setActions([
            $actionMock
        ]);
        $this->service->addRule($rule);

        $this->service->calculateTotals($this->cart);
    }
}
