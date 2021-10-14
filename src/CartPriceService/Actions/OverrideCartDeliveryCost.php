<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Override whole cart delivery cost
 * @codeCoverageIgnore
 */
class OverrideCartDeliveryCost extends BaseCartAction
{
    use PriceTrait;

    /**
     * @inheritDoc
     */
    public function applyToCart(\CartPriceService\Cart $cart, array $products = []): void
    {
        $cart->setDefaultDeliveryCost($this->getPrice());
    }
}
