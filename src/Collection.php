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
use JsonSerializable;
use RuntimeException;
use Traversable;

/**
 * Class Collection.
 */
class Collection implements ArrayAccess, Iterator, Countable, JsonSerializable
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
     * @todo: check here
     *
     * @param $index
     *
     * @return mixed|null
     */
    public function indexOf($index)
    {
        $i = 0;
        foreach ($this->items as $key => $value) {
            if ($i++ == $index) {
                return $value;
            }
        }

        return null;
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
     *
     * @todo
     *      I think we should add an $offset or $index argument to this as the second argument.
     *      If it is present, this method sill check if $this->containsKey($offset) and if it does, nothing happens.
     *      If it does not, then $this->set($key, $value)
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
     * @param callable|null $callback
     *
     * @return mixed
     */
    public function first(callable $callback = null)
    {
        if ($callback == null) {
            return reset($this->items);
        }

        return $this->filter($callback)->first();
    }

    /**
     * Shift an element off the beginning of the collection(in-place).
     *
     * @performanceCompared true
     */
    public function shift()
    {
        reset($this->items);

        return $this->pull($this->key());
    }

    /**
     * Shift an element off the beginning of the collection(in-place).
     *
     * @performanceCompared true
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Get the last item.
     *
     * @param callable|null $callback
     *
     * @return mixed
     */
    public function last(callable $callback = null)
    {
        if ($callback == null) {
            return end($this->items);
        }

        return $this->filter($callback)->last();
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
        $index      = 0;
        foreach ($this->items as $key => $value) {
            $collection->set($key, $callback($value, $key, $index++));
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
        $index = 0;
        foreach ($this->items as $key => $value) {
            $this->set($key, $callback($value, $key, $index++));
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
        $index      = 0;
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key, $index++)) {
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
        $index = 0;
        foreach ($this->items as $key => $value) {
            if (!$callback($value, $key, $index++)) {
                $this->remove($key);
            }
        }

        return $this;
    }

    /**
     * Combine and return a new Collection.
     * It takes the keys of the current Collection and assign the values.
     *
     * @param Traversable|array $values
     * @param bool              $inPlace
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
        $values = Factory::getArrayForItems($values);

        return Factory::create(array_combine($this->items, $values));
    }

    /**
     * Combine (in-place).
     *
     * @param Traversable|array $values
     *
     * @return $this
     */
    public function replace($values)
    {
        $this->items = $this->combine($values, true)->toArray();

        return $this;
    }

    /**
     * Opposite of Combine
     * It keeps the values of the current Collection and assign new keys.
     *
     * @param Traversable|array $keys
     *
     * @return Collection
     */
    public function combineKeys($keys)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            $this->doThrow('Invalid input type for combineKeys.', $keys);
        }

        if (count($keys) != count($this->items)) {
            $this->doThrow('Invalid input for combineKeys, number of items does not match.', $keys);
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
     * @param Traversable|array $keys
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
        $index       = 0;
        foreach ($this->items as $key => $value) {
            $accumulator = $callback($accumulator, $value, $key, $index++);
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
     * @param Traversable|array $items
     * @param bool              $inPlace
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
     * @param Traversable|array $items
     *
     * @return $this
     */
    public function coalesce($items)
    {
        return $this->merge($items, true);
    }

    /**
     * Union the collection with Items.
     *
     * @param Traversable|array $items
     * @param bool              $inPlace
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
     * @param Traversable|array $items
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
        $index = 0;
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key, $index++) !== $expected) {
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
        return Factory::create(array_values($this->items));
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
        return Factory::create(array_values($this->items));
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
     * @param string $separator
     *
     * @return string
     */
    public function implode($separator)
    {
        return implode($this->items, $separator);
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
     * Merge the values items by items.
     *
     * @param Traversable|array $items
     *
     * @return Collection
     */
    public function zip($items)
    {
        $collection = Factory::create($items);

        return $this->map(
            function ($value, $key, $index) use ($collection) {
                return [$value, $collection->indexOf($index)];
            }
        );
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

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() == 0;
    }

    /**
     * Split in the collection in $count parts.
     *
     * @param int $count
     */
    public function split($count = 1)
    {
        return $this->chunk(ceil($this->count() / $count));
    }

    /**
     * Chunk of $size sub collection.
     *
     * @param int $size
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function chunk($size)
    {
        return Factory::create(array_chunk($this->items, $size, true));
    }

    /**
     * Get a slice of the collection and inject it in a new one.
     *
     * @param int      $offset
     * @param int|null $length
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function slice($offset, $length = null)
    {
        return Factory::create(array_slice($this->items, $offset, $length, true));
    }

    /**
     * Keep a slice of the collection (in-place).
     *
     * @param int      $offset
     * @param int|null $length
     *
     * @return $this
     */
    public function keep($offset, $length = null)
    {
        $this->items = $this->slice($offset, $length);

        return $this;
    }

    /**
     * Cut a slice of the collection (in-place).
     *
     * @param int      $offset
     * @param int|null $length
     *
     * @return $this
     */
    public function cut($offset, $length = null)
    {
        if ($length == null) {
            $length = PHP_INT_MAX;
        }
        if ($offset < 0) {
            $offset = $this->count() + $offset;
        }

        return $this->prune(
            function ($value, $key, $index) use ($offset, $length) {
                return !(($index >= $offset) && ($index < $offset + $length));
            }
        );
    }

    /**
     * Compares the collection against $items and returns the values that are not present in the collection.
     *
     * @param Traversable|array $values
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function diff($items)
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_diff($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the keys that are not present in the collection.
     *
     * @param Traversable|array $items
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function diffKeys($items)
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_diff_key($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the values that exist in the collection.
     *
     * @param Traversable|array $items
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function intersect($items)
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_intersect($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the keys that exist in the collection.
     *
     * @param Traversable|array $items
     *
     * @performanceCompared true
     *
     * @return Collection
     */
    public function intersectKeys($items)
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_intersect_key($this->items, $items));
    }

    /* --         ---          -- */
    /* -- INTERFACE COMPLIANCE -- */
    /* --         ---          -- */

    /* --                      -- */
    /* --    JsonSerializable  -- */
    /* --                      -- */

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

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
        $this->remove($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->add($value);
        }

        $this->set($offset, $value);
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
