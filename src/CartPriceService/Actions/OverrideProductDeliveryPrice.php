<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

use CartPriceService\Product;

/**
 * Set delivery price to product
 * 
 * @codeCoverageIgnore
 */
class OverrideProductDeliveryPrice extends BaseProductProductPriceOverride
{
    public function applyToProduct(Product $product) : void
    {
        $product->deliveryCost = $this->price;
    }
}
