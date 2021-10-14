<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Apply percentage discount on product price
 */
class DiscountProductPrice extends BaseProductProductDiscount
{
    public function applyToProduct(\CartPriceService\Product $product) : void
    {
        $product->finalPrice = $product->price - ($product->price * $this->getDiscount());
    }
}
