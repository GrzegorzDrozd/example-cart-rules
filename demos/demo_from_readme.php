<?php
require_once 'vendor/autoload.php';

$product_red = new \CartPriceService\Product(id:"R01", name: "Red widget",   categories: [1, 2, 3],    price: 3295);
$product_grn = new \CartPriceService\Product(id:"G01", name: "Green widget", categories: [2, 3],       price: 2495);
$product_blu = new \CartPriceService\Product(id:"B01", name: "Blue widget",  categories: [3],          price: 795);

$service = new \CartPriceService\Service();

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
, 10);
$service->addRule(
    (new \CartPriceService\Rules\CartTotalMinMax())
        ->setMin(9000,true)
        ->setActions([
            (new \CartPriceService\Actions\OverrideCartDeliveryCost())->setPrice(0)
        ])
, 11);

$service->addRule(
    (new CartPriceService\Rules\NthProduct())
        ->setMinNumber(1)
        ->setApplyTo(\CartPriceService\Rules\NthProduct::APPLY_TO_LAST)
        ->setProductIds(['R01'])
        ->setActions([
            (new \CartPriceService\Actions\DiscountProductPrice())->setDiscount(0.5)
        ])
,1);


$cart = new \CartPriceService\Cart();
$cart->addProduct($product_blu, 1);
$cart->addProduct($product_grn, 1);
$service->calculateTotals($cart);
assert($cart->getTotals()['price_after_discounts_with_delivery'] === 3785);


$cart = new \CartPriceService\Cart();
$cart->addProduct($product_red, 2);
$service->calculateTotals($cart);
assert($cart->getTotals()['price_after_discounts_with_delivery'] === 5237);

$cart = new \CartPriceService\Cart();
$cart->addProduct($product_red, 1);
$cart->addProduct($product_grn, 1);
$service->calculateTotals($cart);
assert($cart->getTotals()['price_after_discounts_with_delivery'] === 6085);

$cart = new \CartPriceService\Cart();
$cart->addProduct($product_blu, 2);
$cart->addProduct($product_red, 3);
$service->calculateTotals($cart);
assert($cart->getTotals()['price_after_discounts_with_delivery'] === 9827);
