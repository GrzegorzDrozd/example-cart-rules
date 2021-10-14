<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Override product price
 *
 * @codeCoverageIgnore
 */
class OverrideProductPrice extends BaseProductProductPriceOverride
{
    public function applyToProduct(\CartPriceService\Product $product) : void
    {
        $product->finalPrice = $this->price;
    }
}
