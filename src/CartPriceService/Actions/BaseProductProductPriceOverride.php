<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Replace price of a product to a fixed price
 *
 * For example: buy 5 and 6th will cost you a 1$
 * @codeCoverageIgnore
 */
class BaseProductProductPriceOverride extends BaseProductAction
{
    use PriceTrait;

    public function applyToProduct(\CartPriceService\Product $product) : void
    {
        $product->finalPrice = $this->price;
    }
}
