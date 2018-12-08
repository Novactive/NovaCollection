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

namespace Novactive\Tests\Perfs;

use Novactive\Collection\Collection;
use Novactive\Collection\Factory;

/**
 * Class ForeachMethodCollection.
 */
class ForeachMethodCollection extends Collection
{
    public function map(callable $callback): Collection
    {
        $collection = Factory::create();
        $index      = 0;
        foreach ($this->items as $key => $value) {
            $collection->set($key, $callback($value, $key, $index++));
        }

        return $collection;
    }

    public function filter(callable $callback): Collection
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

    public function reduce(callable $callback, $initial = null)
    {
        $accumulator = $initial;
        $index       = 0;
        foreach ($this->items as $key => $value) {
            $accumulator = $callback($accumulator, $value, $key, $index++);
        }

        return $accumulator;
    }

    public function combine(iterable $values, bool $inPlace = false): Collection
    {
        if (count($values) != count($this->items)) {
            $this->doThrow(
                'Invalid input for '.($inPlace ? 'replace' : 'combine').', number of items does not match.',
                Factory::getArrayForItems($values)
            );
        }
        $values     = Factory::getArrayForItems($values);
        $collection = Factory::create([], static::class);
        $this->rewind();
        foreach ($values as $value) {
            $collection->set($this->current(), $value);
            $this->next();
        }
        $this->rewind();

        return $collection;
    }

    public function each(callable $callback): Collection
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            $callback($value, $key, $index++);
        }

        return $this;
    }

    public function flip(): Collection
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->set($value, $key);
        }

        return $collection;
    }

    public function values(): Collection
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $value) {
            $collection->add($value);
        }

        return $collection;
    }

    public function keys(): Collection
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->add($key);
        }

        return $collection;
    }

    public function unique(): Collection
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            // testing with in_array to not be dependant as in_array is faster and this collection would use foreach
            // we want to test unique here, not in_array
            if (!\in_array($value, $collection->toArray(), true)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    public function contains($value): bool
    {
        foreach ($this->items as $val) {
            if ($value === $val) {
                return true;
            }
        }

        return false;
    }

    public function merge(iterable $items, bool $inPlace = false): Collection
    {
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            $collection->set($key, $value);
        }

        return $collection;
    }

    public function union(iterable $items, bool $inPlace = false): Collection
    {
        $collection = $inPlace ? $this : clone $this;
        foreach ($items as $key => $value) {
            if (!$collection->containsKey($key)) {
                $collection->set($key, $value);
            }
        }

        return $collection;
    }

    public function reverse(): Collection
    {
        $collection = Factory::create([], static::class);
        $count      = $this->count();
        $keys       = $this->keys();
        $values     = $this->values();

        for ($i = $count - 1; $i >= 0; --$i) {
            $collection->set($keys[$i], $values[$i]);
        }

        return $collection;
    }

    public function shift()
    {
        reset($this->items);

        return $this->pull($this->key());
    }

    public function pop()
    {
        end($this->items);

        return $this->pull($this->key());
    }

    public function chunk(int $size): Collection
    {
        $collection = Factory::create();
        $chunk      = Factory::create();
        foreach ($this->items as $key => $value) {
            $chunk->set($key, $value);
            if ($chunk->count() == $size) {
                $collection->add($chunk);
                $chunk = Factory::create();
            }
        }
        if (!$chunk->isEmpty()) {
            $collection->add($chunk);
        }

        return $collection;
    }

    public function slice(int $offset, ?int $length = PHP_INT_MAX): Collection
    {
        if ($offset < 0) {
            $offset = $this->count() + $offset;
        }

        return $this->filter(
            function ($value, $key, $index) use ($offset, $length) {
                return ($index >= $offset) && ($index < $offset + $length);
            }
        );
    }

    public function diff(iterable $items): Collection
    {
        $itemsCollection = Factory::create($items);

        return $this->filter(
            function ($value, $key, $index) use ($itemsCollection) {
                return !$itemsCollection->contains($value);
            }
        );
    }

    public function diffKeys(iterable $items): Collection
    {
        $itemsCollection = Factory::create($items);

        return $this->filter(
            function ($value, $key, $index) use ($itemsCollection) {
                return !$itemsCollection->containsKey($key);
            }
        );
    }

    public function intersect(iterable $items): Collection
    {
        $itemsCollection = Factory::create($items);

        return $this->filter(
            function ($value, $key, $index) use ($itemsCollection) {
                return $itemsCollection->contains($value);
            }
        );
    }

    public function intersectKeys(iterable $items): Collection
    {
        $itemsCollection = Factory::create($items);

        return $this->filter(
            function ($value, $key, $index) use ($itemsCollection) {
                return $itemsCollection->containsKey($key);
            }
        );
    }
}
