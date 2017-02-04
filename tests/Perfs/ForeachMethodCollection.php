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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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

        $collection = Factory::create([], static::class);
        $this->rewind();
        foreach ($values as $value) {
            $collection->set($this->current(), $value);
            $this->next();
        }
        $this->rewind();

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function each(callable $callback)
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            $callback($value, $key, $index++);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
     * {@inheritdoc}
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
