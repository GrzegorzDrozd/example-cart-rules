<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Actions;

/**
 * Base class for all actions. Usefull in typehints
 * @codeCoverageIgnore
 */
abstract class BaseAction implements \JsonSerializable
{
    public static function fromJson($class, $options)
    {
        $action = new $class();
        if (!($action instanceof BaseAction)) {
            throw new \RuntimeException('Unknown action');
        }
        foreach($options as $name => $value) {
            $action->$name = $value;
        }

        return $action;
    }

    public function jsonSerialize(): mixed {
        $data = ['class'=>get_called_class(), 'options'=>[]];
        foreach(get_object_vars($this) as $name => $value) {
            $data['options'][$name] = $value;
        }
        return $data;
    }
}
