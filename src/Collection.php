<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 *
 * @copyright 2017 Novactive
 * @license   MIT
 */
namespace Novactive\Collection;

use Traversable;

/**
 * Class Collection
 */
class Collection
{
    /**
     * @var array
     */
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return $this->items;
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
     * @return mixed
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->items);
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
     * @param $items
     *
     * @return $this
     */
    public function add($items)
    {
        if ($items instanceof Traversable) {
            foreach ($items as $item) {
                $this->add($item);
            }

            return $this;
        }
        $this->items[] = $items;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return null
     */
    public function remove($key)
    {
        if (!isset($this->items[$key]) && !array_key_exists($key, $this->items)) {
            return null;
        }
        $removed = $this->items[$key];
        unset($this->items[$key]);

        return $removed;
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
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
     * @param callable $callbackl
     *
     * @return mixed|null
     */
    public function reduce(callable $callback)
    {
        $accumulator = null;
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
    public function check(callable $callback, $expected)
    {
        foreach ($this->looper() as $key => $value) {
            if ($callback($value, $key) !== $expected) {
                return false;
            }
        }

        return true;
    }
}
