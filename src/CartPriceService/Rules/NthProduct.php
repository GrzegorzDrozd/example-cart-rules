<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * N-th product rule.
 *
 * @see \CartPriceService\Rules\NthProduct::$applyTo
 */
class NthProduct extends BaseRule
{

    const APPLY_TO_ALL = 'all';
    const APPLY_TO_LAST = 'last';
    const APPLY_TO_NEXT = 'next';
    const APPLY_TO_NTH = 'n-th';

    /**
     * Minimal number of products that rule will start to apply
     */
    protected float $minNumber = 0;

    /**
     * Apply this rule ONLY to products that are listed here
     * 
     * @var string[]
     */
    protected array $productIds = [];

    /**
     * Determine which products actions are applied to 
     *
     * If this flag is set to 'all' then action is applied to ALL products that match qty check.
     *
     * If this flag is set to 'next' then action is applied to products that are above min number check. For example:
     * In cart we have a product with qty 7. Min is set to 2.
     * (if discount is an action then only products from 3rd will have discount applied)
     * 1
     * 2
     * 3+
     * 4+
     * 5+
     * 6+
     * 7+
     *
     * If this flag is set to 'last' then action is applied to last instance of product
     * 1
     * 2
     * 3
     * 4
     * 5
     * 6
     * 7+
     *
     * If this flag is set to 'n-th' then action is applied to n-th product. For example:
     * min number is set to 2, we have a product with qty = 7, products marked with + have actions applied:
     * 1
     * 2
     * 3+
     * 4
     * 5
     * 6+
     * 7
     */
    protected string $applyTo = self::APPLY_TO_NEXT;

    /**
     * @return \CartPriceService\Product[]
     */
    public function validate(\CartPriceService\Cart $cart): array
    {

        $ret = [];
        foreach ($cart->getProductQuantities() as $productId => $qty) {
            // only care when qty checks 
            if ($qty <= $this->minNumber) {
                continue;
            }

            // check if this rule should be applied to specific products in the cart only
            if (!empty($this->productIds) && !in_array($productId, $this->productIds, true)){
                continue;
            }

            // get products that we will apply actions to
            $products = match ($this->applyTo){
                self::APPLY_TO_NEXT => array_slice(
                    $cart->getProductsById($productId),
                    $this->minNumber
                ),
                self::APPLY_TO_LAST => array_slice(
                    $cart->getProductsById($productId),
                    -1
                ),
                self::APPLY_TO_ALL => $cart->getProductsById($productId),
                self::APPLY_TO_NTH => $this->getNthElements($cart->getProductsById($productId))
            };

            /** @noinspection SlowArrayOperationsInLoopInspection */
            $ret = array_merge($ret, $products);
        }

        return $ret;
    }

    protected function getNthElements(array $products) : array
    {
        $ret = [];
        // split arrays into chunks, that way we have exactly $this->minNumber in sub-arrays
        $chunked = array_chunk($products, $this->minNumber);
        // iterate over subarrays
        foreach($chunked as $group) {
            // get element from the end of the array.
            // note $this->minNumber-1 vs -1: -1 will return LAST element of the array BUT when array
            // has fewer elements (aka: last of the chunks) then still LAST one is returned. Asking for
            // specific element ($this->minNumber-1) when array is shorter, then empty array is returned
            $ret = array_merge($ret, array_slice($group, $this->minNumber-1, 1));
        }

        return $ret;
    }

    public function setMinNumber(int $minNumber): NthProduct
    {
        $this->minNumber = $minNumber;
        return $this;
    }

    /**
     * @see \CartPriceService\Rules\NthProduct::$applyTo
     */
    public function setApplyTo(string $applyTo): NthProduct
    {
        if (!in_array($applyTo, [self::APPLY_TO_NTH,self::APPLY_TO_NEXT,self::APPLY_TO_ALL,self::APPLY_TO_LAST], true)) {
            throw new \InvalidArgumentException('Invalid value for apply to');
        }
        $this->applyTo = $applyTo;
        return $this;
    }

    /**
     * @param string[] $productIds
     */
    public function setProductIds(array $productIds): NthProduct
    {
        $this->productIds = $productIds;
        return $this;
    }
}
