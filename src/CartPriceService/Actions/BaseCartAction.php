<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Base class for actions that are applied to carts
 */
abstract class BaseCartAction extends BaseAction
{
    /**
     * @param \CartPriceService\Product[] $products optional list of products that are matching the rule
     */
    abstract public function applyToCart(\CartPriceService\Cart $cart, array $products = []) : void;
}
