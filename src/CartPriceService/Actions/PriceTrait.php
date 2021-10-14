<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Price handling
 * 
 * @codeCoverageIgnore
 */
trait PriceTrait
{
    protected int $price;

    public function setPrice(int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }
}
