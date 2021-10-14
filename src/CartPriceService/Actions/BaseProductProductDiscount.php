<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

use http\Exception\InvalidArgumentException;

/**
 * Base class to handle percentage discount
 */
abstract class BaseProductProductDiscount extends BaseProductAction
{
    protected float $discount;

    public function setDiscount(float $discount): BaseProductProductDiscount
    {
        if ($discount > 1 || $discount <= 0){
            throw new \InvalidArgumentException('Use value between 0 and 1');
        }
        $this->discount = $discount;
        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }
}
