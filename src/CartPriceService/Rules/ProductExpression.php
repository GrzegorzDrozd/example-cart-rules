<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ProductExpression extends BaseRule {

    protected \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage;

    protected string $expression;


    public function validate(\CartPriceService\Cart $cart): array {

        $ret = [];
        foreach($cart->getProductsFlatList() as $product) {
            $status = $this->expressionLanguage->evaluate(
                $this->expression,
                [
                    'cart'      => $cart,
                    'product'   => $product,
                    'products_flat' => $cart->getProductsFlatList()
                ]
            );

            if ($status) {
                $ret[] = $product;
            }
        }
        return $ret;
    }

    /**
     * @param mixed $expressionLanguage
     * @return ProductExpression
     */
    public function setExpressionLanguage(\Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage) {
        // register some basic functions
        $expressionLanguage->addFunction(ExpressionFunction::fromPhp('count'));
        $expressionLanguage->addFunction(ExpressionFunction::fromPhp('in_array'));
        $expressionLanguage->addFunction(ExpressionFunction::fromPhp('array_filter'));
        $expressionLanguage->addFunction(ExpressionFunction::fromPhp('array_column'));

        $this->expressionLanguage = $expressionLanguage;



        return $this;
    }

    /**
     * @param string $expression
     * @return ProductExpression
     */
    public function setExpression(string $expression): ProductExpression {
        $this->expression = $expression;
        return $this;
    }
}
