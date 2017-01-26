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

/**
 * Class ForeachMethodCollection.
 */
class ForeachMethodCollection extends Collection
{
    /**
     * @param callable $callback
     *
     * @return Collection
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
     * @param callable $callback
     *
     * @return Collection
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
     * @param callable $callback
     * @param null     $initial
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
     * @param $values
     *
     * @return mixed
     */
    public function combine($values)
    {
        $collection = Factory::create([], static::class);
        $this->rewind();
        foreach ($values as $value) {
            $collection->set($this->key(), $value);
            $this->next();
        }
        $this->rewind();

        return $collection;
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        $index = 0;
        foreach ($this->items as $key => $value) {
            $callback($value, $key, $index++);
        }
    }

    /**
     * @return Collection
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
     * @return Collection
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
     * @return Collection
     */
    public function keys()
    {
        $collection = Factory::create([], static::class);
        foreach ($this->items as $key => $value) {
            $collection->add($key);
        }

        return $collection;
    }
}
