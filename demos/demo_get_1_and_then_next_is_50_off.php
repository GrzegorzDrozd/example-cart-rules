<?php
require_once 'vendor/autoload.php';


$cart = new \CartPriceService\Cart();

$product_red = new \CartPriceService\Product(id:"R01", name: "Red widget",   categories: [1, 2, 3],    price: 3295);
$product_grn = new \CartPriceService\Product(id:"G01", name: "Green widget", categories: [2, 3],       price: 2495);
$product_blu = new \CartPriceService\Product(id:"B01", name: "Blue widget",  categories: [3],          price: 795);

$cart->addProduct($product_blu, 2);
$cart->addProduct($product_red, 3);


$service = new \CartPriceService\Service();

$service->addRule(
    (new CartPriceService\Rules\NthProduct())
        ->setMinNumber(2)
        ->setApplyTo(\CartPriceService\Rules\NthProduct::APPLY_TO_LAST)
        ->setProductIds(['R01'])
        ->setActions([
            (new \CartPriceService\Actions\DiscountProductPrice())->setDiscount(0.5)
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


$service->calculateTotals($cart);
var_dump($cart->getTotals());
