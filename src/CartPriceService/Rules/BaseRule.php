<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * Base class for all rules
 */
abstract class BaseRule implements \JsonSerializable
{

    const SCOPE_CART = 'cart';
    const SCOPE_PRODUCT = 'product';

    /**
     * Scope of this rule.
     *
     * Cart = apply all actions to all elements in the product
     * Product = apply all actions ONLY to elements that match rule. Default.
     *
     * @var string
     */
    public string $scope = self::SCOPE_PRODUCT;

    /**
     * Should processing stop after this rule or not.
     */
    public bool $stop = false;

    /**
     * @var \CartPriceService\Actions\BaseProductAction[]
     */
    protected array $actions = [];

    abstract public function validate(\CartPriceService\Cart $cart): array ;

    public static function fromJson($class, $options) : BaseRule
    {
        $rule = new $class();
        if (!($rule instanceof \CartPriceService\Rules\BaseRule)) {
            throw new \RuntimeException('Unknown rule');
        }
        $actions = $options['actions'];
        unset($options['actions']);
        foreach($options as $name => $value) {
            $rule->$name = $value;
        }

        foreach($actions as $action) {
            $rule->addAction(\CartPriceService\Actions\BaseAction::fromJson($action['class'], $action['options']));
        }

        return $rule;
    }

    public function jsonSerialize(): mixed {
        $data = ['class'=>get_called_class(), 'options'=>[]];
        foreach(get_object_vars($this) as $name => $value) {
            $data['options'][$name] = $value;
        }

        return $data;
    }

    public function setActions(array $actions): BaseRule
    {
        $this->actions = $actions;
        return $this;
    }

    public function addAction(\CartPriceService\Actions\BaseAction $action): BaseRule
    {
        $this->actions[] = $action;
        return $this;
    }


    /**
     * @return \CartPriceService\Actions\BaseProductAction[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    public function setScope(string $scope): BaseRule
    {
        $this->scope = $scope;
        return $this;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function setStop(bool $stop): BaseRule
    {
        $this->stop = $stop;
        return $this;
    }

    public function isStop(): bool
    {
        return $this->stop;
    }
}
