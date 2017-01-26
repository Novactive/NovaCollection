<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */

namespace Novactive\Collection;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use Traversable;

/**
 * Class Collection.
 */
class Collection implements ArrayAccess, Iterator, Countable
{
    /**
     * @var array
     */
    protected $items;

    /**
     * Collection constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->rewind();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        if ($this->containsKey($key)) {
            return $this->items[$key];
        }

        return null;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return isset($this->items[$key]) || array_key_exists($key, $this->items);
    }

    /**
     * @param $value
     *
     * @performanceCompared true
     *
     * @return bool
     */
    public function contains($value)
    {
        return in_array($value, $this->items, true);
    }

    /**
     * @param $item
     *
     * @return $this
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * @param $key
     *
     * @return $this
     */
    public function remove($key)
    {
        if (!$this->containsKey($key)) {
            return null;
        }
        unset($this->items[$key]);

        return $this;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function pull($key)
    {
        if (!$this->containsKey($key)) {
            return null;
        }
        $removed = $this->items[$key];
        unset($this->items[$key]);

        return $removed;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * @param callable $callback
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function map(callable $callback)
    {
        $collection = Factory::create();
        foreach ($this->items as $key => $value) {
            $collection->set($key, $callback($value, $key));
        }

        return $collection;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function transform(callable $callback)
    {
        foreach ($this->items as $key => $value) {
            $this->set($key, $callback($value, $key));
        }

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function filter(callable $callback)
    {
        $collection = Factory::create();
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    //@todo: find a better name
    public function ifilter(callable $callback)
    {
        //@todo: filter in-place
        return $this;
    }

    /**
     * @param $values
     *
     * @performanceCompared true
     *
     * @return mixed
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
     * @param $values
     *
     * @return $this
     */
    public function replace($values)
    {
        //@todo: combine in-pace
        return $this;
    }

    /**
     * @param $keys
     *
     * @return Collection
     */
    //@todo: find a better name
    public function keyCombine($keys)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.__METHOD__.', cannot keyCombine.', $keys);
        }

        if (count($keys) != count($this->items)) {
            $this->doThrow('Invalid input for '.__METHOD__.', number of items does not match.', $keys);
        }
        $collection = Factory::create();
        $this->rewind();
        foreach ($keys as $key) {
            $collection->set($key, $this->current());
            $this->next();
        }
        $this->rewind();

        return $collection;
    }

    /**
     * @param $keys
     *
     * @return $this
     */
    public function reindex($keys)
    {
        //@todo: keyCombine in-place
        return $this;
    }

    /**
     * @param callable $callback
     * @param null     $initial
     *
     * @performanceCompared true
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
     * @param callable $callback
     *
     * @performanceCompared true
     */
    public function each(callable $callback)
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            $callback($value, $key, $index++);
        }
    }

    /**
     * @performanceCompared true
     *
     * @return Collection
     */
    public function flip()
    {
        $collection = Factory::create();
        foreach ($this->items as $key => $value) {
            $collection->set($value, $key);
        }

        return $collection;
    }

    /**
     * @return $this
     */
    public function invert()
    {
        //@todo: flip in-place
        return $this;
    }

    /**
     * @param      $items
     * @param bool $inPlace
     *
     * @performanceCompared true
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
     * @param $items
     */
    public function coalesce($items)
    {
        $this->merge($items, true);
    }

    /**
     * @param      $items
     * @param bool $inPlace
     *
     * @performanceCompared true
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
     * @param $items
     */
    public function absorb($items)
    {
        $this->union($items, true);
    }

    /**
     * @param callable $callback
     * @param bool     $expected
     *
     * @return bool
     */
    public function assert(callable $callback, $expected)
    {
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key) !== $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * @performanceCompared true
     *
     * @return Collection
     */
    public function values()
    {
        $collection = Factory::create();
        foreach ($this->items as $value) {
            $collection->add($value);
        }

        return $collection;
    }

    /**
     * @performanceCompared true
     *
     * @return Collection
     */
    public function keys()
    {
        $collection = Factory::create();
        foreach ($this->items as $key => $value) {
            $collection->add($key);
        }

        return $collection;
    }

    /**
     * @param callable $callback
     *
     * @return Collection
     */
    public function prune(callable $callback)
    {
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                $this->remove($key);
            }
        }

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    //@todo: find better name
    public function iprune(callable $callback)
    {
        //@todo: prune in-place

        return $this;
    }

    /**
     * @return $this
     */
    public function shuffle()
    {
        shuffle($this->items);

        return $this;
    }

    /**
     * @return Collection
     */
    public function random()
    {
        $array = $this->items;
        shuffle($array);

        return Factory::create($array);
    }

    /**
     * @performanceCompared true
     *
     * @return Collection
     */
    public function unique()
    {
        return Factory::create(array_unique($this->items));
    }

    //@todo: find better name
    public function iunique()
    {
        //@todo: unique inplace
        return $this;
    }

    /**
     * @performanceCompared true
     *
     * @return Collection
     */
    public function reverse()
    {
        return Factory::create(array_reverse($this->items, true));
    }

    /**
     * @return $this
     */
    public function inverse()
    {
        //@todo: unique reverse
        return $this;
    }

    /* --         ---          -- */
    /* -- INTERFACE COMPLIANCE -- */
    /* --         ---          -- */

    /* --                      -- */
    /* -- Array Access Methods -- */
    /* --                      -- */

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            return $this->add($value);
        }

        $this->set($offset, $value);

        return true;
    }

    /* --                     -- */
    /* --  Iterator Methods   -- */
    /* --                     -- */

    /**
     * {@inheritDoc}
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function rewind()
    {
        reset($this->items);
    }

    /**
     * {@inheritDoc}
     */
    public function valid()
    {
        return $this->containsKey(key($this->items));
    }

    /* --                             -- */
    /* --  Countable Method           -- */
    /* --                             -- */

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /* --         ---          -- */
    /* -- INTERFACE COMPLIANCE -- */
    /* --         ---          -- */

    /**
     * @param $message
     * @param $arguments
     *
     * @throws InvalidArgumentException
     */
    protected function doThrow($message, $arguments)
    {
        unset($arguments);
        throw new InvalidArgumentException($message);
    }

    /**
     * @return static
     */
    public function dump()
    {
        return $this;
    }
}
