<?php
require_once 'vendor/autoload.php';


$cart = new \CartPriceService\Cart();

$product_red = new \CartPriceService\Product(id:"R01", name: "Red widget",   categories: [1, 2, 3],    price: 3295);
$product_grn = new \CartPriceService\Product(id:"G01", name: "Green widget", categories: [2, 3],       price: 2495);
$product_blu = new \CartPriceService\Product(id:"B01", name: "Blue widget",  categories: [3],          price: 795);

$cart->addProduct($product_red, 2);
$cart->addProduct($product_blu, 7);


$service = new \CartPriceService\Service();
$rule = new \CartPriceService\Rules\ProductExpression();

// this can be easily extended to include for example:
// product.id in user.last20OrderedProducts

// count(cart.getProductsById("R01")) > 3 AND count(cart.getProductsById("B01")) < 1

// etc.

$rule->setExpression('  
"1" in product.categories and product.price < 1000
');


$rule->setExpressionLanguage(new \Symfony\Component\ExpressionLanguage\ExpressionLanguage());
$rule->addAction((new \CartPriceService\Actions\DiscountProductPrice())->setDiscount(0.5));

$service->addRule($rule);

$service->calculateTotals($cart);
var_dump($cart->getTotals());
