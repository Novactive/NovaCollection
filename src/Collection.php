<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
declare(strict_types=1);

namespace Novactive\Collection;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use JsonSerializable;
use Novactive\Collection\Selector\Range;
use RuntimeException;

class Collection implements ArrayAccess, Iterator, Countable, JsonSerializable
{
    /**
     * @var array
     */
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->rewind();
    }

    /**
     * Get the raw Array.
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * Set the value to the key no matter what.
     */
    public function set($key, $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    /**
     * Get the value related to the key.
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
     * Get the item at the given index (numerically).
     *
     * @return mixed|null
     */
    public function atIndex(int $index)
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
     * Get the key of a value if exists.
     *
     *
     * @return mixed|null
     */
    public function keyOf($value)
    {
        foreach ($this->items as $key => $iValue) {
            if ($value === $iValue) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Get the index of a value if exists (numerically).
     */
    public function indexOf($value): ?int
    {
        $i = 0;
        foreach ($this->items as $key => $iValue) {
            if ($value === $iValue) {
                return $i;
            }
            ++$i;
        }

        return null;
    }

    /**
     * Test is the key exists.
     */
    public function containsKey($key): bool
    {
        return isset($this->items[$key]) || array_key_exists($key, $this->items);
    }

    /**
     * Test if this values exists.
     *
     *
     * @performanceCompared true
     */
    public function contains($value): bool
    {
        return \in_array($value, $this->items, true);
    }

    /**
     * Add a new value to the collection, next numeric index will be used. (in-place).
     */
    public function add($item): self
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Append the items at the end of the collection not regarding the keys. (in-place).
     */
    public function append(iterable $values): self
    {
        foreach ($values as $value) {
            $this->add($value);
        }

        return $this;
    }

    /**
     * Clear the collection of all its items. (in-place).
     */
    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    /**
     * Remove the $key/value in the collection. (in-place).
     */
    public function remove($key): self
    {
        unset($this->items[$key]);

        return $this;
    }

    /**
     *  Remove the $key/value in the collection and return the removed value. (in-place).
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
     */
    public function first(callable $callback = null)
    {
        if (null === $callback) {
            return reset($this->items);
        }

        return $this->filter($callback)->first();
    }

    /**
     * Shift an element off the beginning of the collection. (in-place).
     *
     * @performanceCompared true
     */
    public function shift()
    {
        reset($this->items);

        return $this->pull($this->key());
    }

    /**
     * Shift an element off the end of the collection. (in-place).
     *
     * @performanceCompared true
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * Get the last item.
     */
    public function last(callable $callback = null)
    {
        if (null === $callback) {
            return end($this->items);
        }

        return $this->filter($callback)->last();
    }

    /**
     * Map and return a new Collection.
     *
     * @performanceCompared true
     */
    public function map(callable $callback): self
    {
        $collection = Factory::create();
        $index      = 0;
        foreach ($this->items as $key => $value) {
            $collection->set($key, $callback($value, $key, $index++));
        }

        return $collection;
    }

    /**
     * Map keys (and/or value) and return a new Collection.
     */
    public function mapKeys(callable $callback, callable $callbackValue = null): self
    {
        $collection = Factory::create();
        $index      = 0;
        foreach ($this->items as $key => $value) {
            $collection->set(
                $callback($value, $key, $index),
                null === $callbackValue ? $value : $callbackValue($value, $key, $index)
            );
            ++$index;
        }

        return $collection;
    }

    /**
     * Map. (in-place).
     */
    public function transform(callable $callback): self
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
     * @performanceCompared true
     */
    public function filter(callable $callback): self
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
     * Filter. (in-place).
     */
    public function prune(callable $callback): self
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            if ($callback($value, $key, $index++)) {
                $this->remove($key);
            }
        }

        return $this;
    }

    /**
     * Combine and return a new Collection.
     * It takes the keys of the current Collection and assign the values.
     *
     * @performanceCompared true
     */
    public function combine(iterable $values, bool $inPlace = false): self
    {
        if (count($values) != count($this->items)) {
            $this->doThrow(
                'Invalid input for '.($inPlace ? 'replace' : 'combine').', number of items does not match.',
                Factory::getArrayForItems($values)
            );
        }
        $values = Factory::getArrayForItems($values);

        return Factory::create(array_combine($this->items, $values));
    }

    /**
     * Combine. (in-place).
     */
    public function replace(iterable $values): self
    {
        $this->items = $this->combine($values, true)->toArray();

        return $this;
    }

    /**
     * Opposite of Combine
     * It keeps the values of the current Collection and assign new keys.
     */
    public function combineKeys(iterable $keys): self
    {
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
     * CombineKeys. (in-place).
     */
    public function reindex(iterable $keys): self
    {
        $this->items = $this->combineKeys($keys)->toArray();

        return $this;
    }

    /**
     * Reduce.
     *
     * @performanceCompared true
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
     * Run the callback on each element. (passive).
     *
     * @performanceCompared true
     */
    public function each(callable $callback): self
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
     */
    public function flip(): self
    {
        $collection = Factory::create();
        foreach ($this->items as $key => $value) {
            $collection->set($value, $key);
        }

        return $collection;
    }

    /**
     * Flip. (in-place).
     */
    public function invert(): self
    {
        $this->items = $this->flip()->toArray();

        return $this;
    }

    /**
     * Merge the items and the collections and return a new Collection.
     *
     * Note that is a real merge, numerical keys are merged too.(unlike array_merge)
     *
     * @performanceCompared true
     */
    public function merge(iterable $items, bool $inPlace = false): self
    {
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            $collection->set($key, $value);
        }

        return $collection;
    }

    /**
     * Merge. (in-place).
     */
    public function coalesce(iterable $items): self
    {
        return $this->merge($items, true);
    }

    /**
     * Union the collection with Items and return a new Collection.
     *
     * @performanceCompared true
     */
    public function union(iterable $items, bool $inPlace = false): self
    {
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            if (!$collection->containsKey($key)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    /**
     * Union. (in-place).
     */
    public function absorb(iterable $items): self
    {
        $this->union($items, true);

        return $this;
    }

    /**
     * Assert that the callback result is $expected for all.
     */
    public function assert(callable $callback, bool $expected): bool
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
     */
    public function values(): self
    {
        return Factory::create(array_values($this->items));
    }

    /**
     * Return all the keys.
     *
     * @performanceCompared true
     */
    public function keys(): self
    {
        return Factory::create(array_keys($this->items));
    }

    /**
     * Pass the collection to the given callback and return the result.
     */
    public function pipe(callable $callback)
    {
        return $callback($this);
    }

    /**
     * Random. (in-place).
     */
    public function shuffle(): self
    {
        shuffle($this->items);

        return $this;
    }

    /**
     * Shuffle and return a new Collection.
     */
    public function random(): self
    {
        $array = $this->items;
        shuffle($array);

        return Factory::create($array);
    }

    /**
     * Deduplicate the collection and return a new Collection.
     *
     * @performanceCompared true
     */
    public function unique(): self
    {
        return Factory::create(array_unique($this->items));
    }

    /**
     * Join the items using the $separator.
     */
    public function implode(string $separator): string
    {
        return implode($this->items, $separator);
    }

    /**
     * Unique. (in-place).
     */
    public function distinct(): self
    {
        $this->items = array_unique($this->items);

        return $this;
    }

    /**
     * Merge the values items by items and return a new Collection.
     */
    public function zip(iterable $items): self
    {
        $collection = Factory::create($items);

        return $this->map(
            function ($value, $key, $index) use ($collection) {
                return [$value, $collection->atIndex($index)];
            }
        );
    }

    /**
     * Reverse the collection and return a new Collection.
     *
     * @performanceCompared true
     */
    public function reverse(): self
    {
        return Factory::create(array_reverse($this->items, true));
    }

    /**
     * Reverse. (in-place).
     */
    public function inverse(): self
    {
        $this->items = array_reverse($this->items);

        return $this;
    }

    /**
     * Tells if the collection is empty.
     */
    public function isEmpty(): bool
    {
        return 0 === $this->count();
    }

    /**
     * Split in the collection in $count parts and return a new Collection.
     */
    public function split(int $count = 1): self
    {
        return $this->chunk((int) ceil($this->count() / $count));
    }

    /**
     * Chunk of $size sub collection and return a new Collection.
     *
     * @performanceCompared true
     */
    public function chunk(int $size): self
    {
        return Factory::create(array_chunk($this->items, $size, true));
    }

    /**
     * Get a slice of the collection and inject it in a new Collection.
     *
     * @performanceCompared true
     */
    public function slice(int $offset, ?int $length = null): self
    {
        return Factory::create(\array_slice($this->items, $offset, $length, true));
    }

    /**
     * Keep a slice of the collection. (in-place).
     */
    public function keep(int $offset, ?int $length = null): self
    {
        $this->items = $this->slice($offset, $length)->toArray();

        return $this;
    }

    /**
     * Apply the callback $onKeyFound on the key/value if the key exists. (passive).
     *
     * Apply a callback $onKeyNotFound on the key if the key does NOT exists
     *
     * @return mixed|null
     */
    public function applyOn($key, callable $onKeyFound, callable $onKeyNotFound = null)
    {
        if ($this->containsKey($key)) {
            return $onKeyFound($key, $this->get($key));
        }

        return null !== $onKeyNotFound ? $onKeyNotFound($key) : null;
    }

    /**
     * Cut a slice of the collection. (in-place).
     *
     * @return $this
     */
    public function cut(int $offset, ?int $length = null): self
    {
        if (null === $length) {
            $length = PHP_INT_MAX;
        }
        if ($offset < 0) {
            $offset = $this->count() + $offset;
        }

        return $this->prune(
            function ($value, $key, $index) use ($offset, $length) {
                return ($index >= $offset) && ($index < $offset + $length);
            }
        );
    }

    /**
     * Compares the collection against $items and returns the values that are not present in the collection.
     *
     * @performanceCompared true
     */
    public function diff(iterable $items): self
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_diff($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the keys that are not present in the collection.
     *
     * @performanceCompared true
     */
    public function diffKeys(iterable $items): self
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_diff_key($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the values that exist in the collection.
     *
     * @performanceCompared true
     */
    public function intersect(iterable $items): self
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_intersect($this->items, $items));
    }

    /**
     * Compares the collection against $items and returns the keys that exist in the collection.
     *
     * @performanceCompared true
     */
    public function intersectKeys(iterable $items): self
    {
        $items = Factory::getArrayForItems($items);

        return Factory::create(array_intersect_key($this->items, $items));
    }

    /**
     * Return true if one item return true to the callback.
     */
    public function exists(callable $callback): bool
    {
        $index = 0;
        foreach ($this->items as $key => $item) {
            if (true === $callback($item, $key, $index++)) {
                return true;
            }
        }

        return false;
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function offsetExists($offset)
    {
        return $this->containsKey($offset);
    }

    public function offsetGet($offset)
    {
        if (!$this->containsKey($offset)) {
            throw new RuntimeException("Unknown offset: {$offset}");
        }

        return $this->get($offset);
    }

    public function offsetUnset($offset)
    {
        $this->remove($offset);
    }

    public function offsetSet($offset, $value)
    {
        if (!isset($offset)) {
            $this->add($value);
        }

        $this->set($offset, $value);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function rewind()
    {
        reset($this->items);
    }

    public function valid()
    {
        return $this->containsKey(key($this->items));
    }

    public function count()
    {
        return count($this->items);
    }

    protected function doThrow(string $message, array $arguments)
    {
        unset($arguments);
        throw new InvalidArgumentException($message);
    }

    public function dump(): self
    {
        return $this;
    }

    /**
     * @param array ...$params
     *
     * @throws InvalidArgumentException
     *
     * @return Collection
     */
    public function __invoke(...$params)
    {
        $tool       = new Range();
        $parameters = Factory::create($params);
        if ($tool->supports($parameters)) {
            return $tool->convert($parameters, $this);
        }
        $this->doThrow('No Selector is able to handle this invocation.', $params);
    }
}
