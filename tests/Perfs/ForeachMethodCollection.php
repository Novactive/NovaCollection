<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Tests\Perfs;

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;
use Traversable;

/**
 * Class ForeachMethodCollection.
 */
class ForeachMethodCollection extends Collection
{
    /**
     * @param callable $callback
     *
     * @return Collection
     */
    public function map(callable $callback)
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->set($key, $callback($value, $key));
        }

        return $collection;
    }

    /**
     * @param callable $callback
     *
     * @return Collection
     */
    public function filter(callable $callback)
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    /**
     * @param callable $callback
     * @param null     $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        $accumulator = $initial;
        foreach ($this->items as $key => $value) {
            $accumulator = $callback($accumulator, $value, $key);
        }

        return $accumulator;
    }

    /**
     * @param $values
     *
     * @return mixed
     */
    public function combine($values)
    {
        $collection = Factory::create([], static::class);
        $this->rewind();
        foreach ($values as $value) {
            $collection->set($this->key(), $value);
            $this->next();
        }
        $this->rewind();

        return $collection;
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            $callback($value, $key, $index++);
        }
    }

    /**
     * @return Collection
     */
    public function flip()
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->set($value, $key);
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public function values()
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $value) {
            $collection->add($value);
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public function keys()
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->add($key);
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public function unique()
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $value) {
            // testing with in_array to not be dependant as in_array is faster and this collection would use foreach
            // we want to test unique here, not in_array
            if (!in_array($value, $this->items, true)) {
                $collection->add($value);
            }
        }

        return $collection;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function contains($value)
    {
        foreach ($this->items as $val) {
            if ($value === $val) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param      $items
     * @param bool $inPlace
     *
     * @return Collection
     */
    public function merge($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.__METHOD__.', cannot merge.', $items);
        }
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            $collection->set($key, $value);
        }

        return $collection;
    }

    /**
     * @param      $items
     * @param bool $inPlace
     *
     * @return Collection
     */
    public function union($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.__METHOD__.', cannot union.', $items);
        }
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            if (!$collection->containsKey($key)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    /**
     * @return Collection
     */
    public function reverse()
    {
        $collection = Factory::create([], static::class);
        $count      = $this->count();
        $keys       = $this->keys();
        $values     = $this->values();
        for ($i = $count; $i >= 0; $i--) {
            $collection->set($keys[$i], $values[$i]);
        }

        return $collection;
    }
}
