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
use RuntimeException;
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
     * Set the value to the key no matter what.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return $this
     */
    public function set($key, $value)
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get the value related to the key.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        if ($this->containsKey($key)) {
            return $this->items[$key];
        }

        return $default;
    }

    /**
     * Test is the key exists.
     *
     * @param string $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return isset($this->items[$key]) || array_key_exists($key, $this->items);
    }

    /**
     * Test if this values exists.
     *
     * @param mixed $value
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
     * Add a new value to the collection, next numeric index will be used.
     *
     * @param mixed $item
     *
     * @return $this
     */
    public function add($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove the $key/value in the collection.
     *
     * @param string $key
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
     *  Remove the $key/value in the collection and return the removed value.
     *
     * @param string $key
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
     * Get the first time and reset and rewind.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->items);
    }

    /**
     * Get the last item.
     *
     * @return mixed
     */
    public function last()
    {
        return end($this->items);
    }

    /**
     * Map and return a new Collection.
     *
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
     * Map (in-place).
     *
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
     * Filter and return a new Collection.
     *
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
     * Filter (in-place).
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function prune(callable $callback)
    {
        foreach ($this->items as $key => $value) {
            if (!$callback($value, $key)) {
                $this->remove($key);
            }
        }

        return $this;
    }

    /**
     * Combine and return a new Collection.
     * It takes the keys of the current Collection and assign the values.
     *
     * @param Traversable $values
     * @param bool        $inPlace
     *
     * @performanceCompared true
     *
     * @return mixed
     */
    public function combine($values, $inPlace = false)
    {
        if (!is_array($values) && !($values instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.($inPlace ? 'replace' : 'combine').'.', $values);
        }

        if (count($values) != count($this->items)) {
            $this->doThrow(
                'Invalid input for '.($inPlace ? 'replace' : 'combine').', number of items does not match.',
                $values
            );
        }

        return array_combine($this->items, $values);
    }

    /**
     * Combine (in-place).
     *
     * @param Traversable $values
     *
     * @return $this
     */
    public function replace($values)
    {
        $this->items = $this->combine($values, true);

        return $this;
    }

    /**
     * Opposite of Combine
     * It keeps the values of the current Collection and assign new keys.
     *
     * @param Traversable $keys
     *
     * @return Collection
     */
    public function combineKeys($keys)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            $this->doThrow('Invalid input type for keyCombine.', $keys);
        }

        if (count($keys) != count($this->items)) {
            $this->doThrow('Invalid input for keyCombine, number of items does not match.', $keys);
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
     * CombineKeys (in-place).
     *
     * @param Traversable $keys
     *
     * @return $this
     */
    public function reindex($keys)
    {
        $this->items = $this->combineKeys($keys)->toArray();

        return $this;
    }

    /**
     * Reduce.
     *
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
     * Run the callback on each element (passive).
     *
     * @param callable $callback
     *
     * @performanceCompared true
     *
     * @return $this
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
     * Flip the keys and the values and return a new Collection.
     *
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
     * Flip (in-place).
     *
     * @return $this
     */
    public function invert()
    {
        $valuesThatAreKey = Factory::create();
        foreach ($this->items as $key => $value) {
            if (!$this->containsKey($value)) {
                $this->set($value, $key);
                $this->remove($key);
                continue;
            }
            $valuesThatAreKey->set($value, $key);
        }

        return $this->coalesce($valuesThatAreKey);
    }

    /**
     * Merge the items and the collections and return a new Collection.
     *
     * @param Traversable $items
     * @param bool        $inPlace
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function merge($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.($inPlace ? 'coalesce' : 'merge').'.', $items);
        }
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            $collection->set($key, $value);
        }

        return $collection;
    }

    /**
     * Merge (in-place).
     *
     * @param Traversable $items
     */
    public function coalesce($items)
    {
        $this->merge($items, true);
    }

    /**
     * Union the collection with Items.
     *
     * @param Traversable $items
     * @param bool        $inPlace
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function union($items, $inPlace = false)
    {
        if (!is_array($items) && !($items instanceof Traversable)) {
            $this->doThrow('Invalid input type for '.($inPlace ? 'absorb' : 'union'), $items);
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
     * Union (in-place).
     *
     * @param Traversable $items
     *
     * @return $this
     */
    public function absorb($items)
    {
        $this->union($items, true);

        return $this;
    }

    /**
     * Assert that the callback result is $expected for all.
     *
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
     * Return all the values.
     *
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
     * Return all the keys.
     *
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
     * Pass the collection to the given callback and return the result.
     *
     * @param callable $callback
     *
     * @return mixed
     */
    public function pipe(callable $callback)
    {
        return $callback($this);
    }

    /**
     * Shuffle. (random in-place).
     *
     * @return $this
     */
    public function shuffle()
    {
        shuffle($this->items);

        return $this;
    }

    /**
     * Shuffle and return a new Collection.
     *
     * @return Collection
     */
    public function random()
    {
        $array = $this->items;
        shuffle($array);

        return Factory::create($array);
    }

    /**
     * Deduplicate the collection and return a new Collection.
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function unique()
    {
        return Factory::create(array_unique($this->items));
    }

    /**
     * Unique (in-place).
     *
     * @return $this
     */
    public function distinct()
    {
        $this->items = array_unique($this->items);

        return $this;
    }

    /**
     * Reverse the collection and return a new Collection.
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function reverse()
    {
        return Factory::create(array_reverse($this->items, true));
    }

    /**
     * Reverse (in-place).
     *
     * @return $this
     */
    public function inverse()
    {
        $this->items = array_reverse($this->items);

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
        if (!$this->containsKey($offset)) {
            throw new RuntimeException("Unknown offset: {$offset}");
        }
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
    /* --        OTHERS        -- */
    /* --         ---          -- */

    /**
     * @param string $message
     * @param mixed  $arguments
     *
     * @throws InvalidArgumentException
     */
    protected function doThrow($message, $arguments)
    {
        unset($arguments);
        throw new InvalidArgumentException($message);
    }

    /**
     * @return $this
     */
    public function dump()
    {
        return $this;
    }
}
