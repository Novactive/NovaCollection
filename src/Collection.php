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
     * @return \Generator
     */
    protected function looper()
    {
        foreach ($this->items as $key => $value) {
            yield $key => $value;
        }
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
     * @return mixed
     */
    public function remove($key)
    {
        if (!$this->containsKey($key)) {
            return null;
        }
        $removed = $this->items[$key];
        unset($this->items[$key]);

        return $removed;
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        $index = 0;
        foreach ($this->looper() as $key => $value) {
            $callback($value, $key, $index++);
        }
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
     * @return static
     */
    public function map(callable $callback)
    {
        $collection = Factory::create();
        foreach ($this->looper() as $key => $value) {
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
        foreach ($this->looper() as $key => $value) {
            $this->set($key, $callback($value, $key));
        }

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function filter(callable $callback)
    {
        $collection = Factory::create();
        foreach ($this->looper() as $key => $value) {
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
    public function prune(callable $callback)
    {
        foreach ($this->looper() as $key => $value) {
            if ($callback($value, $key)) {
                $this->remove($key);
            }
        }

        return $this;
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
        foreach ($this->looper() as $key => $value) {
            $accumulator = $callback($accumulator, $value, $key);
        }

        return $accumulator;
    }

    /**
     * @param callable $callback
     * @param bool     $expected
     *
     * @return bool
     */
    public function assert(callable $callback, $expected)
    {
        foreach ($this->looper() as $key => $value) {
            if ($callback($value, $key) !== $expected) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $items
     */
    public function union($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            throw new InvalidArgumentException('Invalid input type for '.__METHOD__.', cannot union.');
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
     * Need another name.
     *
     * @param $items
     */
    public function iunion($items)
    {
        $this->union($items, true);
    }

    /**
     * @param $items
     */
    public function merge($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            throw new InvalidArgumentException('Invalid input type for '.__METHOD__.', cannot merge.');
        }
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            $collection->set($key, $value);
        }

        return $collection;
    }

    /**
     * Need another name.
     *
     * @param $items
     */
    public function imerge($items)
    {
        $this->merge($items, true);
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
}
