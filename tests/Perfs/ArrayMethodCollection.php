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
 * Class ArrayMethodCollection.
 */
class ArrayMethodCollection extends Collection
{
    /**
     * @param callable $callback
     *
     * @return Collection
     */
    public function map(callable $callback)
    {
        $keys  = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return Factory::create(array_combine($keys, $items), static::class);
    }

    /**
     * @param callable $callback
     *
     * @return Collection
     */
    public function filter(callable $callback)
    {
        return Factory::create(array_filter($this->items, $callback), static::class);
    }

    /**
     * @param callable $callback
     * @param null     $initial
     *
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        array_walk($this->items, $callback);
    }

    /**
     * @param $values
     *
     * @return array
     */
    public function combine($values)
    {
        if (!is_array($values) && !($values instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.__METHOD__.', cannot combine.', $values);
        }

        if (count($values) != count($this->items)) {
            $this->doThrow('Invalid input for '.__METHOD__.', number of items does not match.', $values);
        }

        return array_combine($this->items, $values);
    }

    /**
     * @return Collection
     */
    public function flip()
    {
        return Factory::create(array_flip($this->items), static::class);
    }

    /**
     * @return Collection
     */
    public function values()
    {
        return Factory::create(array_values($this->items), static::class);
    }

    /**
     * @return Collection
     */
    public function keys()
    {
        return Factory::create(array_keys($this->items), static::class);
    }

    /**
     * @return Collection
     */
    public function unique()
    {
        return Factory::create(array_unique($this->items), static::class);
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->items, true);
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

        return Factory::create(array_merge($this->items, $items), static::class);
    }

    /**
     * @param      $items
     * @param bool $inPlace
     *
     * @return Collection
     */
    public function union($items, $inPlace = false)
    {
        return Factory::create($this->items + $items, static::class);
    }

    /**
     * @return Collection
     */
    public function reverse()
    {
        return Factory::create(array_reverse($this->items, true), static::class);
    }
}
