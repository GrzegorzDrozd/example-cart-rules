<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * Rule for cart total 
 */
class CartTotalMinMax extends BaseMinMaxRule
{

    public string $scope = SELF::SCOPE_CART;

    /**
     * @return \CartPriceService\Product[]
     */
    public function validate(\CartPriceService\Cart $cart): array
    {
        $prices = array_column($cart->getProductsFlatList(), 'price');
        if ($this->checkValue(array_sum($prices))) {
            return $cart->getProductsFlatList();
        }
        return [];
    }
}
