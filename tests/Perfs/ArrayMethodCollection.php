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
 * Class ArrayMethodCollection.
 */
class ArrayMethodCollection extends Collection
{
    public function map(callable $callback): Collection
    {
        $keys  = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return Factory::create(array_combine($keys, $items), static::class);
    }

    public function filter(callable $callback): Collection
    {
        return Factory::create(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH), static::class);
    }

    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
    }

    public function each(callable $callback): Collection
    {
        array_walk($this->items, $callback);

        return $this;
    }

    public function combine(iterable $values, bool $inPlace = false): Collection
    {
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

    public function flip(): Collection
    {
        return Factory::create(array_flip($this->items), static::class);
    }

    public function values(): Collection
    {
        return Factory::create(array_values($this->items), static::class);
    }

    public function keys(): Collection
    {
        return Factory::create(array_keys($this->items), static::class);
    }

    public function unique(): Collection
    {
        return Factory::create(array_unique($this->items), static::class);
    }

    public function contains($value): bool
    {
        return \in_array($value, $this->items, true);
    }

    public function merge(iterable $items, bool $inPlace = false): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }
        if ($inPlace) {
            $this->items = array_merge($this->items, $items);

            return $this;
        }

        return Factory::create(array_merge($this->items, $items), static::class);
    }

    public function union($items, $inPlace = false): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }
        if ($inPlace) {
            $this->items = $this->items + $items;

            return $this;
        }

        return Factory::create($this->items + $items, static::class);
    }

    public function reverse(): Collection
    {
        return Factory::create(array_reverse($this->items, true), static::class);
    }

    public function shift()
    {
        return array_shift($this->items);
    }

    public function pop()
    {
        return array_pop($this->items);
    }

    public function chunk(int $size): Collection
    {
        return Factory::create(array_chunk($this->items, $size, true), static::class);
    }

    public function slice(int $offset, ?int $length = PHP_INT_MAX): Collection
    {
        return Factory::create(\array_slice($this->items, $offset, $length, true), static::class);
    }

    public function diff(iterable $items): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_diff($this->items, $items), static::class);
    }

    public function diffKeys(iterable $items): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_diff_key($this->items, $items), static::class);
    }

    public function intersect(iterable $items): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_intersect($this->items, $items), static::class);
    }

    public function intersectKeys(iterable $items): Collection
    {
        if ($items instanceof Collection) {
            $items = $items->toArray();
        }

        return Factory::create(array_intersect_key($this->items, $items), static::class);
    }
}
