<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * Check if at least one product category is on a whitelist set in this rule
 */
class ProductInCategories extends BaseRule
{
    public string $scope = self::SCOPE_PRODUCT;

    /**
     * @var int[]
     */
    protected array $categories = [];

    public function setCategories(array $categories = []): ProductInCategories
    {
        $this->categories = $categories;
        return $this;
    }

    /**
     * \CartPriceService\Product[]
     */
    public function validate(\CartPriceService\Cart $cart): array
    {
        $ret = [];
        foreach($cart->getProductsFlatList() as $product) {
            if (!empty(array_intersect($product->categories, $this->categories))) {
                $ret[] = $product;
            }
        }
        return $ret;
    }
}
