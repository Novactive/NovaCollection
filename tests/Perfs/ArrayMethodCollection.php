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
     * {@inheritdoc}
     */
    public function map(callable $callback)
    {
        $keys  = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return Factory::create(array_combine($keys, $items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $callback)
    {
        return Factory::create(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    /**
     * {@inheritdoc}
     */
    public function each(callable $callback)
    {
        array_walk($this->items, $callback);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function combine($values, $inPlace = false)
    {
        if (!is_array($values) && !($values instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.($inPlace ? 'replace' : 'combine').'.', $values);
        }

        // @todo This may change things performance-wise. I had to add this for Traversable $values to work - LV
        $values = Factory::getArrayForItems($values);
        if (count($values) != count($this->items)) {
            $this->doThrow(
                'Invalid input for '.($inPlace ? 'replace' : 'combine').', number of items does not match.',
                $values
            );
        }
        if ($inPlace) {
            $this->items = array_combine($this->items, $values);

            return $this;
        }

        return Factory::create(array_combine($this->items, $values));
    }

    /**
     * {@inheritdoc}
     */
    public function flip()
    {
        return Factory::create(array_flip($this->items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return Factory::create(array_values($this->items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function keys()
    {
        return Factory::create(array_keys($this->items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function unique()
    {
        return Factory::create(array_unique($this->items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function contains($value)
    {
        return in_array($value, $this->items, true);
    }

    /**
     * {@inheritdoc}
     */
    public function merge($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.__METHOD__.', cannot merge.', $items);
        }
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }
        if ($inPlace) {
            $this->items = array_merge($this->items, $items);

            return $this;
        }

        return Factory::create(array_merge($this->items, $items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function union($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.($inPlace ? 'absorb' : 'union'), $items);
        }
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }
        if ($inPlace) {
            $this->items = $this->items + $items;

            return $this;
        }

        return Factory::create($this->items + $items, static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function reverse()
    {
        return Factory::create(array_reverse($this->items, true), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function chunk($size)
    {
        return Factory::create(array_chunk($this->items, $size, true), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = PHP_INT_MAX)
    {
        return Factory::create(array_slice($this->items, $offset, $length, true), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function diff($items)
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_diff($this->items, $items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function diffKeys($items)
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_diff_key($this->items, $items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function intersect($items)
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_intersect($this->items, $items), static::class);
    }

    /**
     * {@inheritdoc}
     */
    public function intersectKeys($items)
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_intersect_key($this->items, $items), static::class);
    }
}
