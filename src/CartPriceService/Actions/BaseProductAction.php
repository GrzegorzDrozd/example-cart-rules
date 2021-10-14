<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Base action class for actions that are used on products in the cart
 */
abstract class BaseProductAction extends BaseAction
{
    abstract public function applyToProduct(\CartPriceService\Product $product) : void;
}
