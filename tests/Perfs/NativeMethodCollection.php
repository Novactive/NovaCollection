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

/**
 * Class NativeMethodCollection.
 *
 * @package Novactive\Tests
 */
class NativeMethodCollection extends Collection
{
    public function map(callable $callback)
    {
        return new Collection(array_map($callback, $this->items));
    }

    public function filter(callable $callback)
    {
        return new Collection(array_filter($this->items, $callback));
    }

    /**
     * @param callable $callback
     */
    public function each(callable $callback)
    {
        array_walk($this->items, $callback);
    }

    public function combine($values)
    {
        return array_combine($this->items, $values);
    }
}
