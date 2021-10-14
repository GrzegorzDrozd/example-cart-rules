<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService;

use CartPriceService\Rules\BaseRule;

/**
 * Price service
 */
class Service {

    /**
     * Typehint is not strictly true, but it helps a lot with code suggestions in IDE
     *
     * @var \BaseRule[] $rules
     */
    protected \SplPriorityQueue $rules;

    public function __construct()
    {
        $this->rules = new \SplPriorityQueue();
        $this->rules->setExtractFlags(\SplPriorityQueue::EXTR_DATA);
    }

    /**
     * Add rules to a stack.
     *
     * Rules with higher priority are processed first.
     */
    public function addRule(\CartPriceService\Rules\BaseRule $rule, int $priority = 1) : void
    {
        $this->rules->insert($rule, $priority);
    }

    /**
     * Add rules to a stack.
     *
     * Rules with higher priority are processed first.
     */
    public function addRuleFromJSON(string $json, $priority = 1) : void
    {
        try {
            $parsed = json_decode($json, true, 10, JSON_THROW_ON_ERROR | JSON_OBJECT_AS_ARRAY);
        } catch (\JsonException $e) {
            throw new \InvalidArgumentException("Unable to create rule from storage.");
        }

        $rule = \CartPriceService\Rules\BaseRule::fromJson($parsed['class'], $parsed['options']);

        $this->addRule($rule, $priority);
    }

    /**
     * Process prices of items in cart 
     */
    public function calculateTotals(Cart $cart) : Cart
    {
        foreach ($this->getRules() as $rule) {
            // get list of products that are matching this rule
            if ($products = $rule->validate($cart)) {
                if ($rule->getScope() === \CartPriceService\Rules\BaseRule::SCOPE_PRODUCT) {
                    // apply rule only to products that match
                    foreach($products as $product) {
                        /** @var \CartPriceService\Actions\BaseProductAction $action */
                        foreach($rule->getActions() as $action) {
                            $action->applyToProduct($product);
                        }
                    }
                } else {
                    /** @var \CartPriceService\Actions\BaseCartAction $action */
                    foreach($rule->getActions() as $action) {
                        $action->applyToCart($cart, $products);
                    }
                }

                if ($rule->isStop()) {
                    break;
                }
            }
        }

        return $cart;
    }

    /**
     * @return \BaseRule[]
     */
    public function getRules(): \SplPriorityQueue {
        return clone $this->rules;
    }
}

