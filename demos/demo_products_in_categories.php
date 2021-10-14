<?php
require_once 'vendor/autoload.php';

$cart = new \CartPriceService\Cart();

$product1 = new \CartPriceService\Product(id:"R01", name: "Red widget",   categories: [1, 2, 3],    price: 3295);
$product2 = new \CartPriceService\Product(id:"G01", name: "Green widget", categories: [2, 3],       price: 2495);
$product3 = new \CartPriceService\Product(id:"B01", name: "Blue widget",  categories: [3],          price: 795);

$cart->addProduct($product3, 2);
$cart->addProduct($product1, 7);


$service = new \CartPriceService\Service();

// 10% off for products that are in category = 2
$service->addRule(
    (new \CartPriceService\Rules\ProductInCategories())
        ->setCategories([2])
        ->setActions([
            (new \CartPriceService\Actions\DiscountProductPrice())->setDiscount(0.1)
        ])
);

//// set price for product in category = 1 to 2.90
$service->addRule(
    (new \CartPriceService\Rules\ProductInCategories())
        ->setCategories([1])
        ->setActions([
            (new \CartPriceService\Actions\BaseProductPriceOverride())->setPrice(290)
        ])
);

// for cart below 50 default delivery rate is used from cart
// for cart between 50 and 90 price used is 2.95
// for cart above 90, free delivery is used
$service->addRule(
    (new \CartPriceService\Rules\CartTotalMinMax())
        ->setMin(5000,false)
        ->setMax(9000, false)
        ->setActions([
            (new \CartPriceService\Actions\OverrideCartDeliveryCost())->setPrice(295)
        ])
);
$service->addRule(
    (new \CartPriceService\Rules\CartTotalMinMax())
        ->setMin(9000,true)
        ->setActions([
            (new \CartPriceService\Actions\OverrideCartDeliveryCost())->setPrice(0)
        ])
);

$service->addRule(
    (new CartPriceService\Rules\NthProduct())
        ->setMinNumber(2)
        ->setApplyTo(\CartPriceService\Rules\NthProduct::APPLY_TO_NTH)
        ->setProductIds(['R01'])
        ->setActions([
            (new \CartPriceService\Actions\DiscountProductPrice())->setDiscount(0.5)
        ])
);


$service->calculateTotals($cart);
var_dump($cart->getTotals());
