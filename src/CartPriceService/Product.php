<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService;

/**
 * Representation of product
 */
class Product {
    public function __construct(
        public string $id = '',
        public string $name = '',
        public string $description = '',
        public array $categories = [],
        public int $price = 0,
        public int $deliveryCost = 0,
        public int $finalPrice = 0
    ) {}
}
