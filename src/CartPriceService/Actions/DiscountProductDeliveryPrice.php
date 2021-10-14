<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Apply percentage discount on product delivery cost
 */
class DiscountProductDeliveryPrice extends BaseProductProductDiscount
{
    public function applyToProduct(\CartPriceService\Product $product) : void
    {
        $product->deliveryCost -= $product->deliveryCost * $this->getDiscount();
    }
}
