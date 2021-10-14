<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * Check if product is with min qantity
 */
class ProductQtyMinMax extends BaseMinMaxRule
{
    /**
     * @return \CartPriceService\Product[]
     */
    public function validate(\CartPriceService\Cart $cart): array
    {
        $ret = [];
        foreach ($cart->getProductQuantities() as $productId => $qty) {
            if ($this->checkValue($qty)) {
                $ret = array_merge($ret, $cart->getProductsById($productId));
            }
        }
        return $ret;
    }
}
