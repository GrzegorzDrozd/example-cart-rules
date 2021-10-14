<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService;

/**
 * Cart representation
 */
class Cart {

    /**
     * Products in cart. List of products disregarding qty.
     *
     * @var \CartPriceService\Product[]
     */
    public array $products;

    /**
     * Flat list of products. If product has qty = 2 it will be twice on this list
     *
     * @var \CartPriceService\Product[]
     */
    protected array $productsFlatList;

    /**
     * Product quantities by product id
     *
     * @var float[]
     */
    protected array $quantities;

    protected int $defaultDeliveryCost = 495;

    public function addProduct(\CartPriceService\Product $product, float $qty) : void
    {
        if (empty($this->quantities[$product->id])) {
            $this->quantities[$product->id] = 0;
        }

        $this->products[$product->id]    = $product;
        $this->quantities[$product->id] += $qty;

        for($i = 0; $i < $qty; $i++) {
            $this->productsFlatList[] = clone $product;
        }
    }

    public function getTotals(): array {
        $ret = [
            'total_price'                           => 0,
            'price_after_discounts'                 => 0,
            'delivery_price'                        => $this->getDefaultDeliveryCost(),
            'elements'                              => [],
            'price_after_discounts_with_delivery'   => 0
        ];
        foreach($this->getProductsFlatList() as $product) {
            $ret['total_price']             += $product->price;
            $ret['delivery_price']          += $product->deliveryCost;

            $ret['price_after_discounts']   += (!empty($product->finalPrice) ? $product->finalPrice : $product->price);

            // debug
            $ret['elements'][] = (!empty($product->finalPrice) ? $product->finalPrice : $product->price);
        }

        $ret['price_after_discounts_with_delivery'] =  $ret['price_after_discounts'] + $ret['delivery_price'];

        return $ret;
    }

    public function getProductsById(string $productId) : array
    {
        return array_filter($this->productsFlatList, static function ($product) use ($productId) {
            return $product->id === $productId;
        });
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function getProductsFlatList(): array
    {
        return $this->productsFlatList;
    }

    public function getProductQuantities(): array
    {
        return $this->quantities;
    }

    public function setDefaultDeliveryCost(int $defaultDeliveryCost): Cart
    {
        $this->defaultDeliveryCost = $defaultDeliveryCost;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultDeliveryCost(): int
    {
        return $this->defaultDeliveryCost;
    }
}
