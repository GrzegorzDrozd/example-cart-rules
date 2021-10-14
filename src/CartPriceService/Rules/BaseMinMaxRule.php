<?php
/*
 * @license LICENSE.TXT
 * @author  Grzegorz Drozd <grzegorz.drozd@gmail.com>
 * @package example-cart-rules
 * @copyright Grzegorz Drozd
 */

namespace CartPriceService\Rules;

/**
 * Base class for all min and max checking
 */
abstract class BaseMinMaxRule extends BaseRule
{
    protected float $min = 0;
    protected float $max = 0;
    protected bool $minInclusive = false;
    protected bool $maxInclusive = false;

    protected function checkValue(int $value): bool
    {
        $check = true;
        if (!empty($this->min) && $this->minInclusive) {
            $check = $value >= $this->min;
        } else if (!empty($this->min)) {
            $check = $value > $this->min;
        }

        if (!$check) {
            return false;
        }
        if (!empty($this->max) && $this->maxInclusive) {
            $check = $value <= $this->max;
        } else if (!empty($this->max)) {
            $check = $value < $this->max;
        }

        if (!$check) {
            return false;
        }

        return true;
    }

    public function getMin(): float
    {
        return $this->min;
    }

    public function isMinInclusive(): bool
    {
        return $this->minInclusive;
    }

    public function setMin(float $min, bool $inclusive = false): BaseMinMaxRule
    {
        $this->min          = $min;
        $this->minInclusive = $inclusive;
        return $this;
    }

    public function getMax(): float
    {
        return $this->max;
    }

    public function isMaxInclusive(): bool
    {
        return $this->maxInclusive;
    }

    public function setMax(float $max, bool $inclusive = false): BaseMinMaxRule
    {
        $this->max          = $max;
        $this->maxInclusive = $inclusive;
        return $this;
    }
}
